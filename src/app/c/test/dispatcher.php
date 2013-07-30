<?php

class c_test_dispatcher extends f_c_action
{
    
    public function __construct()
    {
        $this->render->off();
    }
    

    public function testAction()
    {
        $class  = str_replace('{controller}', $this->_controller, $this->_class);
        $method = str_replace('{action}', $this->_action, $this->_method);
        
        $this->_object = new $class;
        
        $index = str_replace('{action}', 'index', $this->_method);
        
        f_debug::dump($class, 'class');
        f_debug::dump($method, 'method');
        f_debug::dump($index, 'index');
        //f_debug::dump($this->_object);
        
        f_debug::dump(method_exists($this->_object, $method), 'czy istnieje metoda w klasie');
        
        f_debug::dump($method !== $index, 'czy metoda rozna od \'index\'');
        
        f_debug::dump(method_exists($this->_object, $index), 'czy metoda \'index\' istnieje');
        
        f_debug::dump($method !== $index && ! method_exists($this->_object, $index));
    }
    
    /**
     * @var string Nazwa kontrolera
     */
    protected $_controller;

    /**
     * @var string Nazwa akcji
     */
    protected $_action = 'index';

    /**
     * @var string Wzorzez klasy kontrolera, dostepna zmienna "{controller}"
     */
    protected $_class = 'c_test_dispatcher';

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

}