<?php

class TransactionsControllerTest extends TestCase
{
    /**
     * O valor deve sermaior que zero
     *
     * @return void
     */
    public function testValueGreateTthanZero(): void
    {
        $request = $this->mockTransferRequest();
        $request['value'] = 0;
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O valor tem que ser maior que zero.",
        ]);
    }

    /**
     * O valor deve ser preenchido.
     *
     * @return void
     */
    public function testValueIsNotNull(): void
    {
        $request = $this->mockTransferRequest();
        $request['value'] = null;
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O valor deve ser preenchido.",
        ]);
    }

    /**
     * O documento de quem transfere deve ser preenchido.
     *
     * @return void
     */
    public function testPayerDocumentIsNotNull(): void
    {
        $request = $this->mockTransferRequest();
        $request['payer_document'] = null;
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O documento de quem transfere deve ser preenchido.",
        ]);
    }

    /**
     * O documento de quem transfere deve ser preenchido.
     *
     * @return void
     */
    public function testPayeeDocumentIsNotNull(): void
    {
        $request = $this->mockTransferRequest();
        $request['payee_document'] = null;
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O documento de quem recebe deve ser preenchido.",
        ]);
    }

    /**
     * verificar se o payer existe.
     *
     * @return void
     */
    public function testPayerExists(): void
    {
        $request = $this->mockTransferRequest();
        $request['payer_document'] = '00000000003';
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O usu??rio que est?? transferindo n??o existe.",
        ]);
    }

    /**
     * verificar se o payee existe.
     *
     * @return void
     */
    public function testPayeeExists(): void
    {
        $request = $this->mockTransferRequest();
        $request['payee_document'] = '00000000003';
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O usu??rio que receber?? n??o existe.",
        ]);
    }

    /**
     * verificar se o payer existe.
     *
     * @return void
     */
    public function testHaveEnoughBalance(): void
    {
        $request = $this->mockTransferRequest();
        $request['value'] = 10000;
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "Saldo insuficiente para realizar a transfer??ncia.",
        ]);
    }

    /**
     * loJista n??o pode fazer transfer??ncia
     *
     * @return void
     */
    public function testIsShopkeeper(): void
    {
        $request = $this->mockTransferRequest();
        $request['payer_document'] = '00000000000001';
        $this->json('POST', '/v1/transactions/transfer', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "Lojista n??o pode fazer transfer??ncia.",
        ]);
    }

    /**
     * hash deve ser informado
     *
     * @return void
     */
    public function testHashIsNotNull(): void
    {
        $request['hash'] = null;
        $this->json('POST', '/v1/transactions/chargeback', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "O hash da transa????o deve ser informado.",
        ]);
    }

    /**
     * transa????o n??o existe
     *
     * @return void
     */
    public function testTransactionDoesNotExist(): void
    {
        $request['hash'] = 'gremio';
        $this->json('POST', '/v1/transactions/chargeback', $request)->seeJsonEquals([
            'code' => 422,
            'data' => [],
            'message' => "Transa????o n??o encontrada.",
        ]);
    }

    /**
     * fluxo completo de um transfer??ncia e o estorno da mesma
     *
     * @return void
     */
    public function testTransferAndChargeback(): void
    {
        $responseTransfer = $this->call('POST', '/v1/transactions/transfer', $this->mockTransferRequest());
        $this->assertEquals(200, $responseTransfer->status());

        $responseTransfer = json_decode($responseTransfer->content());
        $this->assertEquals("Transfer??ncia realizada com sucesso", $responseTransfer->message);

        sleep(17);

        $responseChargeback = $this->call('POST', '/v1/transactions/chargeback', ['hash' => $responseTransfer->data->transaction->hash]);
        $this->assertEquals(200, $responseChargeback->status());

        $responseChargeback = json_decode($responseChargeback->content());
        $this->assertEquals("Estorno realizado com sucesso", $responseChargeback->message);
    }

    private function mockTransferRequest(): array
    {
        return [
            "value" => 10,
            "payer_document" => '00000000001',
            "payee_document" => '00000000000001',
        ];
    }
}
