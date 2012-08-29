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
        // get service/helper from DI Cointainer
        $object = parent::__get($sName);
        if ($object) {
           return $object;
        }

        // get helper from lib
        $class  = "f_c_helper_$sName";
        
        $object = new $class();
        if (! $object instanceof f_di_asNew_interface) {
            $this->{$sName} = $object;
        }
        
        return $object;
    }

}