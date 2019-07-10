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
email: admin@360.ru
password: admin
```

file_put_contents(/var/www/storage/framework/cache/...): failed to open stream: No such file or directory

```
make set-permissions
```