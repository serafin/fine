<?php

class f_c_helper_size2byte
{
    
    const SIZE_kB = 1024;
    const SIZE_MB = 1048576;
    const SIZE_GB = 1073741824;

    public static function helper($iSize, $sUnit = 'MB')
    {
        switch ($sUnit) {
            case 'GB':
                return $iSize * self::SIZE_GB;
            case 'MB':
                return $iSize * self::SIZE_MB;
            case 'kB':
                return $iSize * self::SIZE_kB;
            case 'B':
                return $iSize;
            default:
                return null;
        }
    }

}