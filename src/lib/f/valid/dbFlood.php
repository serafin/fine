<?php

class f_valid_dbFlood extends f_valid_abstract
{
	
	const FLOOD = 'flood';

    public $table;
    public $field;
    public $seconds;
    public $where;
	
    protected $_var = array('seconds');
    
    public function __construct($iSeconds, $sTable, $sField = null, $asWhere = array(), $aMsg = null)
    {
        $this->table = $sTable;
        if ($sField == null){
            $sField = $this->table.'_insert';
        }
        $this->field    = $sField;
        $this->seconds  = $iSeconds;
        $this->where    = $asWhere;
		parent::__construct($aMsg);
    }

	public function _()
	{
		return new self;
	}

    public function isValid($mValue)
    {
        if (is_array($this->where) && !empty ($this->where)) {
            foreach ($this->where as $k => $v) {
                $aWhere[] = $k." = '".db::escape($v)."'";
            }
            $sWhere = implode(' AND ', $aWhere);
        }
        else{
            $sWhere = $this->where;
        }

        $iTime = time() - $this->seconds;
        if (db::one("SELECT $this->field FROM $this->table WHERE $this->field > $iTime " . ($sWhere ? ' AND '.$sWhere : ''))) {
            $this->_error(self::FLOOD);
			return false;
		}

		return true;
    }
}