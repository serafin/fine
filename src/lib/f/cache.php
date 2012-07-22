<?php

class f_cache
{

    /**
     * Czy cache jest wlaczony?
     *
     * Jezeli true, to wszystko dziala.
     * Jezeli false, to is, start zwraca false, get zwraca null. Set, save, remove nie uruchamiaja metod backendu.
     *
     * @var boolean
     */
    protected $_on;

    /**
     * Backend
     *
     * @var f_cache_backend_interface
     */
    protected $_backend;

    /**
     * Prefix dla klucza
     *
     * @var string 
     */
    protected $_prefix;

    /**
     * Czas waznosci danych w sekundach
     *
     * @var int 
     */
    protected $_time = 300; 

    /**
     * Ostatnio uzyty klucz. Ustawiane w `get`, `is`, `set`.  Uzywane w `save` i `stop`.
     *
     * @var string
     */
    protected $_lastKey;
    
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
     * @param array $aConfig
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function __isset($sName)
    {
        return $this->is($sName);
    }

    public function __set($sKey, $mValue)
    {
        $this->set($sKey, $mValue);
    }

    public function __get($sKey)
    {
        return $this->get($sKey);
    }

    public function __unset($sKey)
    {
        $this->remove($sKey);
    }

    /**
     * Ustala/pobiera Czy cache jest wlaczony
     *
     * @param boolean $bCachingOn
     * @return self
     */
    public function on($bCachingOn = null)
    {
        if (func_num_args() == 0) {
            return $this->_on;
        }

        $this->_on = $bCachingOn;
        return $this;
    }

    /**
     * Ustala/pobiera backend
     *
     * @param f_cache_backend_interface $oBackend
     * @return f_cache_backend_interface
     */
    public function backend($oBackend = null)
    {
        if (func_num_args() == 0) {
            return $this->_backend;
        }

        $this->_backend = $oBackend;
        return $this;
    }

    /**
     * Ustala/pobiera prefix dla klucza
     *
     * @param string $sPrefix
     * @return $this|string
     */
    public function prefix($sPrefix = null)
    {
        if (func_num_args() == 0) {
            return $this->_prefix;
        }

        $this->_prefix = $sPrefix;
        return $this;
    }

    /**
     * Ustala/pobiera czas waznosci danych
     *
     * @param int $iTime Czas w sekundach
     * @return self
     */
    public function time($iTime = null)
    {
        if (func_num_args() == 0) {
            return $this->_time;
        }

        $this->_time = $iTime;
        return $this;
    }

    /* Data - default */

    /**
     * Czy jest aktualny cache dla podanego klucza?
     *
     * @param $sKey Klucz
     * @return boolean
     */
    public function is($sKey)
    {
        $this->_lastKey = $this->_prefix . $sKey;

        if ($this->_on === false) {
            return false;
        }

        if ($this->_backend === null) {
            throw new f_cache_exception_badMethodCall("Nie ustawiony backend cacheu");
        }

        return $this->_backend->is($this->_prefix . $sKey, $this->_time);
    }

    /**
     * Ustawia dane dla podanego klucza
     *
     * @param string $sKey Klucz
     * @param mixed $mValue Dane
     * @return self
     */
    public function set($sKey, $mValue)
    {
        $this->_lastKey = $this->_prefix . $sKey;

        if ($this->_on === false) {
            return $this;
        }

        if ($this->_backend === null) {
            throw new f_cache_exception_badMethodCall("Nie ustawiony backend cacheu");
        }

        $this->_backend->set($this->_prefix . $sKey, $mValue);
        return $this;
    }

    /**
     * Pobiera dane dla podanego klucza
     *
     * @param string $sKey Klucza
     * @return mixed Dane
     */
    public function get($sKey)
    {
        $this->_lastKey = $this->_prefix . $sKey;

        if ($this->_on === false) {
            return null;
        }

        if ($this->_backend === null) {
            throw new f_cache_exception_badMethodCall("Nie ustawiony backend cacheu");
        }

        return $this->_backend->get($this->_prefix . $sKey, $this->_time);
    }

    /**
     * Usuwa dane dla podanego klucza
     *
     * @param type $sKey Klucz
     * @return self
     */
    public function remove($sKey)
    {
        $this->_lastKey = $this->_prefix . $sKey;

        if ($this->_on === false) {
            return $this;
        }

        if ($this->_backend === null) {
            throw new f_cache_exception_badMethodCall("Nie ustawiony backend cacheu");
        }

        $this->_backend->remove($this->_prefix . $sKey);
        return $this;
    }

    /* Data - nice save */

    /**
     * Ustawia dane dla ostatnio uzytego klucza
     *
     * @param type $mValue Dane
     * @return self
     */
    public function save($mValue)
    {
        if ($this->_on === false) {
            return $this;
        }

        if ($this->_backend === null) {
            throw new f_cache_exception_badMethodCall("Nie ustawiony backend cacheu");
        }

        $this->_backend->set($this->_lastKey, $mValue);
        return $this;
    }

    /* Data - output buffor */

    /**
     * Rozpoczecie cacheu przez bufor wyjscia
     *
     * Jezeli jest cache dla podanego klucza, to zostaje wyechowany i zostaje zwrocona wartosc true.
     * Jezeli nie, to rozpoczyna sie buforowanie wyjscia i zostaje zwrocona wartosc false.
     *
     * @param string $sKey Klucz
     * @return boolean Czy udalo sie pobrac aktualne dane cache?
     */
    public function start($sKey)
    {
        if ($this->_on === false) {
            return false;
        }

        $content = $this->get($sKey);

        if ($content !== false) {
            echo $content;
            return true;
        }

        ob_start();
        return false;

    }

    /**
     * Konczy cache przez bufor wyjscia
     *
     * Konczy buforowanie wyjscia, zapisuje bufor pod podany klucz lub ostatnio uzyty.
     *
     * @param type $sKey Klucz
     * @return self
     */
    public function stop($sKey = null)
    {
        if ($this->_on === false) {
            return $this;
        }

        $content = ob_get_flush();

        if (func_num_args() == 0) {
            $this->save($content);
        }
        else {
            $this->set($sKey, $content);
        }

        return $this;
    }

}
