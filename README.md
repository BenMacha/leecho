LES ECHOS Technical Test
========================

The "LES ECHOS Technical Test" is a refactoring exercise for back-end developers.


Installation
------------

1. run docker

```shell
docker compose up -d
docker compose exec php sh
```
2. install composer

```shell
composer install
php bin/console assets:install --symlink public
```

3. install database
```shell
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

4. start project
```shell
symfony server:start -d
```

5. run webpack
```shell
yarn
yarn dev
yarn watch
```