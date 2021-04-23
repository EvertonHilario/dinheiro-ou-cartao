<?php

namespace App\Domain\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Domain\Repositories\{UsersRepositoryInterface, WalletsRepositoryInterface};

class TransferValidate
{
    private $users;
    private $wallets;

    const SHOPKEEPER_TYPE_ID = 2;

    public function __construct(UsersRepositoryInterface $users, WalletsRepositoryInterface $wallets)
    {
        $this->users = $users;
        $this->wallets = $wallets;
    }

    public function request(Request $request): void
    {
        // (x) valida o request
        $this->requestValidate($request);
        // (x) verificar se o value é maior que 0
        $this->isGreaterThanZero($request);
        // (x) verificar se o payer existe
        $this->thePayerExists($request);
        // (x) verificar se o payer tem saldo para realizar a transafrência
        $this->haveEnoughBalance($request);
        // (x) verificar se o payee existe
        $this->thePayeeExists($request);
        // (x) loJista não pode fazer transferência
        $this->isShopkeeper($request);
        // () validar transferência em um autorizador externo
    }

    private function requestValidate(Request $request): ?bool
    {
        $validator = Validator::make($request->all(), [
            'payer_document' => 'required',
            'payee_document' => 'required',
            'value' => 'required:numeric|between:0,99.99',
        ],[
            'payer_document.required' => 'O documento de quem transfere deve ser preenchido.',
            'payee_document.required' => 'O documento de quem recebe deve ser preenchido.',
            'value.required' => 'O valor deve ser preenchido.',
        ]);

        if ($validator->fails()) {
            throw new \DomainException ($validator->getMessageBag()->first(), 422);
        }
        return true;
    }

    private function isGreaterThanZero(Request $request): ?bool
    {
        if ($request->input('value') <= 0) {
            throw new \DomainException ('O valor tem que ser maior que zero.', 422);
        }
        return true;
    }

    private function thePayerExists(Request $request): ?bool
    {
        if (!$this->users->findByAttribute("document", $request->input('payer_document'))) {
            throw new \DomainException ('O usuário que está transferindo não existe.', 422);
        }
        return true;
    }

    private function thePayeeExists(Request $request): ?bool
    {
        if (!$this->users->findByAttribute("document", $request->input('payee_document'))) {
            throw new \DomainException ('O usuário que receberá não existe.', 422);
        }
        return true;
    }

    private function isShopkeeper(Request $request): ?bool
    {
        $user = $this->users->findByAttribute("document", $request->input('payer_document'));
        if ($user->users_type_id == self::SHOPKEEPER_TYPE_ID) {
            throw new \DomainException ('Lojista não pode fazer transferência.', 422);
        }
        return true;
    }

    private function haveEnoughBalance(Request $request): ?bool
    {
        $payer = $this->users->findByAttribute("document", $request->input('payer_document'));
        $wallet = $this->wallets->findByAttribute("users_id", $payer->id);
        
        if ($wallet->balance < $request->input('value')) {
            throw new \DomainException ('Saldo insuficiente para realizar a transferência.', 422);
        }
        return true;
    }
}