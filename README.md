## About project
This is an API simulating functionalities of simple bank account.

### Run project
To set up project, run: 
```
make start
```
After project is built succesfully and containers are running, use: 
```
make migrate
``` 
to run migrations.

If there are any issues with `make migrate` command, you can enter PHP container and run migrations from container CLI using:
```
docker-compose exec php /bin/bash
and run `symfony console doctrine:migrations:migrate
```

Project can then be accessed locally: `http://localhost:8080/`, where all endpoints are listed.


### Access to containers
To access PHP container CLI run:
```
docker-compose exec php /bin/bash
```
To access MySql container CLI run:
```
docker-compose exec database /bin/bash
```
You can than connect into database with root acccess using credentials:
```
ROOT_USER = root
MYSQL_ROOT_PASSWORD = secret
```
with following command:
```
mysql -u root -p bank_account_api
```



For simplicity the database in `/app/.env` file is set to: 
```
DATABASE_URL="mysql://root:secret@database:3306/bank_account_api?serverVersion=8.0"
```

### Available endpoints:

1. ```POST /create_wallet``` - Create new wallet 
2. ```GET /check_balance/{id}``` - Check balance of the wallet with `{id}`
3. ```POST /deposit/{id}``` - Deposit funds to wallet with given `{id}`. 
4. ```POST /withdraw/{id}``` - Withdraw funds from wallet with given `{id}`

To deposit or withdraw funds from wallet using `/deposit/{id}` and `/withdraw/{id}` endpoints, specify the amount in request body, using JSON: 
```
{
    "amount": 10
}
``` 
All Routes are available in [ApiController.php](app/src/Controller/ApiController.php)

### CLI Command
To generate a log of transactions for a wallet with {id}, use this command from within php container:
```
symfony console api:generate-wallet-transactions-log {id}
```
or use alias
```
symfony console api:gwtl {id}
```


Command will generate and save CSV file in `transaction_files`.

Command is implemented in [GenerateWalletTransactionsLog.php](app/src/Command/GenerateWalletTransactionsLog.php) and uses additional [CSVFileGenerator.php](app/src/Service/CSVFileGenerator.php) service.
