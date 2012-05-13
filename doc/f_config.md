# f_config

Obluga plikow konfiguracyjnych.
Pliki configuracyjna sa ladowane na zadanie. Sa keszowane.


./app/config/foo.php

~~~php
<?php

return array(
    'bar' => 'baz',
);
~~~

~~~php
<?php

$oConfig = new f_config();
$oConfig->path('./app/config/');

echo $oConfig->foo['bar']; // => baz
echo $oConfig->foo['bar']; // => baz (brak drugiego wywolania require, uzywany cache)
~~~


## Zaawansowany przyklad

/app/config/main.php

~~~php
<?php

return array(
    'dev' => array(
        'db' => (object) array (
            'host'    => 'localhost',
            'name'    => 'fine',
            'user'    => 'fine',
            'pass'    => 'XXXXXXXXXXXXXXX',
            'charset' => 'utf8'
        ),
        'error' => array(
            'level'      => E_ALL ^ E_NOTICE,
            'log'        => true,
            'render'     => true,
            'throwError' => E_ALL ^ E_NOTICE
        ),
    ),
    'prod' =>  array(
        'db' => (object) array (
            'host'    => 'localhost',
            'name'    => 'fine',
            'user'    => 'fine',
            'pass'    => 'XXXXXXXXXXXXXXX',
            'charset' => 'utf8'
        ),
        'error' => array(
            'level'  => E_ALL ^ E_NOTICE,
            'log'    => true,
            'render' => false,
        ),
    ),
);
~~~

~~~php
<?php

$oConfig = new f_config();
$oConfig->path('./app/config/');
$oConfig->main  =  $oConfig->main->{f::$c->env});
~~~





