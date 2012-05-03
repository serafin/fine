<?php

/** @todo implements zrobic */
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
    
    protected $_attr  = array('method' => self::METHOD_POST);
    protected $_decor = array(
        'formBody' => 'f_form_decor_formBody',
        'form'     => 'f_form_decor_form',
    );
    protected $_helper = 'form';
    
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
     * Ustawianie lub pobieranie lub usuwanie atrybutow
     *
     * @param array|string $asName
     * @param string $sValue
     * @return f_form_element
     */
    public function attr($asName = null, $sValue = null)
    {
        switch (func_num_args()) {
            case 0:

                return $this->_attr;

            case 1:

                if ($asName === null) {
                    $this->_attr = array();
                }
                else if (is_array($asName)) {
                    foreach ($asName as $k => $v) {
                        $this->_attr[$k] = $v;
                    }
                }
                else {
                    return $this->_attr[$asName];
                }
                return $this;

            case 2:

                if ($sValue === null) {
                    if (is_array($asName)) {
                        foreach ($asName as $k => $v) {
                            unset($this->_attr[$k]);
                        }
                    }
                    else {
                        unset($this->_attr[$asName]);
                    }
                }
                else {
                    $this->_attr[$asName] = $sValue;
                }
                return $this;

            default:
                /** @todo */
                throw new f_form_exception(array(
                    'type' => f_form_exception::BAD_METHOD_CALL,
                    'msg'  => 'Too many arguments',
                ));
        }
    }

    public function id($sId = null)
    {
        // getter
        if (func_num_args() === 0) {
            return $this->_attr['id'];
        }

        // setter
        if ($sId === null) {
            unset($this->_attr['id']);
        }
        else if ($sId === true) {
            $this->_attr['id'] = $this->_attr['name'];
        }
        else {
            $this->_attr['id'] = $sId;
        }
        return $this;
    }

    public function addClass($asName)
    {
        if (! is_array($asName)) {
            $asName = array($asName);
        }

        foreach ($asName as $k => $v) {
            if ($k != 0 || strlen($this->_attr['class']) > 0) {
                $this->_attr['class'] .= ' ';
            }
            $this->_attr['class'] .= $v;
        }

        return $this;
    }

    public function removeClass($sName = null)
    {
    	if ($sName === null) {
            $this->_attr['class'] = array();
    	}

        $class = explode(' ', $this->_attr['class']);
        foreach ($class as $k => $v) {
            if ($v == $sName) {
                unset ($class[$v]);
            }
        }
        $this->_attr['class'] = implode(' ', $class);

        return $this;
    }

    public function css($asName, $sValue = null)
    {

        $style = f_c_helper_arrayExplode::helper($this->_attr['style'], ';', ':');

        switch (func_num_args ()) {

            case 1:

                if (is_array($asName)) {
                    foreach ($asName as $k => $v) {
                        $style[$k] = $v;
                    }
                    return $this;
                }
                return $style[$asName];

            case 2:

                if ($sValue === null) {
                    unset($style[$sName]);
                }
                else {
                    $style[$asName] = $sValue;
                }
                break;

            default:

                throw new f_form_exception(array(
                    'type' => f_form_exception::BAD_METHOD_CALL,
                    'msg'  => 'Too many arguments',
                ));
        }

        $this->_attr['style'] = f_c_helper_arrayImplode::helper($style, ';', ':');

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
            if ($element->ignoreError()) {
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
            if ($element->ignoreValid()) {
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
            foreach ($this->_ as $sName => $oElement) {
                $this->_removeElement($sName);
            }
        }
        else {
            $this->_removeElement($sName);
        }
        return $this;
    }

    public function decor($aosuDecor)
    {
        print_r($aosuDecor);
        if ($aosuDecor === null) {
            $this->_decor = array();
            return $this;
        }
    	if (is_array($aosuDecor)) {
            foreach ($aosuDecor as $k => $v) {
                if (is_integer($k)) {
                    $this->_decor[] = $v;
                }
                else {
                    $this->_decor[$k] = $v;
                }
            }
            return $this;
    	}
        if (is_string($aosuDecor)) {
            if (!is_object($this->_decor[$aosuDecor])) {
                if (is_string($this->_decor[$aosuDecor])) {
                    $this->_decor[$aosuDecor] = new $this->_decor[$abnosDecor];
                }
                else if (is_array($this->_decor[$aosuDecor])) {
                    $class = array_shift($this->_decor[$aosuDecor]);
                    $this->_decor[$aosuDecor] = new $class($this->_decor[$aosuDecor]);
                }
            }
            return $this->_decor[$aosuDecor];
        }

        $this->_decor[] = $aosuDecor;
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

            $decor->object = $this;
            $decor->buffer = $render;
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