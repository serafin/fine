<?php

class f_valid_dbNotExist extends f_valid_abstract
{

    const EXIST = 'exist';
    
    protected $_msg = array(
        self::EXIST => 'Podana wartość już istnieje',
    );
    protected $_callback;
    
    public $table;
    public $field;
    public $id;

    public static function _(array $config = array())
    {
        return new self;
    }
    
    public function __construct($sField, $iId = null, $sTable = null, $aCallback = null)
    {
        if ($sTable === null) {
            list ($sTable) = explode('_', $sField);
        }
        $this->table = $sTable;
        $this->field = $sField;
        $this->id = $iId;
        $this->_callback = $aCallback;
    }

    public function isValid($mValue)
    {
        $sTable       = $this->table;
        $sField       = $this->field;
        $sValue       = (string) $mValue;

        if(count($this->_callback) > 0) {
            foreach($this->_callback as $class => $method) {
                if(method_exists($class, $method)) {
                    $oClass = new $class;
                    $sValue = $oClass->{$method}($mValue);
                }
            }
        }
        
        $sExceptWhere = '';
        if ($this->id !== null) {
            $sExceptWhere = ' AND ' . $sTable . '_id != ' . f::$c->db->escape($this->id);
        }
        $this->_val($sValue);

        if (0 < (int)f::$c->db->val("SELECT COUNT(*) FROM $sTable WHERE $sField = '{$sValue}'$sExceptWhere")) {
            $this->_error(self::EXIST);
            return false;
        }

        return true;
    }

}