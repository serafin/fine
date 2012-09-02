<?php

class f_c_helper_arrayImplode
{

    /**
     * Łączy tablice asocjacyjną w string
     *
     * <b>Przyklad</b>
     * <code>
     * >> f_c_helper_arrayImplode::helper(array('a' => 'b', 'c' => 'd'));
     * => 'a=b&c=d'
     * </code>
     *
     * <b>Przyklad 2</b>
     * <code>
     * >> f_c_helper_arrayImplode::helper(array('a' => 'b', 'c' => 'd'), '||', '|');
     * => 'a|b||c|d'
     * </code>
     *
     * @param array $aKeyValue Tablica asocjacyjna
     * @param string $sGlueParam Klej dla elementów
     * @param string $sGlueKeyValue Klej dla klucza i wartosci elementu
     * @return string polaczona tablica
     */
    public function helper($aKeyValue, $sGlueParam = '&', $sGlueKeyValue = '=')
    {
        $aReturn = array();
        foreach ($aKeyValue as $sKey => $sValue) {
            $aReturn[] = $sKey . $sGlueKeyValue . $sValue;
        }
        return implode($sGlueParam, $aReturn);
    }

}

