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
![](https://lh5.googleusercontent.com/sD2vGzz9wQ45UoSygp1wxNaUqBO1YYJlZtp9tWfFML220w3Zy_x6Q_qVbJQ3HnOyhy1V0xjfO3duNT-KA6hP=w1848-h979)

Transação entre dois usuários comuns:
![](https://camo.githubusercontent.com/bbbc6956f4c83378c5ec5898cbf186b5cd1c6eb47c4f5f2704f02a3ad606fe92/68747470733a2f2f6c68332e676f6f676c6575736572636f6e74656e742e636f6d2f5044694c4f5038597a33777a7751316b3865742d72337059304563393476444d474241333957414e5f5936786336654e4756454448554370665363386873555a36594e416b4b687a6b4a706932625168354e6f583d77313834382d68393739)

## Fluxo das notificações
![](https://camo.githubusercontent.com/de8d78135434c41ccd885dacd67add7ded69930570c0b7776bb074f020f2a237/68747470733a2f2f6c68362e676f6f676c6575736572636f6e74656e742e636f6d2f50634831556c454a4f6e45655165776e683250587161747034574d6d5478704b325061447a336a74666c37525f52783637324e49516e7245547157346266423268665557624f5559706d6f6239795443415f33733d77313834382d68393739)

## Arquitetura Distribuída
![](https://camo.githubusercontent.com/65e35efeeaa6bd42f086bf5209e003edc11e8a46882932d023adcda847b7063f/68747470733a2f2f6c68352e676f6f676c6575736572636f6e74656e742e636f6d2f6d726b64775437306f765f434a775758655333537764797373482d706447545a6a41686f6c41444e42706d684859776b6b4c7a5664534f486a314d70724a75746e43302d766e526c34695539326c4442414333753d77313834382d68393739)

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