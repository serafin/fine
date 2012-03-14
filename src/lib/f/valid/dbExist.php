<?php

class f_valid_dbExist extends f_valid_abstract
{
    
    const NOT_EXIST = 'NOT_EXIST';

    protected $_msg = array(
        self::NOT_EQUAL => 'Błędna wartość',
    );
    public $table;
    public $field;

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