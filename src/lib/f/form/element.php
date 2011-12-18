<?php

class f_form_element
{

    public $valid      = array();
    public $filter     = array();
    public $decor      = true;

    protected static $_configStatic = array(
        'default_decor' => array(
            'viewHelper' => 'f_form_decor_viewHelper',
            'label'      => 'f_form_decor_label',
            'error'      => 'f_form_decor_error',
            'desc'       => 'f_form_decor_desc',
            'tag'        => array('f_form_decor_tag', 'attr' => 'class="form_element"'),
        ),
        'checkboxOne_decor' => array(
            'viewHelper' => 'f_form_decor_viewHelper',
            'label'      => array('f_form_decor_label', 'placement' => 'EMBRACE'),
            'error'      => 'f_form_decor_error',
            'desc'       => 'f_form_decor_desc',
            'tag'        => array('f_form_decor_tag', 'attr' => 'class="form_element"'),
        ),
        'hidden_decor' => array(
            'viewHelper' => 'f_form_decor_viewHelper',
        ),
        'submit_decor' => array(
            'viewHelper' => 'f_form_decor_viewHelper',
            'tag'        => array('f_form_decor_tag', 'attr' => 'class="form_element"'),
        ),
    );

    protected $_type          = 'text';
    protected $_name;
    protected $_val;
    protected $_attr          = array();
    protected $_option        = array();
    protected $_separator     = array();
    protected $_ignoreVal     = false;
    protected $_ignoreRender  = false;
    protected $_isArray       = false;
    protected $_required      = false;
    protected $_requiredClass = 'f_valid_notEmpty';
    protected $_breakOnFail   = true;
    protected $_error         = array();
    protected $_viewHelper    = 'formText';
    protected $_label;
    protected $_desc;

    public static function configStatic(array $config)
    {
        self::$_configStatic = $config + self::$_configStatic;
    }

    /**
     * Element formularza
     *
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     * Renderuje element
     *
     * @return string Kod html elementu
     */
    public function __toString()
    {
        return $this->render();
    }

    public function toArray()
    {
        return array(
            'valid'         => $this->valid,
            'filter'        => $this->filter,
            'decor'         => $this->decor,
            'type'          => $this->_type,
            'name'          => $this->_name,
            'val'           => $this->_val,
            'attr'          => $this->_attr,
            'option'        => $this->_option,
            'separator'     => $this->_separator,
            'ignoreVal'     => $this->_ignoreVal,
            'ignoreRender'  => $this->_ignoreRender,
            'isArray'       => $this->_isArray,
            'required'      => $this->_required,
            'requiredClass' => $this->_requiredClass,
            'breakOnFail'   => $this->_breakOnFail,
            'error'         => $this->_error,
            'viewHelper'    => $this->_viewHelper,
            'label'         => $this->_label,
            'desc'          => $this->_desc,
        );
    }

    /**
     * Friendly method for f_form
     * 
     * @param f_form $oForm
     */
    public function form($oForm)
    {
    }


    /**
     * Ustala lub pobiera nazwe elementu
     *
     * @param string $sName nazwa elementu
     * @return string|this
     */
    public function name($sName = null)
    {
    	if (func_get_args() === 0) {
	    return $this->_name;
    	}
    	if (substr($sName, -2) == '[]') {
            $this->_name    = substr($sName, 0, -2);
            $this->_isArray = true;
        }
        else {
            $this->_name    = $sName;
            $this->_isArray = false;
        }
        return $this;
    }

    public function nameRaw()
    {
        return $this->_name . ($this->_isArray ? '[]' : '');
    }

    /**
     * Ustala lub pobiera wartość elementu
     *
     * @param mixed $mValue Wartość
     * @return mixed|this Wartość
     */
    public function val($mValue = null)
    {
    	if (func_num_args() === 0) {
            return $this->_val;
    	}
        /** @todo filtry */
        $this->_val = $mValue;
        return $this;
    }

