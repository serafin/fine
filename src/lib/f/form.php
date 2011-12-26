<?php

class f_form /* implements ArrayAccess, IteratorAggregate, Countable */
{
    /**
     * Method type constants
     */
    const METHOD_DELETE = 'delete';
    const METHOD_GET    = 'get';
    const METHOD_POST   = 'post';
    const METHOD_PUT    = 'put';

    /**
     * Encoding type constants
     */
    const ENCTYPE_URLENCODED = 'application/x-www-form-urlencoded';
    const ENCTYPE_MULTIPART  = 'multipart/form-data';

    /**
     * @var array Elementy formularza
     * dla udostepnienia pelnej przestrzeni nazw nie nazywa sie 'element'
     * glownie sluzy do zmiany kolejnosci elementow
     */
    public $_ = array();
    
    protected $_attr = array('method' => self::METHOD_POST);
    protected $_decor = array('viewHelper' => 'f_form_decor_viewHelperForm');
    protected $_viewHelper = 'form';
    
    protected $_error;
    

    /**
     * Tworzy i konfiguruje obiekt formularza
     *
     * @param array $aConfig Tablica gdzie kluczem jest nazwa funkcji tej klasy a wartością pierwszy argument
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     * Zwraca obiekt podanego elementu tego formularza
     *
     * @param string $sName nazwa elementu
     * @return object
     */
    public function __get($sName)
    {
        return $this->_[$sName];
    }

    public function __set($sName, $oElement)
    {
        if (isset($this->_[$sName])) {
            $this->_removeElement($sName);
        }
        $this->_addElement($oElement, $sName);
    }

    /**
     * Renderuje formularz
     *
     * @return string Wygenerowany kod html formularza
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Ustala/pobiera akcje formularza - adres gdzie formularz ma zostać wysłany (wartość atrybutu action elementu form)
     *
     * @param array|string $asAction Adres
     * @return string|$this
     */
    public function action($asAction = null)
    {
        if ($asAction === null) {
            return $this->_attr['action'];
        }

        if (!is_string($asAction)) {
            $asAction = f::$c->uri->helper($asAction);
        }
        $this->_attr['action'] = $asAction;
        return $this;
    }

    /**
     * Dodaje element lub elementy
     *
     * @param array|object $aosElement
     * @return $this
     */
    public function element($aoElement = null)
    {
        if (is_array($aoElement)) {
            foreach ($aoElement as $oElement) {
                $this->_addElement($oElement);
            }
        }
        else {
            $this->_addElement($aoElement);
        }
        return $this;
    }

    /**
     * Pobiera błędy napotkane przy walidacji
     *
     * @return unknown
     */
    public function error()
    {
        $errors = array();
        
        foreach ($this->_ as $element) {
            
            /* @var $element f_form_element */
            if ($element->ignoreVal()) {
                continue;
            }
            
            if ($element->error()) {
                $errors[$element->name()] = $element->error();
            }
        }
        
        return $errors;
    }

    /**
     * Sprawdza czy formularz sie waliduje l
     *
     * @return boolean
     */
    public function isValid()
    {
        $isValid = true;
        
        foreach ($this->_ as $element) {
            
            /* @var $element f_form_element */
            if ($element->ignoreVal()) {
                continue;
            }
            
            if (!$element->isValid()) {
                $isValid = false;
            }
        }
        
        return $isValid;
    }

    /**
     * Usuwa element z formularzu lub wszystkie jeżeli jako parametr została podana wartość null
     *
     * @param string|null $sName Nazwa elementu lub null
     * @return $this
     */
    public function removeElement($sName = null)
    {
        if ($sName === null) {
            foreach ($this->element as $sName => $oElement) {
                $this->_removeElement($sName);
            }
        }
        else {
            $this->_removeElement($sName);
        }
        return $this;
    }

    /**
     * Renderuje formularz
     *
     * @return string
     */
    public function render()
    {


        $render = "";

        foreach ((array) $this->_decor as $k => $decor) {

            // lazy load decorator
            if (!is_object($decor)) {
                if (is_string($decor)) {
                    $this->_decor[$k] = new $decor;
                }
                else if (is_array($decor)) {
                    $class = array_shift($decor);
                    $this->_decor[$k] = new $class($decor);
                }
                $decor = $this->_decor[$k];
            }

            $decor->element = $this;
            $decor->content = $render;
            $render = $decor->render();
        }
        return $render;
    }

    /**
     * Ustala/pobiera wartości formularza
     *
     * @param null|array $asValues null - pobiera wszystkie wartości, array - ustala wartości
     * @return array|$this wartości formularza
     */
    public function val($aValues = null)
    {
        
        if (func_num_args() === 0) {
            $values = array();
            foreach ($this->_ as $element) {
                /* @var $element f_form_element */
                if ($element->ignoreVal()) {
                    continue;
                }
                $values[$element->name()] = $element->val();
            }
            return $values;
        }
        else {
            foreach ($aValues as $name => $value) {
                if (isset($this->_[$name])) {
                    $this->_[$name]->val($value);
                }
            }
        }
        return $this;
    }


    /* implements ArrayAccess */

    public function offsetExists($sName)
    {
        return isset($this->_[$sName]);
    }

    public function offsetGet($sName)
    {
        return $this->_[$sName];
    }

    public function offsetSet($sName, $oElement)
    {
        if (isset($this->_[$sName])) {
            $this->_removeElement($sName);
        }
        $this->_addElement($oElement, $sName);
    }

    public function offsetUnset($sName)
    {
        $this->_removeElement($sName);
    }

    protected function _addElement($oElement, $sName = null)
    {
        if ($sName === null) {
            $sName = $oElement->name();
        }
        $this->_[$sName] = $oElement;
        
        $oElement->form($this);
    }

    protected function _removeElement($sName)
    {
        unset($this->_[$sName]);
    }

}