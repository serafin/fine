<?php

/**
 * @todo zrobic
 * 
 * obecnie szkielet validatora
 * 
 *  - moze przyjac string - nazwa pliku, sciezka pliku
 *  - moze przyjac array - z $_POST['x'], wtedy patrzy na $_POST['x']['tmp_name']
 *  - dodac metode lowercase  (true|false), default false
 *
 */
//class f_valid_fileExt extends f_valid_abstract
//{
//
//    const VALUE_EMPTY = 'VALUE_EMPTY';
//    const NOT_VALID   = 'NOT_VALID';
//
//    protected $_msg = array(
//        self::VALUE_EMPTY => '',
//        self::NOT_VALID   => '',
//    );
//    protected $_var = array(
//        '{ext}' => '_extForMsg'
//    );
//    protected $_ext;
//    protected $_extForMsg;
//
//    public static function _(array $config = array())
//    {
//        return new self($config);
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
//        if (!$this->_ext) {
//            $this->_extForMsg = '';
//        }
//        else {
//            $this->_extForMsg = "'" . implode("', '", $this->_ext) . "'";
//        }
//
//        return $this;
//    }
//
//    public function isValid($mValue)
//    {
//        $sValue = (string) $mValue;
//        $this->_val($sValue);
//
//        if ('' === $sValue) {
//            $this->_error(self::STRING_EMPTY);
//            return false;
//        }
//
////        if () {
////            $this->_error(self::NOT_VALID);
////            return false;
////        }
//
//        return true;
//    }
//
//}