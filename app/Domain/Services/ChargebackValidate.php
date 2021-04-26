<?php

namespace App\Domain\Services;

use App\Domain\Repositories\{
    TransactionsRepositoryInterface,
    ExternalAuthorizerRepositoryInterface
};

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChargebackValidate
{
    private $transaction;
    private $externalAuthorizer;

    const TRANSACTIONS_STATUS_ID = 3;
    const TRANSACTIONS_TYPE_ID = 1;

    public function __construct(
        TransactionsRepositoryInterface $transaction,
        ExternalAuthorizerRepositoryInterface $externalAuthorizer
    ) {
        $this->transaction = $transaction;
        $this->externalAuthorizer = $externalAuthorizer;
    }

    public function request(Request $request): void
    {
        // (x) valida o request
        $this->requestValidate($request);

        // (x) verificar se a transação existe
        // (x) o status da transação deve ser = 3 (processado)
        // (x) tipo de transação = 1 (Transferência)
        $this->transactionValidate($request);

        // (x) validar transferência em um autorizador externo
        $this->checkExternalAuthorizer($request);
    }

    private function requestValidate(Request $request): ?bool
    {
        $validator = Validator::make($request->all(), [
            'hash' => 'required',
        ], [
            'hash.required' => 'O hash da transação deve ser informado.',
        ]);

        if ($validator->fails()) {
            throw new \DomainException($validator->getMessageBag()->first(), 422);
        }
        return true;
    }

    private function transactionValidate(Request $request): ?bool
    {
        $transaction = $this->transaction->findByAttribute("hash", $request->input('hash'));

        if (!$transaction) {
            throw new \DomainException('Transação não encontrada.', 422);
        }

        if ($transaction->transactions_status_id != self::TRANSACTIONS_STATUS_ID) {
            throw new \DomainException('Atransação não pode ser estornada estando neste status.', 422);
        }

        if ($transaction->transactions_type_id != self::TRANSACTIONS_TYPE_ID) {
            throw new \DomainException('Este tipo de transação não pode ser estornada.', 422);
        }
        return true;
    }

    private function checkExternalAuthorizer(Request $request): ?bool
    {
        if (!$this->externalAuthorizer->check()) {
            throw new \DomainException('Erro ao autenticar a sua transação (Autenticador externo).', 422);
        }
        return true;
    }
}
