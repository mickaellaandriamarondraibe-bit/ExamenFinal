# DATABASE
### Mysql 

```
    public array $default = [
    'DSN'          => '',
    'hostname'     => 'localhost',
    'username'     => 'root',
    'password'     => '',
    'database'     => 'nom_de_la_base',
    'DBDriver'     => 'MySQLi',
    'DBPrefix'     => '',
    'pConnect'     => false,
    'DBDebug'      => true,
    'charset'      => 'utf8mb4',
    'DBCollat'     => 'utf8mb4_general_ci',
    'swapPre'      => '',
    'encrypt'      => false,
    'compress'     => false,
    'strictOn'     => false,
    'failover'     => [],
    'port'         => 3306,
    'numberNative' => false,
    'foundRows'    => false,
    'dateFormat'   => [
        'date'     => 'Y-m-d',
        'datetime' => 'Y-m-d H:i:s',
        'time'     => 'H:i:s',
    ],
];

```

### Postgres

```
    public array $default = [
    'DSN'          => '',
    'hostname'     => 'localhost',
    'username'     => 'postgres',
    'password'     => 'ton_mot_de_passe',
    'database'     => 'nom_de_la_base',
    'DBDriver'     => 'Postgre',
    'DBPrefix'     => '',
    'pConnect'     => false,
    'DBDebug'      => true,
    'charset'      => 'utf8',
    'DBCollat'     => '',
    'schema'       => 'public',
    'swapPre'      => '',
    'encrypt'      => false,
    'compress'     => false,
    'strictOn'     => false,
    'failover'     => [],
    'port'         => 5432,
    'numberNative' => false,
    'foundRows'    => false,
    'dateFormat'   => [
        'date'     => 'Y-m-d',
        'datetime' => 'Y-m-d H:i:s',
        'time'     => 'H:i:s',
    ],
];
```
### Sqlite3

```
    public array $default = [
    'DSN'          => '',
    'hostname'     => '',
    'username'     => '',
    'password'     => '',
    'database'     => WRITEPATH . 'database.sqlite',
    'DBDriver'     => 'SQLite3',
    'DBPrefix'     => '',
    'pConnect'     => false,
    'DBDebug'      => true,
    'charset'      => 'utf8',
    'DBCollat'     => '',
    'swapPre'      => '',
    'encrypt'      => false,
    'compress'     => false,
    'strictOn'     => false,
    'failover'     => [],
    'port'         => 0,
    'foreignKeys'  => true,
    'numberNative' => false,
    'foundRows'    => false,
    'dateFormat'   => [
        'date'     => 'Y-m-d',
        'datetime' => 'Y-m-d H:i:s',
        'time'     => 'H:i:s',
    ],
];

```
- Apres 
```
    touch writable/database.sqlite
```


# MIGRATION
### Migrations

```
    php spark make:migration CreateUsersTable
```
- qui va Creer le fichier de migration dans app/Database/Migrations
```
    2026-07-20-080000_CreateUsersTable.php
```
- Remplire ce fichier 

### Seeder
```
    php spark make:seeder UserSeeder
```
- qui va Creer le fichier de migration dans app/Database/Seeder
``` 
    app/Database/Seeds/UserSeeder.php
```


sqlite3 writable/mobile_money.sqlit