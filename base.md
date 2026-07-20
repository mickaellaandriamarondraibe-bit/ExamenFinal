- je modifie dans database :
```
    public array $default = [
    'DSN'          => '',
    'hostname'     => '',
    'username'     => '',
    'password'     => '',
    'database'     => WRITEPATH . 'mobile_money.sqlite',
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
- je creer le sqlite :
```
touch writable/mobile_money.sqlite
```

- je creer le script base.sql

- je met le base.sql dans sqlite
sqlite3 writable/mobile_money.sqlite < base.sql

-j'ntre dans base 

rm writable/mobile_money.sqlite
touch writable/mobile_money.sqlite
sqlite3 writable/mobile_money.sqlite < base.sql