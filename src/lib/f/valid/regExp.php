<?php

class f_valid_regExp extends f_valid_abstract
{
	
    const NOT_MATCH = 'NotMatch';

    /** @todo */
    protected $_msg =  array(
        self::NOT_MATCH => "Ihre Eingabe ('{value}') muss die Zeichenkette '{pattern}' enthalten",
    );
    protected $_var = array(
        'pattern' => '_pattern'
    );
    protected $_pattern;
    
	public static function _()
	{
		return new self;
	}

    public function pattern($sPattern = null)
    {
        if ($sPattern === null) {
            return $this->_pattern;
        }
        $this->_pattern = $sPattern;
        return $this;
    }

    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);
		
        if (!@preg_match($this->pattern, $sValue)) {
            $this->_error(self::NOT_MATCH);
            return false;
        }
		
        return true;
    }
    
}