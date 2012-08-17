<?php

class f_event
{

    protected $_id;
    protected $_subject;
    protected $_val;
    protected $_cancel = false;

    /**
     * @param array $aConfig
     *      id*      => ID zdarzenia
     *      subject* => podmiot
     *      val      => wartosc
     *      param    => tablica parametrow
     */
    public function  __construct(array $config = array())
    {
        $this->_id        = $config['id'];
        $this->_subject   = $config['subject'];
        $this->_val       = $config['val'];
        
        foreach ($config['param'] as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function subject()
    {
        return $this->_subject;
    }

    public function id()
    {
        return $this->_id;
    }

    public function cancel($bCancel = null)
    {
        if (func_num_args()) {
            $this->_cancel = (boolean)$bCancel;
            return $this;
        }
        return $this->_cancel === false ? false : true;
    }

    public function val($mVal = null)
    {
        if (func_num_args() == 0) {
            return $this->_val;
        }
        $this->_val = $mVal;
        return $this;;
    }

}