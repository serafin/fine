# f_db
Obsluga bazy danych.

## f_db_mysql
Manipulacje danymi bazy MySQL.

~~~php
<?php

$oDb = new f_db_mysql();
$oDb->connect('localhost', 'user', 'password');
$oDb->selectDb('blog');
$oDb->query("SET NAMES 'utf8'");

$oDb->query("INSERT INTO news (news_title, news_text) VALUES ('Tytul', 'Tresc newsa...')");
$rows = $oDb->rows("SELECT * FROM news");
```


	+----------------------------------+
	|               news               |
	+---------+------------+-----------+
	| news_id | news_title | news_text |
	+---------+------------+-----------+
	| 1       | Aaa        | Aaa aaa   |
	| 2       | Bbb        | Bbb bbb   |
	| 3       | Ccc        | Ccc ccc   |
	| 4       | Ddd        | Ddd ddd   |
	| 5       | Eee        | Eee eee   |
	+---------+------------+-----------+


### f_db_mysql::rows

pobiera rekordy, drugi wymiar to tablica asocjacyjna

```php
<?php

$result = $oDb->rows("SELECT * FROM news LIMIT 2");

print_r($result);

Array
(
    [0] => Array
        (
            [news_id] => 1
            [news_title] => Aaa
            [news_text] => Aaa aaa
        )
    [1] => Array
        (
            [news_id] => 2
            [news_title] => Bbb
            [news_text] => Bbb bbb
        )
)
```

### f_db_mysql::row

pobiera rekord jako tablice asocjacyjna

```php
<?php

$result = $oDb->row("SELECT * FROM news WHERE news_id = '1'");

print_r($result);

Array
(
    [news_id] => 1
    [news_title] => Aaa
    [news_text] => Aaa aaa
)
```

### f_db_mysql::cols

Zwraca jedno wymiarową tablice asocjacyjną gdzie kluczem jest pierwsze pole,
a wartością drugie z wyselekcjonowanych rekordow


```php
<?php

$result = $oDb->cols("SELECT news_id, news_title FROM news LIMIT 2");

print_r($result);


Array
(
    [1] => Aaa
    [2] => Bbb
)
```

### f_db_mysql::col

Zwraca jedno wymiarową tablice numeryczną
gdzie wartością pola tablicy jest pierwsze pole z wyselekcjonowanych rekordow


```php
<?php

$result = $oDb->col("SELECT news_title FROM news LIMIT 2");

print_r($result);


Array
(
    [0] => Aaa
    [1] => Bbb
)
```

### f_db_mysql::val

Zwraca wartosc pierwszego pola z pierwszego wyselekcjonowanego rekordu

```php
<?php

$result = $oDb->val("SELECT news_title FROM news WHERE news_id = '1'");

print_r($result);


Aaa
```


