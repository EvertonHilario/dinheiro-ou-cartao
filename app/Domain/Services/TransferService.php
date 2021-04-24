<?php

namespace App\Domain\Services;

use App\Domain\Contracts\TransferServiceInterface;
use App\Domain\Services\{Transaction, Wallet};
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

    const TRANSACTION_TYPE = 1;

    public function __construct(
        TransferValidate $validate,
        Transaction $transaction,
        UsersRepositoryInterface $usersRepository,
        Wallet $wallet,
        TransferJob $transferJob
    ) {
        $this->validate = $validate;
        $this->transaction = $transaction;
        $this->usersRepository = $usersRepository;
        $this->wallet = $wallet;
        $this->transferJob = $transferJob;
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

        //realiza o saque na carteira do pagador
        $this->wallet
            ->setTransaction($this->transaction->get())
            ->setWallet($request->input('payer_document'))
            ->setValue($request->input('value'))
            ->setTitle(
                "Saque: Transferencia de {$this->payer->full_name} para "
                . "{$this->payee->full_name} às {$this->transaction->get()->created_at}"
            )
            ->withdraw();

        //atualiza o status da transição para : status = processando
        $this->transaction->processing();

        //envia a mensagem para fila do rabbitmk
        $this->dispatch($this->transaction, $this->wallet, $this->payee, $this->payer);

        // enviar mensagem para o depositante
        return $this->response();
    }

    public static function pull(Transaction $transaction, Wallet $wallet, Users $payee, Users $payer): void
    {
        //realiza o depósito na carteira do recebedor
        $wallet
            ->setTransaction($transaction->get())
            ->setWallet($payee->id)
            ->setValue($transaction->get()->value)
            ->setTitle(
                "Saque: Transferencia de {$payer->full_name} para "
                . "{$payee->full_name} às {$transaction->get()->created_at}"
            )
            ->deposit();

        //realiza o saque na carteira do pagador : status = processado
        $transaction->processed();

        // enviar mensagem para o beneficiário
    }

    private function dispatch($transaction, $wallet, $payee, $payer): void
    {
        $job = new TransferJob($transaction, $wallet, $payee, $payer);

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