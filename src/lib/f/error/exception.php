<?php

class f_error_exception extends f_exception
{

    protected $file;
    protected $line;

    public function  __construct(array $config = array())
    {
        $file = $config['file'];
        $line = $config['line'];
        unset($config['file'], $config['line']);

        parent::__construct($config);

        $this->file = $file;
        $this->line = $line;        
    }
    
}