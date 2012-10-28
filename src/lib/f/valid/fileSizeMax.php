<?php

//class f_valid_fileSizeMax extends f_valid_abstract
//{
//
//    const NOT_VALID = 'NOT_VALID';
//
//    protected $_msg = array(
//        self::NOT_VALID => "Zbyt duży rozmiar pliku. Maksymalny rozmiar pliku to {max}",
//    );
//    protected $_var = array(
//        '{max}' => '_maxForMsg'
//    );
//    protected $_max;
//
//    public static function _(array $config = array())
//    {
//        return new self($config);
//    }
//    
//    public function max($iMax = null)
//    {
//        if (func_num_args() == 0) {
//            return $this->_max;
//        }
//        $this->_max = $iMax;
//        return $this;
//    }
//
//    public function ext($asExt = null)
//    {
//        // getter
//        if (func_num_args() == 0) {
//            return $this->_ext;
//        }
//
//        // setter
//        if (!is_array($asExt)) {
//            $asExt = explode(' ', $asExt);
//        }
//        $this->_ext = $asExt;
//
//        // extension for message
//        $this->_extForMsg = $this->_ext
//                          ? "'" . implode("', '", $this->_ext) . "'"
//                          : '';
//        return $this;
//    }
//
//    /**
//     * Ustala/pobiera wrazliwosc na wielkosc liter
//     *
//     * @param boolean $bCaseSensitive
//     * @return f_valid_fileExt|boolean
//     */
//    public function caseSensitive($bCaseSensitive = null)
//    {
//        if (func_num_args() == 0) {
//            return $this->_caseSensitive;
//        }
//        $this->_caseSensitive = $bCaseSensitive;
//        return $this;
//    }
//
//    public function isValid($mValue)
//    {
//        // $mValue from $_FILES ?
//        if (!is_string($mValue) && is_array($mValue) && isset($mValue['tmp_name'])) {
//            $mValue = $mValue['tmp_name'];
//        }
//
//        $parts = explode('.', (string)$mValue);
//        $ext   = (string)(count($parts) > 1 ? end($parts) : '');
//        $this->_val($ext);
//
//        if ('' === $ext) {
//            $this->_error(self::NO_EXTENSION);
//            return false;
//        }
//
//        $variants = $this->_ext;
//
//        if (!$this->_caseSensitive) {
//            $variants = array_map('strtolower', $variants);
//            $ext      = strtolower($ext);
//        }
//
//        if (!in_array($ext, $variants)) {
//            $this->_error(self::NOT_VALID);
//            return false;
//        }
//
//        return true;
//    }
//
//}