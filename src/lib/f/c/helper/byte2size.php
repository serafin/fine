<?php

class f_c_helper_byte2size
{

    const UNIT_B  = 'B';
    const UNIT_kB = 'kB';
    const UNIT_MB = 'MB';
    const UNIT_GB = 'GB';

    const SIZE_kB = 1024;
    const SIZE_MB = 1048576;
    const SIZE_GB = 1073741824;

    /**
     *
     * @param type $iSize
     * @param type $tUnit
     * @param type $iRound
     * @return string
     * @throws f_c_exception_domain
     */
    public function helper($iSize, $tUnit = null, $iRound = 2)
    {
        if ($tUnit !== null) {
            switch ($tUnit) {
                case self::UNIT_GB:
                    return round($iSize/self::SIZE_GB, $iRound);
                case self::UNIT_MB:
                    return round($iSize/self::SIZE_MB, $iRound);
                case self::UNIT_kB:
                    return round($iSize/self::SIZE_kB, $iRound);
                case self::UNIT_B:
                    return $iSize;
                default:
                    throw new f_c_exception_domain(
                        'Nieobsługiwana jednostka ' . $tUnit . ' (obsługiwane: '
                      . self::UNIT_B . ', '
                      . self::UNIT_kB . ', '
                      . self::UNIT_MB . ', '
                      . self::UNIT_GB . ')'
                    );
            }
        }
        else {
            if ($iSize >= self::SIZE_GB) {
                return round($iSize/self::SIZE_GB, $iRound) . ' ' . self::UNIT_GB;
            }
            else if ($iSize >= self::SIZE_MB) {
                return round($iSize/self::SIZE_MB, $iRound) . ' ' . self::UNIT_MB;
            }
            else if ($iSize >= self::SIZE_kB) {
                return round($iSize/self::SIZE_kB, $iRound) . ' ' . self::UNIT_kB;
            }
            else {
                return $iSize . ' ' . self::UNIT_B;
            }
        }
        return null;
    }

}
