<?php

use Illuminate\Database\Seeder;

class estrutura_apresentacao extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(!DB::table('users_type')->get()->all()) {
            $timestamp = date('Y/m/d H:i:s');

            $userTypePf = DB::table('users_type')->insertGetId([
                'type' => 'Pessoa Física (comuns)',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
    
            $userTypePj = DB::table('users_type')->insertGetId([
                'type' => 'Pessoa Jurídica (lojistas)',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
    
            $user1 = DB::table('users')->insertGetId([
                'full_name' => 'Éverton Hilario',
                'document' => '00000000001',
                'email' => 'everton@gmail.com',
                'password' => md5(random_bytes(1)),
                'users_type_id' => $userTypePf,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
    
            $user2  = DB::table('users')->insertGetId([
                'full_name' => 'Luís de Almeida',
                'document' => '00000000002',
                'email' => 'luis@gmail.com',
                'password' => md5(random_bytes(1)),
                'users_type_id' => $userTypePf,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
    
            $user3 = DB::table('users')->insertGetId([
                'full_name' => 'Dinheiro ou Cartão S/A',
                'document' => '00000000000001',
                'email' => 'contato@gmail.com',
                'password' => md5(random_bytes(1)),
                'users_type_id' => $userTypePj,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
                
            DB::table('transactions_type')->insert([
                'type' => 'Transferência',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('transactions_type')->insert([
                'type' => 'Estorno',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('transactions_status')->insert([
                'status' => 'Solicitado',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('transactions_status')->insert([
                'status' => 'Processando',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('transactions_status')->insert([
                'status' => 'Processado',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('transactions_status')->insert([
                'status' => 'Estornado',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('transactions_status')->insert([
                'status' => 'Erro ao processar',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('operations_type')->insert([
                'type' => 'Debito',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('operations_type')->insert([
                'type' => 'Saque',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('wallets')->insert([
                'balance' => 700.00,
                'users_id' => $user1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('wallets')->insert([
                'balance' => 100.00,
                'users_id' => $user2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);

            DB::table('wallets')->insert([
                'balance' => 50.00,
                'users_id' => $user3,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
        }
    }
}
