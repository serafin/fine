<?php

class f_valid_dbEqual extends f_valid_abstract
{

    const NOT_EQUAL = 'NOT_EQUAL';

    protected $_msg = array(
        self::NOT_EQUAL   => 'Błędna wartość',
    );
    protected $_equal = 1;
    protected $_sql;
    protected $_sqlVarVal = '{val}'; 
    protected $_db;
    protected $_defaultDb = 'db';
    
    public static function _(array $config = array())
    {
        return new self($config);
    }
    
    public function equal($mEqualValue = null)
    {
        if (func_num_args() == 0) {
            return $this->_equal;
        }
        $this->_equal = $mEqualValue;
        return $this;
    }

    public function sql($sSQLQuery = null)
    {
        if (func_num_args() == 0) {
            return $this->_sql;
        }
        $this->_sql = $sSQLQuery;
        return $this;
    }

    public function sqlVarVal($sSQLVarVal = null)
    {
        if (func_num_args() == 0) {
            return $this->_sqlVarVal;
        }
        $this->_sqlVarVal = $sSQLVarVal;
        return $this;
    }

    public function db($oDBService = null)
    {
        if (func_num_args() == 0) {
            return $this->_db;
        }
        $this->_db = $oDBService;
        return $this;
    }

    public function defaultDb($sDefaultDBServiceName = null)
    {
        if (func_num_args() == 0) {
            return $this->_defaultDb;
        }
        $this->_defaultDb = $sDefaultDBServiceName;
        return $this;
    }

    public function isValid($mValue)
    {
        $sValue = (string) $mValue;
        $this->_val($sValue);

        $db  = $this->_db !== null ? $this->_db : f::$c->{$this->_defaultDb};
        $sql = $this->_sql;
        
        if (strlen($this->_sqlVarVal) > 0) {
            $sql = str_replace($this->_sqlVarVal, $db->escape($sValue), $sql);
        }
        
        $result = $db->val($sql);
        
        if ($this->_equal !== $result) {
            $this->_error(self::NOT_EQUAL);
            return false;
        }

        return true;
    }
    
}