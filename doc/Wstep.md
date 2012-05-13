# Wstep

## Instalacja

1. `$ git clone http://github.com/serafin/fine.git` (lub sciagnac zipa i rozpakowac)
2. `$ cp -a fine/src/* /var/www/vhosts/project1/html/`
3. http://project1.dev

## Standaryzacja

### Nazwy zmiennych 

W argumentach method w bibliotece zmienne maja prefixy:

 - a - array
 - b - boolean
 - c - char
 - f - float
 - i - integer
 - k - callback
 - m - mixed
 - n - number
 - o - object
 - r - resource
 - s - string
 - t - const
 - u - null

```
    f_m::select($aisParam = null);
```

### Settery i gettery

```
    <?php

    class example
    {

        protected $_param;
        
        public function param($sParam = null)
        {
            if (func_num_args() == 0) {
                return $this->_param;
            }
            $this->_param = $sParam;
            return $this;
        }

    }
```

### Unifikacja konstruktorow

```
    <?php

    class example
    {

        public function __construct(array $config = array())
        {
            foreach ($config as $k => $v) {
                $this->{$k}($v);
            }
        }

    }
```

Od tej zasady jest tylko jeden wyjatek i sa nim wyjatki.

### Konstruktor statyczny

```
    <?php

    class example
    {

        public function __construct(array $config = array())
        { 
            foreach ($config as $k => $v) {
                $this->{$k}($v);
            }
        }
        
        public static function _(array $config = array())
        {
            foreach ($config as $k => $v) {
                $this->{$k}($v);
            }
        }

    }

    example::_()->someMethod();
```

### Plynny kod

```
    <?php

    class example
    {

        public function __construct(array $config = array())
        { 
            foreach ($config as $k => $v) {
                $this->{$k}($v);
            }
        }
        
        public static function _(array $config = array())
        {
            foreach ($config as $k => $v) {
                $this->{$k}($v);
            }
        }

        public function method1()
        {
            ...
            return $this;
        }

        public function method2()
        {
            ...
            return $this;
        }

        public function method3()
        {
            ...
            return $this;
        }

    }

    example::_()
        ->method1()
        ->method2()
        ->method3();
```

### Wyjatki

Hierachia na przykladzie f_db

```
	<?php
	
	class f_db_mysql
	{ 
		...
		
		/**
		 * @throws f_db_exception_query
		 */
		public function query($sQuery) 
		{
			...
		}
		...
	}
	
	interface f_db_exception {} // (marker)
	
	class f_db_exception_query extends UnexceptedValueException implements f_db_exception{}
	
	$oDb = new f_db_mysql();
	$oDb->connect(...);
	$oDb->selectDb(...);
	try {
		$oDb->query("]:->");
	}
	catch (f_db_exception_query $e) {
	
	}
	catch (f_db_exception $e) {
	
	}
	catch (UnexpectedValueException $e) {
	
	}
	catch (RuntimeValueException $e) {
	
	}
	catch (Exception $e) {
	
	}
	
	
```


```
    <?php

    try {
        $oDb->query($sTestSQLQuery);
        echo 'sql ok';
    }
    catch (f_db_exception_query $e) {
        echo 'sql error';
    }
```

## Programowanie zdarzeniowe

(bardziej w stanowych aplikacjach)

zwykly callback

```
<?php


class f_filter_callback
{

    protected $_callback;

    public function callback($kCallback = null)
    {
        if ($kCallback === null) {
            return $this->_callback;
        }
        $this->_callback = $kCallback;
        return $this;
    }

    public function helper($nInput)
    {
        return call_user_func($this->_callback, $nInput);
    }

}

$oFilter = new f_filter_callback();
$oFilter->callback('strtolower');
echo $oFilter->helper('Hello World!');
```

(mozna podpiac jeden element,
trzeba wiedziec jak przypisac callback - jaka metoda, jaki parametr)

zdarzenia przez event dispacher

```
<?php

// 1. dyspozytor (dispacher)
$dispacher = new f_event_dispacher();

// 2. obserwator (observer, subscriber)
function filter($event)
{
    $event->val = strtolower($event->val);
}
$dispacher->on('do_filter', filter);

// 3. podmiot (subject, publicator)
$text  = 'Hello World!';
$event = new f_event(array('id' => 'do_filter', 'val' => $text));
$dispacher->run(event);
$text  = $event->val;
echo $text; // `hello world!`

```

wczesniej 2 elementy,
teraz doszedl 3 - dyspozytor, ktory komunikuje sie przez zdarzenie f_event


