<?php

class f_filter_callback
{

    protected $_callback;

    public static function _()
    {
        return new self;
    }

    public function callback($kCallback = null)
    {
        if ($kCallback === null) {
            return $this->_callback;
        }
        $this->_callback = $kCallback;
        return $this;
    }

    public function filter($nInput)
    {
        return call_user_func($this->_callback, $nInput);
    }

}