# dinheiro-ou-cartao
API para realização de transações financeiras assíncronas entre carteiras virtuais

## Requisitos

- Para ambos tipos de usuário, precisamos do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema. Sendo assim, o sistema deve permitir apenas um cadastro com o mesmo CPF ou endereço de e-mail.
- Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários.
- Lojistas só recebem transferências, não enviam dinheiro para ninguém.
- Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo. Mock para simular (https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6).
- A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia.
- No recebimento de pagamento, o usuário ou lojista precisa receber notificação enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável. Mock para simular o envio (https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04).
- Este serviço deve ser RESTFul.

## Fluxo da transação

Transação entre um usuário comum e um lojista:
![](https://meu-driver.s3-sa-east-1.amazonaws.com/IMG_20210425_133851.jpg)

Transação entre dois usuários comuns:
![](https://meu-driver.s3-sa-east-1.amazonaws.com/IMG_20210425_134057.jpg)

## Fluxo das notificações

![](https://meu-driver.s3-sa-east-1.amazonaws.com/IMG_20210425_135058.jpg)

## Arquitetura Distribuída

![](https://meu-driver.s3-sa-east-1.amazonaws.com/IMG_20210425_172431.jpg)

## Documentações
- [Instalação](https://github.com/EvertonHilario/dinheiro-ou-cartao/wiki/Instalação)
- [Endpoints](https://documenter.getpostman.com/view/15321494/TzJydGPW)
- [Análise](https://docs.google.com/document/d/1eMdW1lqg6SQDiWjGWT8aOSGfRbnlKuw_7YBYYTIlcFg/edit?usp=sharing)

## Links
- [PHP](https://www.php.net/)
- [Lumen PHP Framework](https://lumen.laravel.com/)
- [MySql](https://dev.mysql.com/doc/)
- [Docker](https://www.docker.com/)
- [Rabbitmq](https://www.rabbitmq.com/)
- [guzzlephp](https://docs.guzzlephp.org/en/stable/)
- [Uma abordagem de Domain Driven Design (DDD) para a estrutura Lumen](https://github.com/EvertonHilario/api-lumen)