(obserwator i podmit nie musz o sobie nic wiedziec, 
komunikacja przez jednolite api, 
obserwator nie musi istniec, 
event nie musi istniec,
kolejnosc inicjalizacji dowolna,
mozna podpiac wiele elementow, 
nie trzeba implementowac mechnizmu callbacka w kazdym - jest raz zdefiniowany w dispacherze) 



```
<?php


$dispacher = new f_event_dispacher();

// obserwator nr 1
function filter($event)
{
    $event->val = strtolower($event->val);
}
$dispacher->on('do_filter', filter);

// obserwator nr 2
function filter2($event)
{
    $event->val = str_replace('!', '', $event->val);
}
$dispacher->on('do_filter', filter2);

// obserwator nr 3
function filter3($event)
{
    $event->val = str_replace(' ', '_', $event->val);
}
$dispacher->on('do_filter', filter3);


$text  = 'Hello World!';
$event = new f_event(array('id' => 'do_filter', 'val' => $text));
$dispacher->run($event);
$text  = $event->val;
echo $text; // `hello_world`

```


ACL

```
    <?php

    class index extends f_c
    {

        public function __construct()
        {
            
            ...        
        
            $this->event->on('dispacher_pre', array($this, 'checkRights')); // event dispacher

            $this->dispacher->run(); // controller action dispacher;
        }

        public function checkRights(f_event $event) 
        {
            if ($event->subject()->controller() != 'secret') {
                return;
            }
            $event->cancel();
            $this->error(f_error::ERROR_NO_ACCESS);
        }

```

Plugin ktory automaycznie dodaje header content-length do headera

```
    <?php

    class pluginContentLength extends f_c
    {

        public function register()
        {
            $this->event->on('response_pre', array($this, 'addContentLength'));
        }

        public function addContentLength(f_event $event)
        {
            if ($this->response->header('Content-Type') != 'text/html; charset=utf-8') {
                return;
            }
            $this->response->header('Content-Length', mb_strlen($this->response->body));
        }

    }

    $plugin = pluginContentLength();
    $plugin->register();
```

## Dependency Injection Container


f_di

```
    <?php

    class my_container extends f_di
    {

        protected function _serviceA()
        {
            return new stdClass();
        }

        protected function _serviceB()
        {
            return $this->serviceB = new stdClass();
        }

    }

    $oContainer = new my_container();
    $oService1  = $oContainer->serviceA;
    $oService2  = $oContainer->serviceA;
    $oService3  = $oContainer->serviceB;
    $oService4  = $oContainer->serviceB;
```
 

glowny kontener aplikacji

```
<?php

class f_c_container extends f_di
{
    // obsluga helperow f_c_helper_*
}

class container extends f_c_container
{

    protected function _v()
    {
        return $this->v = new f_v();
    }

    protected function _db()
    {
        $this->db = new f_db_mysql();
        $this->db->connct($this->config->main['db]['host'], ..., ...);
        $this->db->selectDb($this->config->main['db]['name']);
        $this->db->query("SET NAMES 'utf-8'");
        return $this->db;
    }

    ...

    
}

f::$c = new container();
```

(helpery tylko z f/c/helper sa ladowane, reszta trzeba sobie samemu ustawic)


kontroller

```
<?php

class foo extends f_c
{
    
    public function bar()
    {
        // $f::c == $this->_c;
        $this->_c->redirect->helper('http://ubuntu.com/');
        $this->redirect->helper('http://ubuntu.com/'); //f_c::__get('redirect')
        $this->redirect('http://ubuntu.com/'); //f_c::__call('redirect', 'http://ubuntu.com/')
    }

}
```

widok
```
<?= $this->_c->formText->helper('search'); ?>
<?= $this->formText->helper('search'); ?>
<?= $this->formText('search'); ?>
<?= $this->c->redirect('http://ubuntu.com/') ?>

```


struktura 

```
f::$c; (container)
f::$c->db; 
f::$c->v;
f::$c->v->c->v->c->redirect();
```

## Adresy

```
<?php

class container extends f_c_container
{

    protected function _uri()
    {
        $this->uri = new f_c_helper_uri();
    }

    protected function _uriBase()
    {
        return $this->uriBase = "/";
    }

}

class v_container extends f_v_container
{

    protected function _uri()
    {
        $this->uri = $this->c->uri;
    }

}


$oUri = f::$c->uri;

print_r($oUri->resolve('/controller/action/param1/value1/param2/value2'));

Array
    (
        [0] => controller
        [1] => action
        [2] => param1
        [3] => value1
        [4] => param2
        [5] => value2
        [controller] => action
        [param1] => value1
        [param2] => value2
    )


print_r($oUri->helper(array('controller', 'action', 'param1', 'value1', 'param2', 'value2'));

/controller/action/param1/value1/param2/value2

```



