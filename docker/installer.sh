#!/bin/bash

##script para iniciar o serviço

################################################################
### obs. este script tem um bug mas ele funciona, no tranco! ###
### O problema acontece pq não da tempo de iniciar o mysql   ###
### É só rodar duas vezes --> iremos arrumar isso            ###  
################################################################

echo "INSTALANDO APP"
echo ""
echo "                                                    0%"

echo ""
echo "1 - Subindo os containers"
echo ""
docker-compose up -d
echo ""
echo "=======>                                            15%"

echo ""
echo "2 - Instalando dependências via composer"
echo ""
docker exec dinheiro-ou-cartao composer install
echo ""
echo "==============>                                     30%"

echo ""
echo "3 - criando estrutura para salvar os dados"
docker exec dinheiro-ou-cartao php artisan migrate
echo ""
echo "========================>                           45%"

echo ""
echo "4 - Salvando dados base (Tipos de usuários, 3 usuários, tipo de transações, status de transações, tipo de operações e deposita 700.00 pila na carteira do Éverton Hilario)"
docker exec dinheiro-ou-cartao php artisan db:seed --class=estrutura_apresentacao
echo ""
echo "================================>                   60%"

echo ""
echo "5 - Rodando testes unitários"
echo ""
docker exec dinheiro-ou-cartao vendor/bin/phpunit tests/Unit/
echo ""
echo "======================================>             75%"

echo ""
echo "5 - Rodando testes de integração"
echo ""
docker exec dinheiro-ou-cartao vendor/bin/phpunit tests/Integration/
echo ""
echo "============================================>       90%"

echo ""
echo "6 - Instanciando a execução dos Jobs"
echo ""
echo "==================================================> 100%"
echo "Monitorando os jobs..."
docker exec dinheiro-ou-cartao php artisan queue:listen
