### Setup
Install Docker and php locally first, then copy `symfony/.env.dist` to `symfony/.env` and edit to your needs.

Then to setup project's containers simply run:

```bash
composer setup
```

Your API is up and ready on [http://localhost](http://localhost) and [https://localhost](https://localhost) with a self signed certificate.

### Usage

Once built, you can start or stop project's containers like this:

```bash
composer up
```

```bash
composer stop
```

Destroys containers (but keep volumes)

```bash
composer down
```

Migrate database

```bash
composer migrate
```

Load fixtures

```bash
composer fixtures
```

Connect to postgresql database

```bash
composer db
```

Show logs

```bash
composer logs
```

Fix code lint

```bash
composer lint:fix
```

See [composer configuration file](./composer.json) for more available scripts.

