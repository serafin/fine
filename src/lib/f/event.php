<?php

class f_event
{

    protected $_id;
    protected $_subject;
    protected $_processed;

    /**
     * @param array $aConfig
     *      id*      => ID zdarzenia
     *      subject* => podmiot
     *      param    => tablica parametrow
     */
    public function  __construct(array $config = array())
    {
        $this->_id        = $config['id'];
        $this->_subject   = $config['subject'];
        
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
        if (func_num_args ()) {
            $this->_processed = $bCancel;
            return $this;
        }
        return $this->_cancel === false ? false : true;
    }

}