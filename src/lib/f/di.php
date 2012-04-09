<?php

class f_di
{

    protected static $_shared = array();

    protected $_useStaticShare = false;

    public function _()
    {
        return new self();
    }

    public function __get($name)
    {
        if ($this->_useStaticShare && isset(self::$_shared[$name])) {
            $this->{$name} = self::$_shared[$name];
        }

        if (method_exists($this, "_{$name}")) {
            return $this->{"_{$name}"}();
        }

        return null;
    }
    
    public function __isset($name)
    {
        return isset($this->{$name}) || method_exists($this, "_{$name}");
    }

}