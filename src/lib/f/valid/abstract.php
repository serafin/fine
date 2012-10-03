<?php

abstract class f_valid_abstract
{

    protected $_var    = array();
    protected $_varVal = '{val}';
    protected $_msg    = array();
    protected $_error  = array();
    protected $_val;
    protected $_translator;

    /**
     * Konstruktor statyczny
     *
     * @return object
     */
    public static function _(array $config = array())
    {
        $sClass = get_called_class();
        return new $sClass($config);
    }

    /**
     * Konstruktor
     *
     * @return object
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     * Pobiera/ustawia obiekt tlumacza
     *
     * @param object $oTranslator obiekt tlumacza z metoda string t(string)
     * @return null|object
     *      jezeli argument jest rozny null to $this
     *      inaczej ustawiony wczesniej tlumacz lub null
     */
    public function translator($oTranslator = null)
    {
        if ($oTranslator === null) {
            return $this->_translator;
        }
        $this->_translator = $oTranslator;
        return $this;
    }

    /**
     * Pobiera/ustawia zmienna walidowanej wartosci w tresciach bledow
     *
     * @param null|string Zmienna, standardowo `{val}`
     * @return null|object
     */
    public function varVal($lsVarVal = null)
    {
        if (func_num_args() == 0) {
            return $this->_varVal;
        }
        $this->_varVal = $lsVarVal;
        return $this;
    }
    
    /**
     * Pobiera/ustawia tresci bledow
     *
     * Pobieranie
     *      array $o->msg();
     *      string $o->msg('err1');
     * Ustawianie
     *      $o $o->msg(array('err1' => 'Blad', 'err2' => 'Inny blad'));
     *      $o $o->msg('err1', 'Blad');
     *
     * Jako klucz bledu nalezy podawac stala klasy
     *
     * @param array|string $asKey
     * @param string $sMsg
     * @return array|object|string
     */
    public function msg($asKey = null, $sMsg = null)
    {
        if (is_array($asKey)) {
            foreach ($asKey as $k => $v) {
                $this->_msg[$k] = $v;
            }
            return $this;
        }
        if ($asKey === null) {
            return $this->_msg;
        }
        if ($sMsg === null) {
            return $this->_msg[$asKey];
        }
        $this->_msg[$asKey] = $sMsg;
        return $this;
    }

    /**
     * Dodaje blad
     *
     * @param string $sKey Nazwa(klucz) bledy
     * @param string|null $sMsg Tresc bledy, jezeli null to tresc zostanie wygenerowana
     * @return $this
     */
    public function addError($sKey, $sMsg = null)
    {
        if ($sMsg === null) {
            $sMsg = true;
        }
        $this->_error[$sKey];
        return $this;
    }

    /**
     * Czy sa jakies bledy
     *
     * @return boolean
     */
    public function isError()
    {
        return (boolean) $this->_error;
    }

    /**
     * Zwraca nazwy(klucze) bledow, tresci bledow nie sa generowane
     *
     * @return array
     */
    public function errorKey()
    {
        return array_keys($this->_error);
    }

    /**
     * Zwraca tablice bledow
     *
     * klucz to nazwa bledu, wartosc to wygenerowana tresc bledu
     * do generowania tresci bledow wykorzystywany jest tlumacz jesli jest zdefiniowany przez
     * $this->translator($oTranslator) lub w f::$c->_t
     *
     * @return array Bledy
     */
    public function error()
    {
        if (!$this->_translator) {
            if (isset(f::$c->t)) {
                $this->_translator = f::$c->t;
            }
        }

        foreach ($this->_error as $key => $msg) {
            
            if ($msg === true) {
                
                $msg = $this->_msg[$key];
                
                if ($this->_translator) {
                    $msg = $this->_translator->helper($msg);
                }
                
                if ($this->_varVal !== null) {
                    $msg = str_replace($this->_varVal, (string) $this->_val, $msg);
                }
                
                foreach ($this->_var as $var => $property) {
                    $msg = str_replace($var, $this->{$property}, $msg);
                }
                $this->_error[$key] = $msg;
            }
        }

        return $this->_error;
    }

    /**
     * Oznaczenie bladu
     * metode nalezy uzywac w metodzie isValid($mValue)
     *
     * @param string $sKey Nazwa (klucz) bledu
     */
    protected function _error($sKey)
    {
        $this->_error[$sKey] = true;
    }

    /**
     * Inicjacja walidacji
     * metode nalezy uzywac w metodzie isValid($mValue)
     *
     * @param mixed $mValue
     */
    protected function _val($mValue)
    {
        $this->_val   = $mValue;
        $this->_error = array();
    }

}