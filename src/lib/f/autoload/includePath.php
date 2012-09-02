<?php

class f_autoload_includePath
{

    protected $_path      = array();
    protected $_suffix    = '.php';
    protected $_separator = '_';
    

    /**
     * Static oknstructor
     *
     * @return f_autoload_includePath
     */
    public static function _()
    {
        return new self;
    }

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     *
     * @param array $aIncludePaths
     * @return f_autoload_includePath|array
     */
    public function path(array $aIncludePaths = array())
    {
        if (func_num_args()) {
            $this->_path = $aIncludePaths;
            return $this;
        }
        else {
            return $this->_path;
        }
    }

    /**
     *
     * @param type $sFileSuffix
     * @return f_autoload_includePath|string
     */
    public function suffix($sFileSuffix= null)
    {
        if (func_num_args()) {
            $this->_suffix = $sFileSuffix;
            return $this;
        }
        else {
            return $this->_suffix;
        }
    }

    /**
     *
     * @param type $sSeparator
     * @return f_autoload_includePath|string
     */
    public function separator($sSeparator = null)
    {
        if (func_num_args()) {
            $this->_separator = $sSeparator;
            return $this;
        }
        else {
            return $this->_separator;
        }
    }

    /**
     *
     * @return f_autoload_includePath
     */
    public function register()
    {
        set_include_path(implode(PATH_SEPARATOR, $this->_path));
        spl_autoload_register(array($this, 'load'));
        return $this;
    }

    /**
     *
     * @return f_autoload_includePath
     */
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load'));
        return $this;
    }

    public function load($sClassName)
    {
        include str_replace($this->_separator, DIRECTORY_SEPARATOR, $sClassName . $this->_suffix);
    }

}