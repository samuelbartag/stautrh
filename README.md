# BACKEND PHP - PROJETO API REST

### Nome: Samuel Santon Bartag
### E-mail: samuel@samuelbartag.com.br

## Observações

Todos os endpoints referentes aos usuários são acessíveis apenas por token.

Para gerar o token é necessário fazer o login por qualquer usuário já criado.

Não foi permitida a utilização de qualquer framework ou biblioteca. Então todo o código foi gerado manualmente.

## Banco de Dados

O banco de dados utilizado é o SQLite, e está localizado na pasta ___database__ sob o nome __database.sqlite__ localizada na raiz da pasta.

O script para criação está localizado na mesma pasta sob o nome de [__database.sql__](_database/database.sql).

## Instalação

```sh
composer install
```

O composer install apenas necessário para o autoload das classes.

## Execução

```sh
php -S localhost:8080 -t ./app/
```

Após isso executar as consultas via CURL

## Endpoints

#### Login

```sh
curl --location --request POST 'http://localhost:8080/login' \
--data-raw '{
	"email": "samuel@email.com.br",
	"senha": "samuel"
}'
```

Retornará os dados do usuário, incluindo o token de acesso.

#### Listar todos

```sh
curl --location --request GET 'http://localhost:8080/user?limit=100' \
--header 'Authorization: Bearer aaaaaaaaaaaa'
```

Os campos limit e offset podem ser definidos como parametros na url para a paginação dos dados.

#### Consultar usuário

```sh
curl --location --request GET 'http://localhost:8080/user/7' \
--header 'Authorization: Bearer aaaaaaaaaaaa'
```

#### Criar novo usuário

```sh
curl --location --request POST 'http://localhost:8080/user' \
--header 'Authorization: Bearer aaaaaaaaaaaa' \
--data-raw '{
	"name": "Samuel",
	"email": "samuel@email.com.br",
	"password": "samuel"
}'
```

#### Editar dados do usuário

É permitido apenas para o usuário logado.

```sh
curl --location --request PUT 'http://localhost:8080/user/16' \
--header 'Authorization: Bearer aaaaaaaaaaaa' \
--data-raw '{
	"name": "Samuel",
	"email": "samuel@email.com.br",
	"password": "samuel"
}'
```

#### Criar nova entrada para o volume ingerido

```sh
curl --location --request POST 'http://localhost:8080/user/15/drink' \
--header 'Authorization: Bearer aaaaaaaaaaaa' \
--data-raw '{
	"drink_count": 743
}'
```

#### Excluir usuário

É permitido apenas para o usuário logado.

```sh
curl --location --request DELETE 'http://localhost:8080/user/11' \
--header 'Authorization: Bearer aaaaaaaaaaaa' \
--header 'Content-Type: text/plain' \
--data-binary '@'
```

