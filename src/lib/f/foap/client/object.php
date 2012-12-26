<?php

class f_foap_client_object
{
    
    protected $_client;

    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function __construct(array $config = array())
    {
        
        if (isset($config['_client'])) {
            $this->_client = $config['_client'];
            unset($config['_client']);
        }
        
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function __call($name, $arguments)
    {
        return $this->_client->call($name, $arguments);
    }
            
}    
