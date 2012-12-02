<?php

class c_foap_clinet
{
    
    protected $_uri;
    protected $_param;
    protected $_object;


    public function uri($sUri = null)
    {
        if (func_num_args() == 0) {
            return $this->_uri;
        }
        $this->_uri = $sUri;
        return $this;
    }
    
    public function param($asKey = null, $sValue = null)
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
                $this->_param[$asKey] = $sValue;
                return $this;
        }

    }
    
    /**
     * 
     * @return f_foap_client_object
     */
    public function object()
    {
        if ($this->_object == null) {
            $this->_object = new f_foap_client_object(array('_client' => $this));
        }
        
        return $this->_object;
    }
    
    public function call($sName, $aArguments)
    {
        
    }
}