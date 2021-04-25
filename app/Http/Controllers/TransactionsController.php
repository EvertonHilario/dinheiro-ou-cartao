<?php

namespace App\Http\Controllers;

use App\Domain\Contracts\TransferServiceInterface;
use Illuminate\Http\{Request, JsonResponse};

class TransactionsController extends Controller
{
    private $transfer;

    public function __construct(TransferServiceInterface $transfer)
    {
        $this->transfer = $transfer;
    }

    public function transfer(Request $request): JsonResponse
    {
        try {
            $response = $this->transfer->push($request);
            return $this->responseAdapter(200, "Transferência realizada com sucesso", $response);
        } catch (\DomainException $e) {
            return $this->responseAdapter($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return $this->responseAdapter(500, "Erro ao executar a transação" . $e->getMessage());
        }
    }

    public function reversal(Request $request): JsonResponse
    {
        try {
            return $this->responseAdapter(200, "Estorno realizado com sucesso", $request);
        } catch (\DomainException $e) {
            return $this->responseAdapter($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return $this->responseAdapter(500, "Erro ao executar a transação");
        }
    }
}