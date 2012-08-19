<?php

class f_filter_trim
{
    
    public static function _()
    {
        return new self;
    }

    public function filter($sString)
    {
        return trim($sString);
    }
    
}