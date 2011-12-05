<?php

class f_c_container extends f_di
{

    protected static $_shared;

    public function  __call($sName, $aArg)
    {
        return call_user_func_array(array($this->{$sName}, 'helper'), $aArg);
    }

    public function __get($sName)
    {
        if ($object = parent::__get($sName)) {
           return $object;
        }

        $class = "f_c_helper_$sName";
        return $this->{$sName} = new $class();
    }

    
}