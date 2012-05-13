# f_config

Obluga plikow konfiguracyjnych.
Pliki configuracyjna sa ladowane na zadanie. Sa keszowane.


./app/config/foo.php

~~~
<?php

return array(
    'bar' => 'baz',
);
~~~

~~~
<?php

$oConfig = new f_config();
$oConfig->path('./app/config/');

echo $oConfig->foo['bar']; // => baz
echo $oConfig->foo['bar']; // => baz (brak drugiego wywolania require, uzywany cache)
~~~


## Zaawansowany przyklad

/app/config/main.php

~~~
<?php

return array(
    'default' => array(
        'db' => array (
            'host'    => 'localhost',
            'name'    => 'fine',
            'user'    => 'fine',
            'charset' => 'utf8'
        ),
        'error' => array(
            'level' => E_ALL ^ E_NOTICE,
            'log'   => true,
        ),
    ),
    'dev' => array(
        'db' => (object) array (
            'pass' => 'XXXXXXXXXXXXXXX',
        ),
        'error' => array(
            'render'     => true,
            'throwError' => E_ALL ^ E_NOTICE
        ),
    ),
    'prod' =>  array(
        'db' => (object) array (
            'pass' => 'XXXXXXXXXXXXXXX',
        ),
        'error' => array(
            'render' => false,
        ),
    ),
);
~~~

~~~
<?php

$oConfig = new f_config();
$oConfig->path('./app/config/');
$oConfig->main_ =  $oConfig->main->{f::c->env};
$oConfig->main  = replaceStructureRecursice($oConfig->main->default, $oConfig->main->{f::$env});
~~~





