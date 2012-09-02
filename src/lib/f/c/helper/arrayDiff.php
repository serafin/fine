<?php

class f_c_helper_arrayDiff
{

    /**
     * Oblicza roznice pomiedzy tablicami
     *
     * <b>Przyklad:</b>
     * <code>
     * >> f_c_helper_arrayDiff::helper(array('a', 'b', 'c', 'd'), array('a', 'c', 'e'));
     * => array('b', 'd')
     * </code>
     *
     * @param array $aMinuend Odjamna
     * @param array $aSubtrahend Odjemnik
     * @return array Różnica
     */
    public function helper($aMinuend, $aSubtrahend)
    {
        if (!is_array($aMinuend)) {
            return array();
        }
        if (!is_array($aSubtrahend)) {
            return $aMinuend;
        }
        $aDifference = array();
        foreach ($aMinuend as $i) {
            if (!in_array($i, $aSubtrahend)) {
                $aDifference[] = $i;
            }
        }
        return $aDifference;
    }

}