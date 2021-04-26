<?php

namespace App\Http\Controllers;

use App\Domain\Contracts\{TransferServiceInterface, ChargebackServiceInterface};
use Illuminate\Http\{Request, JsonResponse};

class TransactionsController extends Controller
{
    private $transfer;
    private $chargeback;

    public function __construct(TransferServiceInterface $transfer, ChargebackServiceInterface $chargeback)
    {
        $this->transfer = $transfer;
        $this->chargeback = $chargeback;
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

    public function chargeback(Request $request): JsonResponse
    {
        try {
            $response = $this->chargeback->push($request);
            return $this->responseAdapter(200, "Estorno realizado com sucesso", $response);
        } catch (\DomainException $e) {
            return $this->responseAdapter($e->getCode(), $e->getMessage());
        } catch (\Exception $e) {
            return $this->responseAdapter(500, "Erro ao executar o estorno");
        }
    }
}