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
        error_log("PUSH\n\n", 3, getenv('LOGS_TRANSACTION'));
        error_log("1 - Iniciando processo de transferência\n", 3, getenv('LOGS_TRANSACTION'));

        error_log("2 - Validando dados da solicitação de transferencia\n", 3, getenv('LOGS_TRANSACTION'));
        $this->validate->request($request);

        //instancia o pagador
        $this->payer = $this->usersRepository
            ->findByAttribute("document", $request->input('payer_document'));

        //instancia o recebedor
        $this->payee = $this->usersRepository
            ->findByAttribute("document", $request->input('payee_document'));

        error_log("3 - Persistindo Transação: status = solicitado\n", 3, getenv('LOGS_TRANSACTION'));
        $this->transaction
            ->setPayer($this->payer)
            ->setPayee($this->payee)
            ->setValue($request->input('value'))
            ->setTransactionType(self::TRANSACTION_TYPE)
            ->requested();

        $title = "Saque: Transferencia de {$this->payer->full_name} para "
            . "{$this->payee->full_name} às {$this->transaction->get()->created_at}";

        error_log("4 - Realizado saque na carteira do depositante\n", 3, getenv('LOGS_TRANSACTION'));
        $this->wallet
            ->setTransaction($this->transaction->get())
            ->setWallet($request->input('payer_document'))
            ->setValue($request->input('value'))
            ->setTitle($title)
            ->withdraw();

        error_log("5 - Persistindo Transação: status = processando\n", 3, getenv('LOGS_TRANSACTION'));
        $this->transaction->processing();

        error_log("6 - Disparadando notificação para o depositante\n", 3, getenv('LOGS_TRANSACTION'));
        $this->messenger->push([
            "message" => $title,
            "users_id" => $this->payer->id,
            "transactions_id" => $this->transaction->get()->id,
        ]);

        error_log("7 - Disparadando solicitação de debito na carteira do recebedor\n", 3, getenv('LOGS_TRANSACTION'));
        $this->dispatch($this->transaction, $this->wallet, $this->payee, $this->payer, $this->messenger);

        error_log("8 - Solicitação de transferencia concluida\n", 3, getenv('LOGS_TRANSACTION'));
        return $this->response();
    }

    public static function pull(
        Transaction $transaction,
        Wallet $wallet,
        Users $payee,
        Users $payer,
        Messenger $messenger
    ): void {
        error_log("\nPULL\n\n", 3, getenv('LOGS_TRANSACTION'));
        error_log("1 - Iniciando processo de debito na carteira do beneficiario\n", 3, getenv('LOGS_TRANSACTION'));

        $title = "Debito: Transferencia de {$payer->full_name} para "
            . "{$payee->full_name} às {$transaction->get()->created_at}";

        error_log("2 - Relizando Depósito na carteira do beneficiário\n", 3, getenv('LOGS_TRANSACTION'));
        $wallet
            ->setTransaction($transaction->get())
            ->setWallet($payee->id)
            ->setValue($transaction->get()->value)
            ->setTitle($title)
            ->deposit();

        error_log("3 - Persistindo Transação: status = processado\n", 3, getenv('LOGS_TRANSACTION'));
        $transaction->processed();

        error_log("4 - Disparadando notificação para o beneficiário\n", 3, getenv('LOGS_TRANSACTION'));
        $messenger->push([
            "message" => $title,
            "users_id" => $payee->id,
            "transactions_id" => $transaction->get()->id,
        ]);
        error_log("5 - Transação concluída\n", 3, getenv('LOGS_TRANSACTION'));
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
