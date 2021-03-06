<p align="center">
   <img src="./docs/clients.png" width="200"/>
</p>

# CUCO Health | Client API





[![Author](https://img.shields.io/badge/Author-Alisson%20Gabriel-black)](https://github.com/LauraBeatris)


> Cadastre seus clientes e os gerencie através de uma interface simples e amigável.

<br />


# Funcionalidades

* Crie seus clientes, registrando seu E-mail, CPF e Telefone
* Explore os dados registrados por meio de filtros e ordenações
* Obtenha seus clientes no formato mais famoso para integrações: JSON

# Tecnologias e técnicas

* PHP 8
* Symfony 5
* Postgres 13
* SQLite 3
* Aplicado uso de cache para agilizar consultas
* PHPUnit para realizar testes de integração e unitários ( todos passando :) )
* Na infraestrutura, para banco de dados foi utilizado o ORM doctrine para operar os repositórios
* Também foi utilizado Postman para testes das rotas
* Para o repositório git, segui a [convenção do framework Angular](https://www.conventionalcommits.org/en/v1.0.0-beta.4/)
  
# Requerimentos
 - Instale o [PHP 8](https://www.php.net/downloads)
 - Instale o [Postgres 13](https://www.postgresql.org/download/)
 - Instale o [Composer 2](https://getcomposer.org/download/)
 - Instale o [Git](https://git-scm.com/downloads/)

# Instalação

Clone o repositório

``` git clone git@github.com:dalissongabriel/test_php_backend_cuco.git ```

Dentro da pasta do projeto, crie suas variáveis de ambiente baseado no exemplo:  ```.env.example```

```cp .env.example .env```

``` DATABASE_URL="postgresql://SEU_USUARIO:SUA_SENHA@HOST_DO_BANCO:PORTA_DO_BANCO/NOME_DO_BANCO?serverVersion=13&charset=utf8" ```

Baixe as dependências com o composer:

``` composer install ```

Crie o banco de dados

```php bin/console doctrine:database:create```

```php bin/console doctrine:migrations:migrate```

Popule o banco com as seeds

``` php bin/console doctrine:fixtures:load ```

Suba o servidor para rodar a aplicação: 

```php -S localhost:8081 -t public/```


# Testes

Execute o comando para criar a base de dados teste em SQLite 3:

``` php bin/console -e test doctrine:schema:create ```
 
Execute o comando para popular as seeds na base de testes:

```php bin/console doctrine:fixtures:load --env=test```

Em seguida, rode testes feitos em PHPUnit

``` php bin/phpunit ```

Testes passando!!!
``` OK (24 tests, 47 assertions) ```

# Histórias de usuário

- [X] Usuário deve poder criar um cliente informando: nome, e-mail, cpf. Opcionalmente, pode-se informar também o telefone.

- [X] Usuário deve poder deletar um cliente

- [X] Usuário deve poder editar um cliente

- [X] Usuário deve poder buscar um cliente pelo nome e/ou CPF

- [X] Todos os retornos da API devem ser em JSON, incluindo os erros tratados pela aplicação

*BONUS*

- [X] Cliente deverá se autenticar via token JWT para ter acesso as rotas

- [X] Cliente poderá filtrar os registros por email

- [X] Cliente deverá receber respostas com informações extras sobre os recursos consultados (HATEOAS)

- [X] Cliente poderá escolher a ordenação de sua busca

- [X] Cliente deverá receber respostas páginadas, e poderá escolher qual página gostaria de receber, assim como a quantidade de registros por página

- [X] Os campos CPF,Telefone e E-mail devem ser válidados para garantir a integridade do domínio


## Documentação

Esta é um API RESTful com um contexto bem enxuto, todavia, para a construção da mesma, utilizei boas práticas de programação e teste unitários para entregar um ferramenta de fácil manutenção e evolução. Para facilitar ainda mais a utilização da mesma, disponibilizo abaixo uma relação completa de uso das rotas, e também uma [coleção do postmam](./docs/Cuco%20Clientes%20Collection.postman_collection.json). Faça bom uso :)

---

Documentação completa para consumo da API:

1. Obter Token de acesso (Rota não protegida)

    Esta rota autentica um usuário e retona um token JWT válido para utilizar nas demais requisições. Somente um usuário foi adicionado por meio de seeds (Fixtures)
    - Request
        ```bash
        POST http://localhost:8081/login 
        ```
    - Exemplos de um bom Body
        ```json
        {
            "username": "my-valid-user-login",
            "password": "my-valid=password-login"
        }
        ```
    - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `200 (OK)` | `Login processado com sucesso ` 
        `400 (Requisição inválida)` | `A requisição realizada contém problemas de má formação`
        `401 (Não autorizado)` | `Usuário ou senha incorretos`
    - Response
      ```json
        {
            "success": true,
            "data": {
                "access_token": "aqui estará seu token para acesso"
            }
        }
        ```
    
        ```json
        {
            "success": false,
            "data": {
                "message": "Usuário ou senha inválidos"
        }
        ```

2. Adicionar clientes (Rota protegida)

    Esta rota insere um novo registro na tabela client.
    - Request
        ```bash
        POST http://localhost:8081/clientes 
    - Header
        ```bash
        AUTHORIZATION {ACCESS_TOKEN 
        ```
    - Exemplos de um bom Body
        ```json
        {
            "name": "Otavio Mota",
            "email": "otaviomota2020@gmail.com",
            "cpf": "123.456.789.9"
        }
        ```
         ```json
        {
            "name": "Otavio Mota",
            "email": "otaviomota2020@gmail.com",
            "cpf": "123.456.789.9",
            "phone": "(40) 404044040"
        }
        ```
    - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `201 (Criado)` | `Cliente cadastrado ` 
        `400 (Requisição inválida)` | `A requisição realizada contém problemas de má formação`
        `401 (Não autorizado)` | `Na requisição, não foi informado o cabeçalho de autorização`
        `412 (Pré-condição falhou)` | `Os valores informados não são válidos.`

3. Buscar cliente (Rota protegida)

    Esta rota busca um registro na tabela client pelo ID.
    - Request
        ```bash
        GET http://localhost:8081/clientes/{id} 
        ```
    - Header
        ```bash
        AUTHORIZATION {ACCESS_TOKEN}
        ```
    - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `200 (Ok)` | `Requisição processada com sucesso ` 
        `400 (Requisição inválida)` | `A requisição realizada contém problemas de má formação`
        `401 (Não autorizado)` | `Na requisição, não foi informado o cabeçalho de autorização`
   - Exemplos
     - Request
         ```json
            {
               "name":"Teste 3",
               "cpf":"278.128.110-77",
               "email":"email@email.com"
            }
         ```
      - Header
          ```bash
          AUTHORIZATION {ACCESS_TOKEN 
          ```
     - Response
        ```json
         {
            "success": true,
            "data": {
                        "id": 20,
                        "name": "Teste 3",
                        "cpf": "278.128.110-77",
                        "email": "email@email.com"
                     }
         }
        ```

4. Buscar todos os clientes (Rota protegida)

    Esta rota busca registros na tabela client, podendo realizar buscas personalizadas.
    - Request
        ```bash
        GET http://localhost:8081/clientes
        ```
    - Header
        ```bash
        AUTHORIZATION {ACCESS_TOKEN}
        ```
   - Filtros, ordenação e paginação:

        Opções | Função
        ------------ | -------------
        `/clientes?name=Teste 3` | `filtra por nome ` 
        `/clientes?cpf="123.456.789-9` | `filtra por cpf`
        `/clientes?name=Teste 3cpf=123.456.789-9` | `filtra por nome e cpf`
        `/clientes?sort[name]=ASC` | `ordena em ascendente por nome `
        `/clientes?sort[name]=DESC` | `ordena em descendente por nome `
        `/clientes?sort[name]=DESC&sort[id]=ASC` | `ordena em descendente por nome e depois por ID em ascendente `
        `/clientes?page=2` | `retorna a 2ª página de dados `
        `/clientes?page=2&itemsPerPage=20` | `retorna a 2ª página de dados, exibindo 20 items `

   - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `200 (Ok)` | `Requisição processada com sucesso ` 
        `400 (Requisição inválida)` | `A requisição realizada contém problemas de má formação`
        `401 (Não autorizado)` | `Na requisição, não foi informado o cabeçalho de autorização`
   - Response
      ```json
      {
         "success": true,
         "currentPage": 1,
         "itemsPerPage": 5,
         "data": []
      }
      ```
      ```json
      {
         "success": true,
         "currentPage": 1,
         "itemsPerPage": 5,
         "data": [
            {
                  "id": 17,
                  "name": "Client Test 1",
                  "cpf": "446.069.350-06",
                  "email": "fructuosa2658@uorak.com",
                  "phone": null
            },
            {
                  "id": 18,
                  "name": "Client Test 2",
                  "cpf": "949.487.580-00",
                  "email": "abdennacer2076@uorak.com",
                  "phone": null
            },
            {
                  "id": 19,
                  "name": "Teste 3",
                  "cpf": "278.128.110-77",
                  "email": "email@email.com",
                  "phone": null
            },
            {
                  "id": 20,
                  "name": "Teste 3",
                  "cpf": "278.128.110-77",
                  "email": "email@email.com",
                  "phone": null
            }
         ]
      }
      ```

5. Atualizar dados de um cliente (Rota protegida)

    Esta rota atualiza um registro na tabela client pelo ID.
    - Request
        ```bash
        PUT http://localhost:8081/clientes/{id}
        ```
    - Header
        ```bash
        AUTHORIZATION {ACCESS_TOKEN}
        ```
    - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `200 (Atualizado)` | `Cliente atualizado com sucesso ` 
        `400 (Requisição inválida)` | `A requisição realizada contém problemas de má formação`
        `401 (Não autorizado)` | `Na requisição, não foi informado o cabeçalho de autorização`
        `404 (Não encontrado)` | `Recurso com o {id} não encontrado na base`
        `412 (Pré-condição falhou)` | `Os valores informados não são válidos.`

6. Remover um cliente (Rota protegida)

    Esta rota remove um registro na tabela client pelo ID.
    - Request
        ```bash
        DELETE http://localhost:8081/clientes/{id}
        ```
    - Header
        ```bash
        AUTHORIZATION {ACCESS_TOKEN} 
        ```
    - Retornos possíveis

        Código | Resposta
        ------------ | -------------
        `204 (Nenhum conteúdo)` | `Cliente apagado ` 
        `401 (Não autorizado)` | `Na requisição, não foi informado o cabeçalho de autorização`
        `404 (Não encontrado)` | `Recurso com o {id} não encontrado na base`


---
Projeto concluído em **2021**

Feito com carinho por  [Alisson Gabriel](https://github.com/dalissongabriel)
