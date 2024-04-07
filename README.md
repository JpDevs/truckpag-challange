# Backend Challenge 20230105

## Introdução

Nesse desafio foi feito o desenvolvimento de uma REST API para utilizar os dados do projeto Open Food Facts, que é um banco de dados aberto com informação nutricional de diversos produtos alimentícios.

O projeto tem como objetivo dar suporte a equipe de nutricionistas da empresa Fitness Foods LC para que eles possam revisar de maneira rápida a informação nutricional dos alimentos que os usuários publicam pela aplicação móvel.

## O projeto
 
Para o desenvolvimento do projeto foi utilizado o framework laravel e o banco de dados MySql.

## Integração

Siga as instruções abaixo para integrar a aplicação em seu ambiente local:

1. **Instalação:**
    - Clone o repositório: `git clone https://github.com/JpDevs/truckpag-challange`
    - Acesse o diretório: `cd truckpag-challange`
    - Instale as dependências: `composer install`
    - Crie um banco de dados localmente em sua máquina

2. **Configuração:**
    - Copie o arquivo .env.example para .env e edite o mesmo, inserindo os dados de conexão da sua database
    ````
   cp .env.example .env
   nano .env
   ````
    ````dotenv
    DB_CONNECTION=mysql
    DB_HOST=seu_host
    DB_PORT=3306
    DB_DATABASE=nomedobanco
    DB_USERNAME=root
    DB_PASSWORD=suasenha
   ````

3. **Rode as migrations:**
    - Após criar e se conectar ao banco de dados, rode as migrations para que as tabelas possam ser
      criadas: `php artisan migrate:fresh`

4. **Configuração do CRON**
 - Entre no diretório /etc em seguida edite o arquivo crontab

  ````
  cd /etc
  sudo nano crontab
  ````
  - Insira os dados do cron. Lembre-se que ele está definido para ser executado diariamente as 00:00. Caso queira alterar vá até app/console/Kernel.php.
  ```
  0  3    * * *   root    cd projeto && php artisan products:update >> /dev/null 2>&1
  ```
5. **Startando a aplicação**
- Após realizar as devidas configurações, rode o comando `php artisan serve` e sua aplicação estará disponível em http://localhost:8000.

## Testes
Para executar os testes, rode `php artisan test`


### ROTAS

Na REST API teremos um CRUD com os seguintes endpoints:

 - `GET /api/`: Detalhes da API, se conexão leitura e escritura com a base de dados está OK, horário da última vez que o CRON foi executado, tempo online e uso de memória.
 - `PUT /api/products/:code`: Será responsável por receber atualizações do Projeto Web
 - `DELETE /api/products/:code`: Mudar o status do produto para `trash`
 - `GET /api/products/:code`: Obter a informação somente de um produto da base de dados
 - `GET /api/products`: Listar todos os produtos da base de dados, adicionar sistema de paginação para não sobrecarregar o `REQUEST`.



