### Setup
uruchomienie projektu

```bash
docker compose --env-file symfony/.env up -d --build
```
Migracja

```bash
docker exec -it app  bin/console doctrine:migration:migrate 
```
załadowanie danych testowych 
```bash
docker exec -it app  bin/console doctrine:fixtures:load
```

sprawdzenie api
```bash
http://localhost/api/v1/reports
```

uruchomienie frontendu
```bash
cd frontend
npm install
ng serve
```