<?php

class f_c_helper_flash extends f_c
{

    const STATUS_INFO    = 'info';
    const STATUS_OK      = 'ok';
    const STATUS_ERROR   = 'error';
    const STATUS_WARNING = 'warning';

    protected $_storage;

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

    public function helper($sMsg, $sStatus = null, $aParams = null)
    {
        return $this->add($sMsg, $sStatus, $aParams);
    }

    public function storage(&$storage = null)
    {
        if (func_num_args() == 0) {
            return $this->_storage;
        }
        $this->_storage = &$storage;
        return $this;
    }

    public function add($sMsg, $sStatus = null, $aParams = null)
    {
        $this->_storage[] = array(
            'msg'    => $sMsg,
            'status' => $sStatus,
            'param'  => $aParams,
        );
        
        return $this;
    }

    public function get()
    {
        $msgs = $this->_storage;

        $this->remove();

        return $msgs;
    }

    public function is()
    {
        return (boolean) $this->_storage;
    }

    public function remove()
    {
        $this->_storage = array();
        return $this;
    }

    public function redirect($sUri)
    {
        $this->_c->redirect($sUri);
    }

    public function uri($sUri)
    {
        $this->redirect($this->uri($sUri));
    }

}