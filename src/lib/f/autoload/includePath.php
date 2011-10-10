<?php

class f_autoload_includePath
{

    protected $_path           = array();
    protected $_extension      = '.php';
    protected $_separator      = '_';
    

    public static function _()
    {
        return new self;
    }

    /**
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

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

    public function extension($sFileExtension = null)
    {
        if (func_num_args()) {
            $this->_extension = $sFileExtension;
            return $this;
        }
        else {
            return $this->_extension;
        }
    }

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

    public function register()
    {
        set_include_path(implode(PATH_SEPARATOR, $this->_path));
        spl_autoload_register(array($this, 'load'));
        return $this;
    }

    public function unregister()
    {
        spl_autoload_unregister(array($this, 'load'));
        return $this;
    }

    public function load($sClassName)
    {
        @include str_replace($this->_separator, DIRECTORY_SEPARATOR, $sClassName . $this->_extension);
    }

}