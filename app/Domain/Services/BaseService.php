<?php

namespace App\Domain\Services;

use App\Domain\Models\Users;

class BaseService
{
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
}
