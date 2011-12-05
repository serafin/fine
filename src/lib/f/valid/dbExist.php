<?php

class f_valid_dbExist extends f_valid_abstract
{
	
	const NOT_EXIST = 'notExist';
	
    public $table;
    public $field;
    
    public function __construct($sField, $sTable = null, $aMsg = null)
    {
		if ($sTable === null) {
			list ($sTable) = explode('_', $sField);
		}
        $this->table = $sTable;
        $this->field = $sField;
		parent::__construct($aMsg);
    }
    
	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
        $sTable = $this->table;
        $sField = $this->field;
    	$sValue = (string) $mValue;
        $this->_val($sValue);

        if (0 == db::one("SELECT COUNT(*) FROM $sTable WHERE $sField = '?'", $sValue)) {
            $this->_error(self::NOT_EXIST);
            return false;
        }
		
        return true;
    }
    
}