<?php

class f_c_dispacher extends f_c
{

    const EVENT_DISPACHER_PRE  = 'dispacher_pre';
    const EVENT_DISPACHER_POST = 'dispacher_post';

    /**
     * @var string Nazwa kontrolera
     */
    protected $_controller;

    /**
     * @var string Nazwa akcji
     */
    protected $_action;

    /**
     * @var string Wzorzez klasy kontrolera, dostepna zmienna "{controller}"
     */
    protected $_class = 'c_{controller}';

    /**
     * @var string Nazwa wymaganego iterfejsu dla klasy kontrolera
     */
    protected $_interface = 'f_c_action_interface';

    /**
     * @var string Wzorzez metody akcji, dostepna zmienna "{action}"
     */
    protected $_method = '{action}Action';

    /**
     * @var string Sciezka do klas kontrollerow
     */
    protected $_dir = './app/';

    /**
     * @var object Ostatni kontroller
     */
    protected $_object;

    /**
     * @var array Wszystkie wywolania
     */
    protected $_stack;

    /**
     *
     * @param array $config
     * @return f_c_dispacher 
     */
    public static function _(array $config = array()) 
    {
        return new self($config);
    }
    
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }
    
    public function controller($sController = null)
    {
        if (func_num_args() == 0) {
            return $this->_controller;
        }
        $this->_controller = $sController;
        return $this;
    }
    
    public function action($sAction = null)
    {
        if (func_num_args() == 0) {
            return $this->_action;
        }
        $this->_action = $sAction;
        return $this;
    }
    
    public function className($sClass = null)
    {
        if (func_num_args() == 0) {
            return $this->_class;
        }
        $this->_class = $sClass;
        return $this;
    }
    
    public function interfaceName($sInterface = null)
    {
        if (func_num_args() == 0) {
            return $this->_interface;
        }
        $this->_interface = $sInterface;
        return $this;
    }
    
    public function method($sMethod = null)
    {
        if (func_num_args() == 0) {
            return $this->_method;
        }
        $this->_method = $sMethod;
        return $this;
    }
    
    public function dir($sDir = null)
    {
        if (func_num_args() == 0) {
            return $this->_dir;
        }
        $this->_dir = $sDir;
        return $this;
    }
    
    public function object()
    {
        return $this->_object;
    }
    
    public function stack()
    {
        return $this->_stack;
    }
    
    
    /**
     * Uruchamia akcje kontrolera
     */
    public function run()
    {

        if (!isset($this->_controller[0])) {
            $this->_controller = 'index';
        }
        if (!isset($this->_action[0])) {
            $this->_action = 'index';
        }
        $class  = str_replace('{controller}', $this->_controller, $this->_class);
        $method = str_replace('{action}', $this->_action, $this->_method);
        $file   = $this->_dir . str_replace('_', '/', $class) . '.php';

        // check file
        if (! is_file($file)) {
            throw new f_c_exception_notFound();
        }

        include $file;

        // check class name
        if (!class_exists($class, false)) {
            throw new f_c_exception_notFound();
        }

        $this->_object = new $class;

        // check interface
        if (isset($this->_interface[0]) && ! ($this->_object instanceof $this->_interface)) {
            throw new f_c_exception_notFound();
        }

        // index method
        if (! method_exists($this->_object, $method)) {
            $index = str_replace('{action}', 'index', $this->_method);
            if ($method !== $index && ! method_exists($this->_object, $index)) {
                throw new f_c_exception_notFound();
            }
            $method        = $index;
            $this->_action = 'index';
        }

        /** @event dispacher_pre */
        if ($this->event->is(self::EVENT_DISPACHER_PRE)) {
            $this->event->run($event = new f_event(array('id' => self::EVENT_DISPACHER_PRE, 'subject' => $this)));
            if ($event->cancel()) {
                return;
            }
        }

        // call
        $this->_object->{$method}();

        // log stack
        $this->_stack[] = array(
            'controller' => $this->_controller,
            'action'     => $this->_action,
            'class'      => $this->_class,
            'interface'  => $this->_interface,
            'method'     => $this->_method,
            'dir'        => $this->_dir,
        );

        /** @event dispacher_end */
        if ($this->event->is(self::EVENT_DISPACHER_POST)) {
            $this->event->run(new f_event(array('id' => self::EVENT_DISPACHER_POST, 'subject' => $this)));
        }

    }

}    