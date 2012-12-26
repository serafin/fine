<?php

class f_valid_dateStartLtStop extends f_valid_abstract
{
    const NOT_VALID = 'NOT_VALID';

    protected $_msg = array(
        self::NOT_VALID => 'Data końcowa mniejsza od daty początkowej',
    );
    
    protected $_start_date;
    protected $_start_time;
    protected $_stop_date;
    protected $_stop_time;

    public static function _(array $config = array())
    {
        return new self($config);
    }
   
    public function startDate($sName)
    {
        if (func_num_args() == 0) {
            return $this->_start_date;
        }
        $this->_start_date = $sName;
        return $this;
    }
    
    public function stopDate($sName)
    {
        if (func_num_args() == 0) {
            return $this->_stop_date;
        }
        $this->_stop_date = $sName;
        return $this;
    }
    
    public function startTime($sName)
    {
        if (func_num_args() == 0) {
            return $this->_start_time;
        }
        $this->_start_time = $sName;
        return $this;
    }
    
    public function stopTime($sName)
    {
        if (func_num_args() == 0) {
            return $this->_stop_time;
        }
        $this->_stop_time = $sName;
        return $this;
    }
    
    public function isValid($mValue)
    {           
        list($d, $m, $Y) = explode('.', $_POST[$this->_start_date]);
        if($_POST[$this->_start_time]){
            list($H, $i) = explode(':', $_POST[$this->_start_time]);
        }
        else {
            $H = 0;
            $i = 0;
        }
        $iStart = mktime($H, $i, 0, $m, $d, $Y);

        list($d, $m, $Y) = explode('.', $_POST[$this->_stop_date]);
        if($_POST[$this->_stop_time]){
            list($H, $i) = explode(':', $_POST[$this->_stop_time]);
        }
        else {
            $H = 0;
            $i = 0;
        }
        $iStop = mktime($H, $i, 0, $m, $d, $Y);
        
        if($iStart >= $iStop){
            $this->_error(self::NOT_VALID);
            return false;
        }
        
        return true;
    }

}
