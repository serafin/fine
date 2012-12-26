<?php

class f_valid_fileExt extends f_valid_abstract
{

    const NO_EXTENSION = 'NO_EXTENSION';
    const NOT_VALID    = 'NOT_VALID';

    protected $_msg = array(
        self::NO_EXTENSION => 'Brak rozszerzenia w nazwie pliku',
        self::NOT_VALID    => 'Niedozwolone rozszerzenie w nazwie pliku',
    );
    protected $_var = array(
        '{ext}' => '_extForMsg'
    );
    protected $_ext;
    protected $_extForMsg;
    protected $_caseSensitive = false;

    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function ext($asExt = null)
    {         
        // getter
        if (func_num_args() == 0) {
            return $this->_ext;
        }

        // setter
        if (!is_array($asExt)) {
            $asExt = explode(' ', $asExt);
        }
        $this->_ext = $asExt;

        // extension for message
        $this->_extForMsg = $this->_ext
                          ? "'" . implode("', '", $this->_ext) . "'"
                          : '';
        return $this;
    }

    /**
     * Ustala/pobiera wrazliwosc na wielkosc liter
     *
     * @param boolean $bCaseSensitive
     * @return f_valid_fileExt|boolean
     */
    public function caseSensitive($bCaseSensitive = null)
    {
        if (func_num_args() == 0) {
            return $this->_caseSensitive;
        }
        $this->_caseSensitive = $bCaseSensitive;
        return $this;
    }

    public function isValid($mValue)
    {
        // $mValue from $_FILES ?
        if (!is_string($mValue) && is_array($mValue) && isset($mValue['name'])) {
            $mValue = $mValue['name'];
        }

        $parts = explode('.', (string)$mValue);
        $ext   = (string)(count($parts) > 1 ? end($parts) : '');
        $this->_val($ext);
        
        if ('' === $ext) {
            $this->_error(self::NO_EXTENSION);
            return false;
        }

        $variants = $this->_ext;

        if (!$this->_caseSensitive) {
            $variants = array_map('strtolower', $variants);
            $ext      = strtolower($ext);
        }
        
        if (!in_array($ext, $variants)) {
            $this->_error(self::NOT_VALID);
            return false;
        }

        return true;
    }

}