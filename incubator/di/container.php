<?php

class f_di_container
{

    protected static $_shared = array();

    protected $_useStaticShare = false;
    //protected $_define         = array();
    protected $_param          = array();

    //private $_diDefiner;
    //private $_diBuilder;

    public function _()
    {
        return new self();
    }

    public function __get($name)
    {
        if ($this->_useStaticShare && isset(self::$_shared[$name])) {
            $this->{$name} = self::$_shared[$name];
        }

//        if (isset($this->_define[$name])) {
//            if ($this->_diBuilder === null) {
//                $this->_diBuilder = new f_di_sys_build($this, $this->_define, $this->_param, self::$_shared);
//            }
//            return $this->_diBuilder->build($name);
//        }

        if (method_exists($this, "_{$name}")) {
            return $this->{"_{$name}"}();
        }

        return null;
    }

//    public function define($sServiceName)
//    {
//        if ($this->_diDefiner === null) {
//            $this->_diDefiner = new f_di_sys_define($this, $this->_define, $this->_param, self::$_shared);
//        }
//        return new $this->_diDefiner->define($sServiceName);
//    }

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