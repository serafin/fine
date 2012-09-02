<?php

class f_c_helper_arrayExplode
{

    /**
     * Rozdziela string na tablice asocjacyjną
     *
     * <b>Przyklad</b>
     * <code>
     * >> f_c_helper_arrayExplode::helper('a=b&c=d');
     * => array('a' => 'b', 'c' => 'd')
     * </code>
     *
     * <b>Przyklad 2</b>
     * <code>
     * >> f_c_helper_arrayExplode::helper('a|b||c|d', '||', '|');
     * => array('a' => 'b', 'c' => 'd')
     * </code>
     *
     * @param string $sString String do rozdzielenia
     * @param string $sSplitParam Rozdzielacz elementów tablicy
     * @param string $sSplitKeyValue Rozdzielacz klucza od wartosci elementu tablicy
     * @return array Tablica asocjacyjna
     */
    public function helper($sString, $sSplitParam = '&', $sSplitKeyValue = '=')
    {
        $aReturn = array();
        foreach (explode($sSplitParam, $sString) as $sKeyValue) {
            $aKeyValue = explode($sSplitKeyValue, $sKeyValue);
            if (isset($aKeyValue[1])) {
                $aReturn[$aKeyValue[0]] = $aKeyValue[1];
            }
        }
        return $aReturn;
    }

}