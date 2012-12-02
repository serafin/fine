<?php

class f_event
{

    protected $_id;
    protected $_subject;
    protected $_val;
    protected $_cancel = false;
    protected $_dispacher;

    /**
     * Statyczny konstruktor
     * 
     * @param array $config
     * @return f_event
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    /**
     * Konstruktor
     * 
     * @param array $config
     */
    public function  __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }
    
    public function __call($name, $arguments)
    {
        if (count($arguments) == 0) {
            return $this->{$name};
        }
        $this->{$name} = $arguments[0];
        return $this;
    }
    
    public function subject($oSubject = null)
    {
        if (func_num_args() == 0) {
            return $this->_subject;
        }
        $this->_subject = $oSubject;
        return $this;
    }

    public function id($sId = null)
    {
        if (func_num_args() == 0) {
            return $this->_id;
        }
        $this->_id = $sId;
        return $this;
    }

    public function cancel($bCancel = null)
    {
        if (func_num_args() == 0) {
            return $this->_cancel;
        }
        $this->_cancel = (boolean)$bCancel;
        return $this;
    }

    public function val($mVal = null)
    {
        if (func_num_args() == 0) {
            return $this->_val;
        }
        $this->_val = $mVal;
        return $this;
    }
    
    public function dispacher($oDispacher = null)
    {
        if (func_num_args() == 0) {
            return $this->_dispacher;
        }
        $this->_dispacher = $oDispacher;
        return $this;
    }
    
    public function run()
    {
        /* @var $dispacher f_event_dispacher */
        $dispacher = $this->_dispacher ? $this->_dispacher : f::$c->event;
        
        $dispacher->run($this);
        
        return $this;
    }

}