<?php

class f_c_helper_byte2size
{

    const SIZE_kB = 1024;
    const SIZE_MB = 1048576;
    const SIZE_GB = 1073741824;
    
    public function helper($iSize, $sUnit = null, $iRound = 2)
    {
        if ($sUnit !== null) {
            switch ($sUnit) {
                case 'GB':
                    return round($iSize/self::SIZE_GB, $iRound);
                case 'MB':
                    return round($iSize/self::SIZE_MB, $iRound);
                case 'kB':
                    return round($iSize/self::SIZE_kB, $iRound);
                case 'B':
                    return $iSize;
                default:
                    throw new f_c_helper_exception(array(
                        'type' => f_c_helper_exception::INVALID_ARGUMENT,
                        'msg'  => "Nieobsługiwana jednostka '$sUnit'. Obsługiwane: MB, kB, B",
                    ));
            }
        }
        else {
            if ($iSize >= self::SIZE_GB) {
                return round($iSize/self::SIZE_GB, $iRound).' GB';
            }
            else if ($iSize >= self::SIZE_MB) {
                return round($iSize/self::SIZE_MB, $iRound).' MB';
            }
            else if ($iSize >= self::SIZE_kB) {
                return round($iSize/self::SIZE_kB, $iRound).' kB';
            }
            else {
                return $iSize.' B';
            }
        }
        return null;
    }

}
