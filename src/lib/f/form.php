<?php

class f_form implements ArrayAccess, IteratorAggregate, Countable
{

    /**
     * @var array Elementy formularza
     * dla udostepnienia pelnej przestrzeni nazw nie nazywa sie 'element'
     * glownie sluzy do zmiany kolejnosci elementow
     */
    public $_ = array(); 

    protected $_attr     = array('method' => 'post');
    protected $_decor    = array('helper' => 'f_form_decor_helper');
    protected $_viewHelper = 'form';


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
    public function  __get($sName)
    {
        return $this->element[$sName];
    }

    public function __set($sName, $oElement)
    {
        if (isset($this->element[$sName])) {
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

    }

	/**
	 * Ustala/pobiera akcje formularza - adres gdzie formularz ma zostać wysłany (wartość atrybutu action elementu form)
	 *
	 * @param array|string $asAction Adres
	 * @return string|$this
	 */
	public function action($asAction = null, $sRouteName = null)
	{
            if ($asAction === null) {
                return $this->_attr['action'];
            }
        else if (is_string($asAction)) {
            $this->_attr['action'] = $asAction;
        }
        else {
      		$this->_attr['action'] = f::$c->uri->helper($asAction, $sRouteName);
        }
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
		if (! $this->isValid() && $_POST && $this->_error === null) {
			foreach ($this->element as $oElement) {
				if (! $oElement->isValid()) {
					$this->_error[$oElement->name()] = $oElement->error();
                    /** @todo formInForm sobie z tym nie poradzi :( */
				}
			}
		}
		return $this->_error;
	}

	/**
	 * Sprawdza czy formularz sie waliduje lub czy podany element sie waliduje
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		if ($this->_isValid === null) {
            $this->_error = null;
            if (! $_POST || ($this->_submit !== null && ! isset($_POST[$this->_submit]))) {
                return $this->_isValid = false;
            }
            $bValid = true;
            foreach ($this->element as $oElement) {
                if (! $oElement->isValid()) {
                    $bValid = false;
                }
            }
            $this->_isValid = $bValid;

		}
		return $this->_isValid;
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
	 * @param object|string $osViewClass
	 * @return string
	 */
	public function render($osViewClass = null)
	{
        $sRender = '';
        foreach ($this->_decorator as $decorator) {
            if (is_array($decorator)) {
                $class = array_shift($decorator);
                $decorator = new $class($decorator);
            }
            $sRender = $decorator->render($sRender, $this);
        }
        return $sRender;
	}

	/**
	 * Renderuje same atrybuty elementu <form>
	 *
	 * @return string Fragment kodu html
	 */
	public function renderAttr()
	{
        if ($this->_form === null && ! isset($this->_attr['action'])) {
			$this->_attr['action'] = htmlspecialchars(f::$c->uri->helper(true));
        }
		return parent::renderAttr();
	}

	/**
	 * Ustala nazwe elementu submit formularza
	 * Wykorzystywane przy wyświetlaniu wielu formularzy na jednej stronie
	 *
	 * @param string $sName
	 * @return $this
	 */
	public function submit($sName = null)
	{
		$this->_submit = $sName;
		return $this;
	}

	/**
	 * Ustala/pobiera wartości formularza
	 *
	 * @param null|array $asValues null - pobiera wszystkie wartości, array - ustala wartości
	 * @param boolean $bDoHtmlSpecialChars
	 * @return array|$this wartości formularza
	 */
	public function val($aValues = null, $bDoHtmlSpecialChars = false)
	{
		if ($aValues === null) {
			$aValue = array();
			foreach ($this->element as $element) {
                if ($element instanceof f_form) {

                }
                else {
                    $aValue[$element->name()] = $element->val();
                }
			}
			return $aValue;
		}
		else {
			foreach ($aValues as $name => $value) {
				if (isset($this->element[$name])) {
					$this->element[$name]->val($value, $bDoHtmlSpecialChars);
				}
			}
		}
		return $this;
	}

    public function inForm($oForm)
    {
        if ($this->_decorator === array(array('f_form_decorator_form'))) {
            $this->_decorator = array();
        }
    }

    public function outForm($oForm)
    {
        if ($this->_decorator === array()) {
            $this->_decorator = array(array('f_form_decorator_form'));
        }
    }

    /* implements ArrayAccess */

	public function offsetExists($sName)
	{
		return isset($this->element[$sName]);
	}

	public function offsetGet($sName)
	{
		return $this->element[$sName];
	}

	public function offsetSet($sName, $oElement)
	{
        if (isset($this->element[$sName])) {
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
        $this->element[$sName] = $oElement;
        $oElement->inForm($this);
    }

    protected function _removeElement($sName)
    {
        $this->element[$sName]->outForm($this);
        unset($this->element[$sName]);
    }

}