<?php

class f_c_helper_arrayMerge
{

    /**
     * Łączy dwie lub wiecej tablic zachowując klucze
     *
     * <b>Przyklad</b>
     * <code>
     * >> f_c_helper_arrayMerge::helper(array('a' => 'b', 'c' => 'd'), array('c' => 'c', 'e' => 'f'));
     * => array('a' => 'b', 'c' => 'c', 'e' => 'f')
     * </code>
     *
     * <b>Przyklad 2</b>
     * <code>
     * >> f_c_helper_arrayMerge::helper(array(array('a' => 'b', 'c' => 'd'), array('c' => 'c', 'e' => 'f')));
     * => array('a' => 'b', 'c' => 'c', 'e' => 'f')
     * </code>
     *
     * @param array $aArray1 tablica do złączenia lub tablica tablic do złączenia <=> $aArray2 == null
     * @param array $aArray2 kolejna tablica do złączenia
     * @return array
     */
    public function helper($aArray1, $aArray2 = null)
    {
        if (func_num_args() > 1) {
            $aaArray = func_get_args();
        }
        else {
            $aaArray = $aArray1;
        }
        $aReturn = array();
        foreach ($aaArray as $aArray) {
            foreach ($aArray as $k => $v) {
                $aReturn[$k] = $v;
            }
        }
        return $aReturn;
    }

}