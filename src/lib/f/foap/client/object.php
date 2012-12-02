<?php

class c_foap_client_object
{
    
    protected $_client;

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

    public function __call($name, $arguments)
    {
        $this->_client->call($name, $arguments);
    }
            
    protected function _client(f_foap_clinet $client)
    {
        $this->_client = $client;
    }
    
}    

class api extends c_foap_client_object
{
    
    public function __construct(array $config = array())
    {
        $this->_client = new f_foap_server(array(
            'uri' => '',
            'param' => array(
                'key' => '{YOUR_APPLICATION_KEY}'
            )
        ));
        
        parent::__construct($config);
    }
    
}