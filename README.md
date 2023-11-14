To set up project, run: `make start`.

You can than access it locally: `http://localhost:8080/`



To build project run: docker compose -d --build

For simplicity the database in `/app/.env` file is set to: 
```
DATABASE_URL="mysql://root:secret@database:3306/bank_account_api?serverVersion=8.0"
```