<?php

class c_example_f_foap extends f_c_action
{

    public function __construct()
    {
        $this->_c->render->off();
    }

    public function indexAction()
    {
        // wariant 1 - zaczynamy f_foap_client_objectod klienta foap, klient ma wlasnosc `object` ktory odpowiada obiektowi w serwerze
        $client = new f_foap_client();
        $client->uri("http://{$_SERVER['SERVER_NAME']}/example_f_foap/server");
        f_debug::dump($client->object()->getId());
    }    
    
    public function index2Action()
    {
        // wariant 2 - definiujemy obiekt f_foap_client_object a w nim klienta
        f_debug::dump(c_example_f_foap__client_object::_()->getId());
    }

    public function serverAction()
    {
        $server = new f_foap_server();
        $server->object(new c_example_f_foap__server_object());
        $server->response($this->response);
        $server->handle();
    }
    
}

class c_example_f_foap__client_object extends f_foap_client_object
{
    
    public function __construct(array $config = array())
    {
        $this->_client = new f_foap_client(array(
            'uri' => "http://{$_SERVER['SERVER_NAME']}/example_f_foap/server",
        ));
        
        parent::__construct($config);
    }
    
    
    public function getId()
    {
        return $this->_client->call('getId');
    }
    
    
}

class c_example_f_foap__server_object 
{
    
    public function getId()
    {
        return 1234;
    }
    
}