## Drzewo plikow

```
/var/www/vhosts/project1/html/
app/
    c/
        data.php
        error.php
        index.php
        public.php
        setup.php
    config/
        main.php
        data.php [news][img][size] = array('t320x260')
        public.php [css][style.css][v] = 1
    m/
    v/
        helper/
        layout/
        script/
            index/
                index.php
        container.php
    container.php
cache/
    aa/
    ab/
data/
    model1/
        {model1_id}_{model1_token}.jpg
    model2/
        {model2_id}_{model2_token}.{model_ext}
lib/
    f/
    f.php
public/
    css/
        style.css/
            box-users.css
tmp/

```

## Cykl zycia

### htaccess

.htaccess

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule .* index.php [L]
```

### index.php

```
<?php

require "lib/f/autoload/includePath.php";

f_autoload_includePath::_()
    ->path(array('.', 'app/m/', 'app/', 'lib/'))
    ->register();


class index extends f_c
{

    public function __construct()
    {
        
        // init main app cointainer
        f::$c = new container();

        // init error & exception handler
        $this->error->register();
        
        // do something for diffrent environment (like debugging)
        $this->{$this->env}();

        // session
        //session_start();
        
        // pretty uris
        $this->uri->resolveRequestUri();
        
        // run controller action by request
        $this->dispacher->run();

        // render if not renderd before (render attaches output result to response)
        //$this->render->renderOnce();

        // send response to client if not send before
        //$this->response->sendOnce();
        
    }

    public function dev()
    {

    }

    public function prod()
    {

    }

}

new index();
```

```
<?php

// render if not renderd before (render attaches output result to response)
$this->render->renderOnce();

// send response to client if not send before
$this->response->sendOnce();

lub

w kazdej akcji na koncu

$this->render();
$this->response();
        
```


### contenter

```
<?php

class container extends f_c_container
{

    protected function _config()
    {
        $this->config       = new f_config(array('path' =>  'app/config/'));
        $this->config->main = $this->config->main[$this->env];
        return $this->config;
    }

    protected function _db()
    {
        $config   = $this->config->main['db'];
        $this->db = new f_db_mysql();
        $this->db->connect($config['host'], $config['user'], $config['pass']);
        $this->db->selectDb($config['name']);
        $this->db->query("SET NAMES '{$config['charset']}'");
        return $this->db;
    }
    
    protected function _debug()
    {
        /** Will be done in v2.1 */
        return null; 
        
        $this->debug = new f_debug();
        $this->db    = new f_debug_component_db(array('component' => $this->db));
        return $this->debug;
    }

    protected function _dispacher()
    {
        $this->dispacher             = new f_c_dispacher();
        $this->dispacher->controller = $this->request->get(0);
        $this->dispacher->action     = $this->request->get(1);
        return $this->dispacher;
    }
    
    protected function _env()
    {
        return $this->env = 'dev';
        return $this->env = $_SERVER['ENV'] == 'dev' ? 'dev' : 'prod';
    }

    protected function _error()
    {
        return $this->error = new f_error($this->config->main['error']);
    }

    protected function _event()
    {
        return $this->event = new f_event_dispacher();
    }

    protected function _flash()
    {
        $this->flash          = new f_c_helper_flash();
        $this->flash->storage =& $_SESSION['flash'];
        return $this->flash;
    }

    protected function _reg()
    {
        return $this->reg = new stdClass();
    }

    protected function _request()
    {
        return $this->request = new f_c_request();
    }

    protected function _response()
    {
        return $this->response = new f_c_response();
    }

    protected function _render()
    {
        $this->render             = new f_c_render();
        $this->render->dispacher  = $this->dispacher;
        $this->render->viewObject = $this->v;
        $this->render->response   = $this->response;
        return $this->render;
    }

    protected function _v()
    {
        $this->v = new f_v();
        $this->v->_c = $this->vc;
        return $this->v;
    }

    protected function _vc()
    {
        return $this->vc = new v_container();
    }

    protected function _uri()
    {
        $this->uri = new f_c_helper_uri();
    }
    
    protected function _uriAbs()
    {
        return $this->uriAbs = "http://" . $_SERVER['SERVER_NAME'] . $this->uriBase;
    }

    protected function _uriBase()
    {
        return $this->uriBase = "/";
    }

}

```


