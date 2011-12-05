<?php

class f_c
{

    public function  __get($sName)
    {
        if ($sName === '_c') {
            return $this->_c = f::$c;
        }
        return $this->{$sName} = $this->_c->{$sName};
    }

    public function  __call($sName, $aArg)
    {
        return call_user_func_array(array($this->_c->{$sName}, 'helper'), $aArg);
    }

}