<?php

abstract class f_form_element extends f_from_tag
{

    public $valid      = array();
    public $filter     = array();

    protected $_name;
	protected $_label;
	protected $_desc;
    protected $_val;
    protected $_multi       = false;
    protected $_required    = false;
    protected $_requiredMsg;
	protected $_isValid;
    protected $_error       = array();
    protected $_breakOnFail = true;
    protected $_decorator;

	/**
	 * Element formularza
	 *
	 * @param array $aSetting Ustawienia - tablica asocjacyjna gdzie klucz to nazwa metody elementu a wartość to wartość metody
	 */
	public function __construct($aConfig = array())
	{
        foreach ($aConfig as $k => $v) {
            $this->{$k}($v);
        }
	}

	/**
	 * Renderuje sam element
	 *
	 * @return string Kod html elementu
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Ustawia czy nie ma być dalszej walidacji po napotkaniu błędu
	 *
	 * @param boolean $bBreakOnFail Czy przerwać walidacje po napotkaniu błędu?
	 * @return <type>
	 */
    public function breakOnFail($bBreakOnFail = true)
    {
    	$this->_breakOnFail = (boolean) $bBreakOnFail;
    	return $this;
    }

	/**
	 * Ustawia lub pobiera dodatkowy opis pola
	 *
	 * @param string $sDesc Dodatkowy opis pola
	 * @return string|this
	 */
    public function desc($sDesc = null)
    {
    	if ($sDesc === null) {
	        return $this->_desc;
    	}
    	else {
	        $this->_desc = $sDesc;
	        return $this;
    	}
    }

	/**
	 * Pobiera błędy walidatorów
	 *
	 * @return array Tablica z treściami błędów
	 */
    public function error()
    {
    	return $this->_error;
    }

	/**
	 * Dodaje filtr lub filtry do elementu formularz
	 *
	 * @param object|array $aoFilter Obiektu filtru lub tablica obiektów filtrów
	 * @return this
	 */
    public function filter($aoFilter)
    {
    	if (is_array($aoFilter)) {
    		foreach ($aoFilter as $k => $v) {
				if (is_integer($k)) {
					$this->filter[] = $v;
				}
				else {
					$this->filter[$k] = $v;
				}
    		}
    		return $this;
    	}
    	$this->filter[] = $aoFilter;
        return $this;
    }


	/*
	 * Czy istnieje błąd wygenerowany po wywołaniu isValid
	 *
	 * @return boolean
	 */
    public function isError()
    {
    	return (boolean) $this->_error;
    }

	/**
	 * Czy pole jest wymagane
	 * 
	 * @return boolean
	 */
    public function isRequired()
    {
    	return $this->_required;
    }

	/**
	 * Czy pole waliduje się poprawnie
	 *
	 * @return boolean
	 */
    public function isValid()
    {
    	if ($this->_isValid === null) {
    		$this->_isValid();
    	}
    	return $this->_isValid;
	}

	/**
	 * Ustawia lub pobiera główny opis pola
	 *
	 * @param string $sLabel Główny opis pola
	 * @return string|this
	 */
    public function label($sLabel = null)
    {
    	if ($sLabel === null) {
	        return $this->_label;
    	}
    	else {
	        $this->_label = $sLabel;
	        return $this;
    	}
    }

	/**
	 * Ustala czy wartość elementu to tablica
	 * @return $this
	 */
    public function multi($bMulti = true)
    {
		$this->_multi = (boolean) $bMulti;
		return $this;
    }

	/**
	 * Ustala lub pobiera nazwe elementu (atrybut name i id tagu html)
	 * @param string $sName nazwa elementu
	 * @return string|this
	 */
    public function name($sName = null)
    {
    	if ($sName === null) {
	        return $this->_name;
    	}
    	if (substr($sName, -2) == '[]') {
    		$this->_name  = substr($sName, 0, -2);
	       	$this->_multi = true;
	    }
	    else {
	    	$this->_name  = $sName;
	       	$this->_multi = false;
	    }
        return $this;
    }

	/**
	 * Usuwa filtry lub filtry
	 *
	 * @param string|null $sKey Jeżeli string - klucz filtru to usuwa ten filtry, jezeli null to usuwa wszystkie filtry
	 * @return this
	 */
    public function removeFilter($sKey = null)
    {
 		if ($sKey !== null) {
			unset ($this->filter[$sKey]);
			return $this;
		}
    	$this->filter = array();
		return $this;
    }

