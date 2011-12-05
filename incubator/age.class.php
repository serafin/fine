<?php

class f_c_helper_age
{

	private $_Y;
	private $_m;
	private $_d;

	public function  __construct()
	{
		list($this->_Y, $this->_m, $this->_d) = explode('-', date('Ymd'));
	}

	public function helper($isDate)
	{
		if (empty($isDate)) {
			return '';
		}
		if (is_integer($isDate)) {
			$isDate = date('Y-m-d', $isDate);
		}
		list($Y, $m, $d) = explode('-', $isDate);
		$Y = $this->_Y - $Y;
		$m = $this->_m - $m;
		$d = $this->_d - $d;
		if ($m < 0 || ($m == 0 && $d < 0)) {
			$Y--;
		}
		if ($Y >= 0) {
			return  $Y;
		}
		return '';
	}

}