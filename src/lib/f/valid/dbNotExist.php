<?php

class f_valid_dbNotExist extends f_valid_abstract
{

    const EXIST = 'exist';

    public $table;
    public $field;
    public $id;

    public function __construct($sField, $iId = null, $sTable = null, $aMsg = null)
    {
        if ($sTable === null) {
            list ($sTable) = explode('_', $sField);
        }
        $this->table = $sTable;
        $this->field = $sField;
        $this->id = $iId;
        parent::__construct($aMsg);
    }

    public function _()
    {
        return new self;
    }

    public function isValid($mValue)
    {
        $sTable       = $this->table;
        $sField       = $this->field;
        $sValue       = (string) $mValue;
        $sExceptWhere = '';
        if ($this->id !== null) {
            $sExceptWhere = ' AND ' . $sTable . '_id != ' . db::escape($this->id);
        }
        $this->_val($sValue);

        if (0 < db::one("SELECT COUNT(*) FROM $sTable WHERE $sField = '?'$sExceptWhere", $sValue)) {
            $this->_error(self::EXIST);
            return false;
        }

        return true;
    }

}