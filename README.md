### Run project
To set up project, run: `make start`.

You can then access it locally: `http://localhost:8080/`



To build project run: docker compose -d --build

For simplicity the database in `/app/.env` file is set to: 
```
DATABASE_URL="mysql://root:secret@database:3306/bank_account_api?serverVersion=8.0"
```

### Access to containers


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