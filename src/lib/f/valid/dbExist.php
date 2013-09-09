<?php

class f_valid_dbExist extends f_valid_abstract
{
    
    const NOT_EXIST = 'NOT_EXIST';

    protected $_msg = array(
        self::NOT_EXIST => 'Błędna wartość',
    );
    
    public $field;
    public $table;

    public static function _()
    {
        return new self;
    }
    
    public function isValid($mValue)
    {
        $sTable = $this->table;
        $sField = $this->field;

        $sValue = (string) $mValue;
        $this->_val($sValue);

        if (0 == f::$c->db->val("SELECT COUNT(*) FROM $sTable WHERE $sField = '{$sValue}'")) {
            $this->_error(self::NOT_EXIST);
            return false;
        }

        return true;
    }

}