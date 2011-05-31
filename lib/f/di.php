<?php

class f_di
{

    protected static $_shared = array();

    protected $_useStaticShare = false;
    protected $_param          = array();

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

    public function removeParam($sName = null)
    {
        if ($sName !== null) {
            $this->_param[$sName] = array();
            return $this;
        }
        $this->_param = array();
        return $this;
    }

    public function param($asName, $mValue = null)
    {
        if (is_array($asName)) {
            foreach ($asName as $k => $v) {
                $this->_param[$k] = $v;
            }
            return $this;
        }
        if ($mValue !== null) {
            $this->_param[$asName] = $mValue;
            return $this;
        }
        return $this->_param[$asName];
    }

}