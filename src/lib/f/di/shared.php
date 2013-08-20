<?php

abstract class f_di_shared extends f_di
{

    protected static $_shared = array();

    public function __get($name)
    {
        if (isset(self::$_shared[$name])) {
            return $this->{$name} = self::$_shared[$name];
        }

        return parent::__get($name);
    }
    

}