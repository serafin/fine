<?php

class f_c_dispacher extends f_c
{

    /**
     * @var string Nazwa kontrolera
     */
    public $controller;

    /**
     * @var string Nazwa akcji
     */
    public $action;

    /**
     * @var string Wzorzez klasy kontrolera, dostepna zmienna "{controller}"
     */
    public $class = 'c_{controller}';

    /**
     * @var string Nazwa wymaganego iterfejsu dla klasy kontrolera
     */
    public $interface = 'f_c_action_interface';

    /**
     * @var string Wzorzez metody akcji, dostepna zmienna "{action}"
     */
    public $method = '{action}Action';

    /**
     * @var string Sciezka do klas kontrollerow
     */
    public $dir = './app/';

    /**
     * @var object Ostatni kontroller
     */
     public $object;

    /**
     * @var array Wszystkie wywolania
     */
    public $stack;

    /**
     * Uruchamia akcje kontrolera
     */
    public function run()
    {
        
        if (!isset($this->controller[0])) {
            $this->controller = 'index';
        }
        if (!isset($this->action[0])) {
            $this->action = 'index';
        }
        $class  = str_replace('{controller}', $this->controller, $this->class);
        $method = str_replace('{action}', $this->action, $this->method);
        $file   = $this->dir . str_replace('_', '/', $class) . '.php';

        // check file
    	if (! is_file($file)) {
            exit('1');
            $this->error(f_error::ERROR_NOT_FOUND);
        }

        include $file;

        // check class name
        if (!class_exists($class, false)) {
            exit('2');
            $this->error(f_error::ERROR_NOT_FOUND);
        }

        $this->object = new $class;

        // check interface
        if (isset($this->interface[0]) && ! ($this->object instanceof $this->interface)) {
            exit('3');
            $this->error(f_error::ERROR_NOT_FOUND);
        }

        // index method
        if (! method_exists($this->object, $method)) {
            $index = str_replace('{action}', 'index', $this->method);
            if ($method !== $index && ! method_exists($this->object, $index)) {
            $this->error(f_error::ERROR_NOT_FOUND);
            }
            $method       = $index;
            $this->action = 'index';
        }

        // event dispacher
        if ($this->event->is('dispacher_pre')) {
            $this->event->run($oEvent = new f_event(array('id' => 'dispacher_pre', 'subject' => $this)));
            if ($oEvent->cancel()) {
                return;
            }
        }

        // call
        $this->object->{$method}();

        // log stack
        $this->stack[] = array(
            'controller' => $this->controller,
            'action'     => $this->action,
            'class'      => $this->class,
            'interface'  => $this->interface,
            'method'     => $this->method,
            'dir'        => $this->dir,
        );

        // event dispacher_end
        if ($this->event->is('dispacher_end')) {
            $this->event->run(new f_event(array('id' => 'dispacher_post', 'subject' => $this)));
        }

    }

}