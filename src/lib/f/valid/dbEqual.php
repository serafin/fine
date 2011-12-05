<?php

class f_valid_dbEqual extends f_valid_abstract
{
	
	const NOT_EQUAL = 'notEqual';
	
    public $valueEqual;
    public $sqlCode;
    public $var;
    
    public function __construct($sValue, $sSqlCode, $asVar = null, $aMsg = null)
    {
        $this->valueEqual = $sValue;
        $this->sqlCode    = $sSqlCode;
        $this->var        = $asVar;
		parent::__construct($aMsg);
    }
    
	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
    	$asVars = $this->var;
    	$sQuery = $this->sqlCode;
    	$sValue = (string) $mValue;
        $this->_val($sValue);
    	
		$sQuery = str_replace('{value}', db::escape($sValue), $sQuery);
        
        if ($this->valueEqual !== db::one($sQuery, $asVars)) {
            $this->_error(self::NOT_EQUAL);
            return false;
        }

        return true;
    }
    
}