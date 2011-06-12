<?php

class f_autoload_includePath
{

    protected $_throwException = true;
    protected $_extension      = '.php';
    protected $_path           = array();
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

    public function throwException($bThrowExcpetionOnError = null)
    {
        if (func_num_args()) {
            $this->_throwException = $bThrowExcpetionOnError;
            return $this;
        }
        else {
            return $this->_throwException;
        }
    }

    public function load($sClassName)
    {
        @include str_replace($this->_separator, DIRECTORY_SEPARATOR, $sClassName . $this->_extension);

        if ($this->_throwException && !class_exists($sClassName, false)) {
            throw new f_autoload_exception(array(
                'type'               => f_autoload_exception::CLASS_NOT_FOUND,
                'msg'                => "Class $sClassName not found",
                'get_include_path()' => get_include_path(),
                'path'               => $this->_path,
                'separator'          => $this->_separator,
                'extension'          => $this->_extension,
            ));
        }
    }

}