<?php

class f_filter_strtolower
{

    public static function _()
    {
        return new self;
    }
    
    public function filter($sString)
    {
		return strtolower($sString);
    }

}