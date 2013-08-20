<?php

class f_di
{

    public function _()
    {
        return new self();
    }

    public function __get($name)
    {
        if (method_exists($this, "_{$name}")) {
            return $this->{"_{$name}"}();
        }

        return null;
    }
    
    public function __isset($name)
    {
        return method_exists($this, "_{$name}");
    }

}