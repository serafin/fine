<?php

class f_longPolling implements f_longPolling_interface
{
    
    protected $_start;
    protected $_intervalMicroseconds = 100000;
    protected $_limit                = 300;
    protected $_callback;
    protected $_response;
    protected $_param;
    protected $_end = false;
    
    /**
     * Static construct
     * 
     * @param array $config
     * @return \self
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

    public function interval($iSeconds = null)
    {
        if (func_num_args() == 0) {
            return (int)$this->intervalMicroseconds() / 1000000;
        }
        $this->intervalMicroseconds($iSeconds * 1000000);
        return $this;
        
    }
            
    public function intervalMicroseconds($iMicroseconds = null)
    {
        if (func_num_args() == 0) {
            return $this->_intervalMicroseconds;
        }
        $this->_intervalMicroseconds = $iMicroseconds;
        return $this;
    }

    public function limit($iSeconds = null)
    {
        if (func_num_args() == 0) {
            return $this->_limit;
        }
        $this->_limit = $iSeconds;
        return $this;
    }
    
    public function callback($kCallback = null)
    {
        if (func_num_args() == 0) {
            return $this->_callback;
        }
        $this->_callback = $kCallback;
        return $this;
    }

    public function response(f_c_response $response = null)
    {
        if (func_num_args() == 0) {
            return $this->_response;
        }
        $this->_response = $response;
        return $this;
    }
    
    public function end($bEnd = null)
    {
        if (func_num_args() == 0) {
            return $this->_end;
        }
        $this->_end = $bEnd;
        return $this;
    }

    public function param($asKey = null, $sVal = null)
    {
        switch (func_num_args()) {

            case 0:
                // get all params
                return $this->_param;

            case 1:
                // set params from array
                if (is_array($asKey)) {
                    foreach ($asKey as $k => $v) {
                        if (is_int($k)) {
                            $this->_param[] = $v;
                        }
                        else {
                            $this->_param[$k] = $v;
                        }
                    }
                    return $this;
                }

                // get param by key
                return $this->_param[$asKey];

            case 2:
                // set param
                $this->_param[$asKey] = $sVal;
                return $this;
        }

    }

    public function handle()
    {
        $this->_start = time();
        while ($this->_start > time() - $this->_limit && !$this->_end) {
            call_user_func($this->_callback, $this);
            usleep($this->_intervalMicroseconds);
        }
        
    }
    
    
}
