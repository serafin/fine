# f_c

Kontroller, zadanie, odpowiedz, glowny kontener, helpery, serwisy, dyspozytor


## odwolanie do helperow/serwisow
~~~php
<?php

class foo extends f_c
{
    
    public function bar()
    {
        $this->_c->redirect->helper('http://ubuntu.com/');
        $this->redirect->helper('http://ubuntu.com/'); //f_c::__get('redirect')
        $this->redirect('http://ubuntu.com/'); //f_c::__call('redirect', 'http://ubuntu.com/')
    }

}

~~~

## f_c_dispacher

```php
<?php

$oDispacher = f_c_dispacher::_(array(
    'dir'           => './app/',
    'className'     => 'c_{controller}',
    'method'        => '{action}Action',
    'interfaceName' => 'f_c_action_interface',
));

$oDispacher->controller($_GET[0]);
$oDispacher->action($_GET[1]);
$oDispacher->run();

print_r(get_class($oDispacher->object()));
```

## f_c_render

## f_c_request

## f_c_response

```php
$oResponse = f_c_response::_();
$oResponse->code(200);
$oResponse->header('Content-Type', 'text/html; charset=utf-8');
$oResponse->body('<b>Hello World!</b>');
$oResponse->send();


f_c_response::_()
    ->redirect('http://github.com')
    ->send();
```