	/**
	 * Usuwa validatory lub validator
	 *
	 * @param string|null $sKey Jeżeli string - klucz walidatora to usuwa ten walidator, jezeli null to usuwa wszystkie walidatory
	 * @return this
	 */
    public function removeValid($sKey = null)
    {
		if ($sKey !== null) {
			unset ($this->valid[$sKey]);
			return $this;
		}
    	$this->valid = array();
		return $this;
    }

	/**
	 * Ustala czy element jest wymagany
	 *
	 * @param boolean $bRequired Czy element jest wymagany
	 * @return this
	 */
    public function required($bRequired = true)
    {
		$this->_required = (boolean) $bRequired;
    	return $this;
    }

	/**
	 * Ustala lub pobiera treść błędu dla wymaganego pola
	 *
	 * @param string $sMsg Treść błędu
	 * @return this|string Treść błędu
	 */
	public function requiredMsg($sMsg = null)
	{
		if ($sMsg === null) {
			if ($this->_requiredMsg === null) {
				$this->_requiredMsg = $this->_requiredMsg();
			}
			return $this->_requiredMsg;
		}
		$this->_requiredMsg = $sMsg;
		return $this;
	}

	/**
	 * Ustala lub pobiera wartość elementu
	 *
	 * @param string|array $sValue Wartość
	 * @param boolean $bDoHtmlspecialchars Czy wykonać htmlspecialchars
	 * @return string|array|this Wartość
	 */
    public function val($sValue = null, $bDoHtmlspecialchars = false)
    {
    	if ($sValue === null) {
	        return $this->_value;
    	}
    	else {
    		if (is_array($sValue) && $bDoHtmlspecialchars) {
				foreach ($sValue as $k => $v) {
					$sValue[$k] = htmlspecialchars($v);
				}
				$bDoHtmlspecialchars = false;
    		}
	        $this->_value = ($bDoHtmlspecialchars) ? htmlspecialchars($sValue) : $sValue;
    	    return $this;
    	}
    }

	/**
	 * Dodaje walidator lub walidatory
	 *
	 * @param array|object $aoValid walidator lub tablica walidatorów
	 * @return <type>
	 */
    public function valid($aoValid)
    {
    	if (is_array($aoValid)) {
    		foreach ($aoValid as $k => $v) {
				if (is_integer($k)) {
					$this->valid[] = $v;
				}
				else {
					$this->valid[$k] = $v;
				}
    		}
    		return $this;
    	}
    	$this->valid[] = $aoValid;
        return $this;
    }

	/**
	 * Ustala widok renderowania elementu dla formularza
	 *
	 * @param object|string $osViewClass nazwa lub obiekt klasy renderującej element dla formularza
	 */
    public function view($osViewClass = 'f_v_helper_formElement')
    {
    	$this->_view = $osViewClass;
    }

    public function render()
    {
        $render = $this->_render();
        foreach ($this->_decorator as $decorator) {
            if (is_array($decorator)) {
                $class = array_shift($decorator);
                $decorator = new $class($decorator);
            }
            $render = $decorator->render($render, $this);
        }
        return $render;
    }

    public function decoratorBase($bUseBaseDecorator = null)
    {
        if ($bUseBaseDecorator === null) {
            return $this->_decoratorBase;
        }
        $this->_decoratorBase = $bUseBaseDecorator;
        return $this;
    }

    public function decorator()
    {

    }

    public function addDecorator()
    {

    }

	/**
	 * Pobiera treść błędy dla wymaganego pola
	 *
	 * @return string Treść błędu
	 */
	protected function _requiredMsg()
	{
		return f_lang::_()->f_form_element->required;
	}

	/**
	 * Ustala czy element waliduje się prawidłowo
	 * 
	 */
	protected function _isValid()
	{
		$this->_error = array();

		if (!$_POST) {
			$this->_isValid = false;
			return;
		}

		if (empty($_POST[$this->_name]) && $_POST[$this->_name] !== '0') {
			if ($this->_required === true) {
				$this->_error[] = $this->requiredMsg();
				$this->_isValid = false;
			}
			else {
				$this->_isValid = true;
			}
			return;
		}

		$bValid = true;
		if ($this->filter) {
			foreach ($this->filter as $filter){
				$_POST[$this->_name] = $filter->filter($_POST[$this->_name]);
			}
		}
		if ($this->valid) {
			foreach ($this->valid as $valid) {
				if (!$valid->isValid($_POST[$this->_name])) {
					$bValid = false;
					foreach ($valid->error() as $i) {
						$this->_error[] = htmlspecialchars($i);
					}
					if ($this->_breakOnFail) {
						break;
					}
				}
			}
		}
		$this->_isValid = $bValid;
	}

}