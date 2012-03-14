<?php

class f_timer
{

    protected $_on    = false;
    protected $_start;
    protected $_time  = 0.0;
    protected $_round = 4;

    public static function microtime()
    {
        $aTime = explode(" ", microtime());
        return ((float) $aTime[0] + (float) $aTime[1]);
    }
    
    public function round($iRound = null) 
    {
        if (func_num_args() == 0) {
            return $this->_round;
        }
        $this->_round = $iRound;
        return $this;
    }

    public function clear()
    {
        $this->_time = 0.0;
        return $this;
    }

    public function start()
    {
        $this->_on = true;
        $this->_start = self::microtime();
        return $this;
    }

    public function stop()
    {
        $this->_on = false;
        if ($this->_start) {
            $this->_time += self::microtime() - $this->_start;
            $this->_start = null;
        }
        return $this;
    }

    public function get()
    {
        if ($this->_on) {
            return round($this->_time + (self::microtime() - $this->_start), $this->_round);
        }
        else {
            return round($this->_time, $this->_round);
        }
    }

}