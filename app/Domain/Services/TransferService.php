<?php

namespace App\Domain\Services;

use App\Domain\Contracts\TransferServiceInterface;
use App\Domain\Services\{Transaction, Wallet, Messenger};
use App\Domain\Repositories\UsersRepositoryInterface;
use App\Domain\Models\Users;
use App\Jobs\TransferJob;
use Illuminate\Http\Request;

class TransferService implements TransferServiceInterface
{
    private $validate;
    private $transaction;
    private $wallet;
    private $payer;
    private $payee;
    private $messenger;

    const TRANSACTION_TYPE = 1;

    public function __construct(
        TransferValidate $validate,
        Transaction $transaction,
        UsersRepositoryInterface $usersRepository,
        Wallet $wallet,
        TransferJob $transferJob,
        Messenger $messenger
    ) {
        $this->validate = $validate;
        $this->transaction = $transaction;
        $this->usersRepository = $usersRepository;
        $this->wallet = $wallet;
        $this->transferJob = $transferJob;
        $this->messenger = $messenger;
    }

    public function push(Request $request): array
    {

        //valida a solicitação de depósito
        $this->validate->request($request);

        //instancia o pagador
        $this->payer = $this->usersRepository
            ->findByAttribute("document", $request->input('payer_document'));

        //instancia o recebedor
        $this->payee = $this->usersRepository
            ->findByAttribute("document", $request->input('payee_document'));

        //inicia a tranasferencia : status = solicitado
        $this->transaction
            ->setPayer($this->payer)
            ->setPayee($this->payee)
            ->setValue($request->input('value'))
            ->setTransactionType(self::TRANSACTION_TYPE)
            ->requested();

        $title = "Saque: Transferencia de {$this->payer->full_name} para "
            . "{$this->payee->full_name} às {$this->transaction->get()->created_at}";

        //realiza o saque na carteira do pagador
        $this->wallet
            ->setTransaction($this->transaction->get())
            ->setWallet($request->input('payer_document'))
            ->setValue($request->input('value'))
            ->setTitle($title)
            ->withdraw();

        //atualiza o status da transição para : status = processando
        $this->transaction->processing();

        // enviar mensagem para o depositante
        $this->messenger->push([
            "message" => $title,
            "users_id" => $this->payer->id,
            "transactions_id" => $this->transaction->get()->id,
        ]);

        //envia a mensagem para fila do rabbitmk
        $this->dispatch($this->transaction, $this->wallet, $this->payee, $this->payer, $this->messenger);

        return $this->response();
    }

    public static function pull(
        Transaction $transaction,
        Wallet $wallet,
        Users $payee,
        Users $payer,
        Messenger $messenger
    ): void {
        $title = "Debito: Transferencia de {$payer->full_name} para "
            . "{$payee->full_name} às {$transaction->get()->created_at}";

        //realiza o depósito na carteira do recebedor
        $wallet
            ->setTransaction($transaction->get())
            ->setWallet($payee->id)
            ->setValue($transaction->get()->value)
            ->setTitle($title)
            ->deposit();

        //realiza o saque na carteira do pagador : status = processado
        $transaction->processed();

        // enviar mensagem para o beneficiário
        $messenger->push([
            "message" => $title,
            "users_id" => $payee->id,
            "transactions_id" => $transaction->get()->id,
        ]);
    }

    private function dispatch($transaction, $wallet, $payee, $payer, $messenger): void
    {
        $job = new TransferJob($transaction, $wallet, $payee, $payer, $messenger);

        if (getenv('APP_ENV') == 'local') {
            $job->delay(\Carbon\Carbon::now()->addSeconds(5));
        }

        dispatch($job);
    }

    private function response(): array
    {
        return [
            'transaction' => $this->transaction->get(),
            'payer' => $this->payer,
            'payee' => $this->payee,
            'wallet' => $this->wallet->get(),
            'operation' => $this->wallet->getOperation()
        ];
    }
}
