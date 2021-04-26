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
![](https://lh4.googleusercontent.com/_1h94epDXcPho1jze_1LbMjMXE6RiTt7M7wdqnYcZVvlZ2lAr6So6coB6UCkFm-jeisDZOLHCBPE55E5_lTf=w1848-h979)

Transação entre dois usuários comuns:
![](https://lh3.googleusercontent.com/PDiLOP8Yz3wzwQ1k8et-r3pY0Ec94vDMGBA39WAN_Y6xc6eNGVEDHUCpfSc8hsUZ6YNAkKhzkJpi2bQh5NoX=w1848-h979)

## Fluxo das notificações
![](https://lh6.googleusercontent.com/PcH1UlEJOnEeQewnh2PXqatp4WMmTxpK2PaDz3jtfl7R_Rx672NIQnrETqW4bfB2hfUWbOUYpmob9yTCA_3s=w1848-h979)

## Arquitetura Distribuída
![](https://lh5.googleusercontent.com/mrkdwT70ov_CJwWXeS3SwdyssH-pdGTZjAholADNBpmhHYwkkLzVdSOHj1MprJutnC0-vnRl4iU92lDBAC3u=w1848-h979)

## Documentações
[Instalação](https://github.com/EvertonHilario/dinheiro-ou-cartao/wiki/Instalação)
[Análise](https://docs.google.com/document/d/1eMdW1lqg6SQDiWjGWT8aOSGfRbnlKuw_7YBYYTIlcFg/edit?usp=sharing)

## Links
- [PHP](https://www.php.net/)
- [Lumen PHP Framework](https://lumen.laravel.com/)
- [MySql](https://dev.mysql.com/doc/)
- [Docker](https://www.docker.com/)
- [Rabbitmq](https://www.rabbitmq.com/)
- [guzzlephp](https://docs.guzzlephp.org/en/stable/)
- [Uma abordagem de Domain Driven Design (DDD) para a estrutura Lumen](https://github.com/EvertonHilario/api-lumen)