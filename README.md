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
 - `PUT /api/products/:code`:  responsável por receber atualizações do Projeto Web.

**Parametros:**

````json
[{
   "code": 20221126,
   "status": "published",
   "imported_t": "2020-02-07T16:00:00Z",
   "url": "https://world.openfoodfacts.org/product/20221126",
   "creator": "securita",
   "created_t": 1415302075,
   "last_modified_t": 1572265837,
   "product_name": "Madalenas quadradas",
   "quantity": "380 g (6 x 2 u.)",
   "brands": "La Cestera",
   "categories": "Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas",
   "labels": "Contem gluten, Contém derivados de ovos, Contém ovos",
   "cities": "",
   "purchase_places": "Braga,Portugal",
   "stores": "Lidl",
   "ingredients_text": "farinha de trigo, açúcar, óleo vegetal de girassol, clara de ovo, ovo, humidificante (sorbitol), levedantes químicos (difosfato dissódico, hidrogenocarbonato de sódio), xarope de glucose-frutose, sal, aroma",
   "traces": "Frutos de casca rija,Leite,Soja,Sementes de sésamo,Produtos à base de sementes de sésamo",
   "serving_size": "madalena 31.7 g",
   "serving_quantity": 31.7,
   "nutriscore_score": 17,
   "nutriscore_grade": "d",
   "main_category": "en:madeleines",
   "image_url": "https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg"
}]

````
 - `DELETE /api/products/:code`: Muda o status do produto para `trash`
- `POST /api/products`: Permite a criação de um produto manualmente ou a importação de um arquivo .json unitário

**Parametros:**
Request do tipo multipart/form contendo um arquivo .JSON com o name file. Ou inserção manual dos dados no formato JSON:
````json
[{
   "code": 20221126,
   "status": "published",
   "imported_t": "2020-02-07T16:00:00Z",
   "url": "https://world.openfoodfacts.org/product/20221126",
   "creator": "securita",
   "created_t": 1415302075,
   "last_modified_t": 1572265837,
   "product_name": "Madalenas quadradas",
   "quantity": "380 g (6 x 2 u.)",
   "brands": "La Cestera",
   "categories": "Lanches comida, Lanches doces, Biscoitos e Bolos, Bolos, Madalenas",
   "labels": "Contem gluten, Contém derivados de ovos, Contém ovos",
   "cities": "",
   "purchase_places": "Braga,Portugal",
   "stores": "Lidl",
   "ingredients_text": "farinha de trigo, açúcar, óleo vegetal de girassol, clara de ovo, ovo, humidificante (sorbitol), levedantes químicos (difosfato dissódico, hidrogenocarbonato de sódio), xarope de glucose-frutose, sal, aroma",
   "traces": "Frutos de casca rija,Leite,Soja,Sementes de sésamo,Produtos à base de sementes de sésamo",
   "serving_size": "madalena 31.7 g",
   "serving_quantity": 31.7,
   "nutriscore_score": 17,
   "nutriscore_grade": "d",
   "main_category": "en:madeleines",
   "image_url": "https://static.openfoodfacts.org/images/products/20221126/front_pt.5.400.jpg"
}]

````
 - `GET /api/products/:code`: Obtem as informação somente de um produto da base de dados
 - `GET /api/products`: Lista todos os produtos da base de dados.



