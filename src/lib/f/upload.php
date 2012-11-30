<?php

class f_upload implements IteratorAggregate
{

    protected $_key;
    protected $_index;

    public static function each($sInputName = null)
    {
        if ($sInputName !== null) {
            $aFile = array($sInputName => $_FILES[$sInputName]);
        } 
        else {
            $aFile = $_FILES;
        }
        
        $aReturn = array();
        foreach ($aFile as $input => $v) {
            if (is_array($aFile[$input]['error'])) {
                foreach ($aFile[$input]['error'] as $k => $v) {
                    $aReturn[] = new self(array('key' => $input, 'index' => $k));
                }
            } 
            else {
                $aReturn[] = new self(array('key' => $input));
            }
        }
        
        return $aReturn;
    }

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return self
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Konstruktor
     *
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        if ($this->_key === null) {
            $this->_key = key($_FILES);
            if (is_array($_FILES[$this->_key]['error'])) {
                $this->_index = 0;
            }
        }

        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function __get($name)
    {
        if ($name == 'image') {
            return $this->image();
        }
        return null;
    }

    public function getIterator()
    {
        return new ArrayIterator(self::each($this->_key));
    }

    /**
     * Zwraca obraz
     *
     * @return f_image
     */
    public function image()
    {
        if ($this->image === null) {
            $this->image = new f_image();
            $this->image->load($this->tmpName());
        }

        return $this->image;
    }

    /**
     * Ustala/pobiera klucz w tablicy $_FILES
     *
     * @param string $sKey
     * @return string|this
     */
    public function key($sKey = null)
    {
        if (func_num_args() == 0) {
            return $this->_key;
        }
        $this->_key = $sKey;
        return $this;
    }

    /**
     * Ustala/pobiera indeks w tablicy $_FILES dla 
     *
     * @param int $iIndex
     * @return int|this
     */
    public function index($iIndex = null)
    {
        if (func_num_args() == 0) {
            return $this->_index;
        }
        $this->_index = $iIndex;
        return $this;
    }

    /**
     * Cz
     * @return boolean
     */
    public function is()
    {
        if (!isset($_FILES[$this->_key]['tmp_name'])) {
            return false;
        }

        return is_uploaded_file($this->_value('tmp_name'));
    }

    public function error()
    {
        return $this->_value('error');
    }

    public function name()
    {
        return $this->_value('name');
    }

    public function tmpName()
    {
        return $this->_value('tmp_name');
    }

    public function size()
    {
        return $this->_value('size');
    }

    public function type()
    {
        return $this->_value('type');
    }

    public function extension()
    {
        if (strstr($this->name(), '.')) {
            return end(explode('.', $this->name()));
        }
        else {
            return '';
        }
    }

    public function extensionLower()
    {
        return strtolower($this->extension());
    }

    public function move($sDestination)
    {
        if (is_uploaded_file($this->tmpName())) {
            return move_uploaded_file($this->tmpName(), $sDestination);
        }
    }

    protected function _value($sValue)
    {
        if ($this->_index === null) {
            return $_FILES[$this->_key][$sValue];
        }

        return $_FILES[$this->_key][$sValue][$this->_index];
    }

}