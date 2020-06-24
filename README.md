[![Build Status](https://travis-ci.org/IsCod/GoMoney.svg?branch=master)](https://travis-ci.org/IsCod/GoMoney)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FIsCod%2FGoMoney.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FIsCod%2FGoMoney?ref=badge_shield)

# What GoMoney
Open PHP Framework 

## Links
[Documentation](https://iscod.github.io/GoMoney)

## Installation
```sh
git clone https://github.com/IsCod/GoMoney.git
cd GoMoney
composer install
```

## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FIsCod%2FGoMoney.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FIsCod%2FGoMoney?ref=badge_large)

# Route

## config

route.ini file in `App\config\route.ini` 

## add route 

you can add code in route.ini

```ini
[Welcome/index]
uri=Welcome/index
action=Welcome/index
method[]=GET
method[]=POST
```

# Controller

Controller is route.ini `uri` map `action`

eq.

route.ini have code:

```ini
[Welcome/index]
uri=Welcome/index
action=Welcome/index
method[]=GET
method[]=POST
```

new Welcome.php file

```sh
touch App/Controller/Welcome.php
```

edit code in  Welcome.php

```php
<?php

namespace App\Controller;

use GoMoney\Controller;

class Welcome extends Controller
{
    public function index()
    {
        echo "Hello World!";
    }
}
```

Access to web `http://example.com/welcome/index`

You see 

```
Hello World!
```

# DB

## config

route.ini file in `App\config\database.ini`

## add dbname

```ini
[dbname]
dsn=
driver=mysql
host=127.0.0.1
port=3306
database=database
username=root
password=
```

## connect db

```php
/**
 * @param null $database
 * @return object DB
 */
$db = DB::connect('dbname'); //return new pdo
```

## table

```php
/**
 * @param string $table
 * @param null $database
 * @return object DB
 */
$db = DB::connect('dbname')->table('tablename');
```

but you can method `table` realize

```php
$db = DB::table('tablename', 'dbname');
```

## insert

```php
$id = DB::connect('dbname')->table('tablename')->insert(['a'=>'a']);
```

## where

```php
/**
 * @return array
 */
$db = DB::connect('dbname')->table('tablename')->where(['id' => 1])->get();
```

or 

```php
$db = DB::connect('dbname')->table('tablename')->where('id', '=', 1)->get();
```

eq (only `=`)

```php
$db = DB::connect('dbname')->table('tablename')->where('id', 1)->get();
```

equivalent

```php
$db = DB::connect('dbname')->table('tablename')->where('id', '=', 1)->get();
```

if you get limit you can use method `limit`

```php
$db = DB::connect('dbname')->table('tablename')->where(['id' => 1])->limit(1)->get();
```

if you limit 1, you can method `getOne()`

```php
/**
 * @return array
 */
$db = DB::connect('dbname')->table('tablename')->where(['id' => 1])->getOne();
```

## exec

exec for SQL statement

```php
$db = DB::connect('dbname')->exec('SHOW TABLES');
``` 

# Todo

1, Route use registered
2, Error manage