    /* atrybuty i helpery dla atrybutow (style i class) */

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
                throw new f_form_exception(array(
                    'type' => f_form_exception::BAD_METHOD_CALL,
                    'msg'  => 'Too many arguments',
                ));
        }
    }

    public function id($sId = null)
    {
        if (func_num_args() === 0) {
            return $this->_attr['id'];
        }
        else if ($sId === null) {
            unset($this->_attr['id']);
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

        $style = f_helper_arrayAssocExplode::helper($this->_attr['style'], ';', ':');

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

        $this->_attr['style'] = f_helper_arrayAssocImplode::helper($style, ';', ':');
        
    	return $this;
    }


    public function option($asName = null)
    {
        switch (func_num_args()) {
            case 0:

                return $this->_option;

            case 1:

                if ($asName === null) {
                    $this->_option = array();
                }
                else if (is_array($asName)) {
                    foreach ($asName as $k => $v) {
                        $this->_option[$k] = $v;
                    }
                }
                return $this;

            default:
                throw new f_form_exception(array(
                    'type' => f_form_exception::BAD_METHOD_CALL,
                    'msg'  => 'Too many arguments',
                ));
        }
    }

    public function separator($sSeparator = null)
    {
    	if (func_num_args() === 0) {
            return $this->_separator;
    	}
        $this->_separator = $sSeparator;
        return $this;
    }

    public function ignoreVal($bIgnore = null)
    {
    	if ($bIgnore === null) {
            return $this->_ignoreVal;
    	}
        $this->_ignoreVal = $bIgnore;
        return $this;
    }

    public function ignoreRender($bIgnore = null)
    {
    	if ($bIgnore === null) {
            return $this->_ignoreRender;
    	}
        $this->_ignoreRender = $bIgnore;
        return $this;
    }

    /**
     * Ustala czy wartość elementu to tablica
     * @return $this
     */
    public function isArray($bIsArray = null)
    {
        if ($bIsArray === null) {
            return $this->_isArray;
        }
        $this->_isArray = $bIsArray;
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

        // getter
        if (func_num_args() === 0) {
            return $this->_required;
        }

        // setter

        $this->_required = (boolean) $bRequired;

        if ($this->_requiredClass === null) {
            return;
        }

        reset($this->_valid);
        $first = current($this->_valid);
        $class = is_array($first) ? $first[0] : get_class($first);
        if ($this->_required) {
            if ($class != $this->_requiredClass) {
                array_unshift($this->_valid, array($this->_requiredClass));
            }
        }
        else {
            if ($class == $this->_requiredClass) {
                array_shift($this->_valid);
            }

        }

    	return $this;
    }

    public function requiredClass($sClassNameValidNotEmpty = null)
    {
        // getter
        if (func_num_args() === 0) {
            return $this->_requiredClass;
        }
        
        //setter
        $this->_requiredClass = $sClassNameValidNotEmpty;
        return $this;
    }

    /**
     * Ustawia czy nie ma być dalszej walidacji po napotkaniu błędu
     *
     * @param boolean $bBreakOnFail Czy przerwać walidacje po napotkaniu błędu?
     * @return <type>
     */
    public function breakOnFail($bBreakOnFail = null)
    {
        // getter
        if ($bBreakOnFail === null) {
            return $this->_breakOnFail;
        }

        // setter
    	$this->_breakOnFail = (boolean) $bBreakOnFail;
    	return $this;
    }

    /**
     * Dodaje lub usuwa walidatory
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
     * Czy pole waliduje się poprawnie
     *
     * @return boolean
     */
    public function isValid($mValue = null)
    {

        if (func_num_args() === 1) {
            $this->val($mValue);
        }

        $value        = $this->val();
        $this->_error = array();

        if ($this->_required === false && ($value === '' || $value === null)) {
            return true;
        }

        $bValid = true;

        foreach ($this->valid as $k => $valid) {

            // lazy load validotor
            if (is_array($valid)) {
                $class = array_shift($valid);
                $this->valid[$k] = new $class($valid);
            }

            if (!$valid->isValid($valid)) {
                $bValid = false;
                foreach ($valid->error() as $i) {
                    $this->_error[] = $i;
                }
                if ($this->_breakOnFail) {
                        break;
                }
            }

        }

        return $bValid;
    }
    
    /**
     * Pobiera lub dodaje błędy
     *
     * @return array Tablica z treściami błędów
     */
    public function error($asError = null)
    {
        if ($asError === null) {
            return $this->_error;
        }
        if (is_array($asError)) {
            foreach ($asError as $i) {
                $this->_error[] = $i;
            }
            return $this;
        }
        $this->_error[] = $asError;
        return $this;
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


    public function decor($abnosDecor)
    {
        if ($abnosDecor === null || $abnosDecor === true) {
            $this->decor = $abnosDecor;
            return $this;
        }
    	if (is_array($abnosDecor)) {
            foreach ($abnosDecor as $k => $v) {
                if (is_integer($k)) {
                    $this->decor[] = $v;
                }
                else {
                    $this->decor[$k] = $v;
                }
            }
            return $this;
    	}
        if (is_string($abnosDecor)) {
            if ($this->decor === true) {
                $this->decorDefaultInit();
            }
            if (!is_object($this->decor[$abnosDecor])) {
                if (is_string($this->decor[$abnosDecor])) {
                    $this->decor[$abnosDecor] = new $this->decor[$abnosDecor];
                }
                else if (is_array($this->decor[$abnosDecor])) {
                    $class = array_shift($this->decor[$abnosDecor]);
                    $this->decor[$abnosDecor] = new $class($this->decor[$abnosDecor]);
                }
            }
            return $this->decor[$abnosDecor];
        }
        $this->decor[] = $abnosDecor;
        return $this;
    }

    public function decorDefaultInit()
    {
        $this->decor = isset(self::$_configStatic[$this->_type . "_decor"])
                     ? self::$_configStatic[$this->_type . "_decor"]
                     : self::$_configStatic["default_decor"];

    }

    public function render()
    {
        // default decorators form configStatic
        if ($this->decor === true) {
            $this->decorDefaultInit();
        }

        $render = "";

        foreach ((array)$this->decor as $k => $decor) {

            // lazy load decorator
            if (!is_object($decor)) {
                if (is_string($decor)) {
                    $this->decor[$k] = new $decor;
                }
                else if (is_array($decor)) {
                    $class = array_shift($decor);
                    $this->decor[$k] = new $class($decor);
                }
                $decor = $this->decor[$k];
            }

            $decor->element    = $this;
            $decor->content    = $render;
            $decor->decoration = "";
            $render            = $decor->render();
            
        }
        return $render;
    }

    public function viewHelper($sViewHelperName = null)
    {
    	if ($sViewHelperName === null) {
            return $this->_viewHelper;
    	}
        $this->_view = $sViewHelperName;
        return $this;
    }

    /**
     * Ustawia lub pobiera glowny opis pola
     *
     * @param string $sLabel Główny opis pola
     * @return string|this
     */
    public function label($sLabel = null)
    {
    	if ($sLabel === null) {
            return $this->_label;
    	}
        $this->_label = $sLabel;
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


}