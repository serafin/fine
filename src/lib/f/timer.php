<?php

class f_timer
{

	protected $_on    = false;
	protected $_start;
	protected $_time  = 0.0;
	
	public function clear()
	{
		$this->_time = 0.0;
		return $this;
	}
	
	public function start()
	{
		$this->_on    = true;
		$this->_start = f_c_helper_microtime::helper();
		return $this;
	}
	
	public function stop()
	{
		$this->_on    = false;
		if ($this->_start) {
			$this->_time += f_c_helper_microtime::helper() - $this->_start;
			$this->_start = null;
		}
		return $this;
	}
	
	public function get($iRound = 4)
	{
		if ($this->_on) {
			return round($this->_time + (f_c_helper_microtime::helper() - $this->_start), $iRound);
		}
		else {
			return round($this->_time, $iRound);
		}
	}
	
}