# 360 Review

### Project setup

Run:

```
make bootstrap
```

Service should be running at http://localhost:8080/

Migrations:

```
make apply-migrations
```

Seed:

```
make apply-seeds
```

Login credentials:

```
email: admin
password: admin
```

file_put_contents(/var/www/storage/framework/cache/...): failed to open stream: No such file or directory

```
make set-permissions
```

### Пособие по разработке
Иконки смотреть тут: https://materialdesignicons.com/
Берём нужную и делаем приставку `mdi-`. Например, `mdi-exit-run`.
