<?php
 
/**
 * Grip Elements of Structure
 *
 */
class c_helper_ges
{
    
    public function helper($aoData, $sGesQuery)
    {
        return $this->fetch($aoData, $sGesQuery);
    }
 
    /**
     * Wychwytuje wszystkie elementy wedlug podanego zapytania GES
     *
     * np.
     * $d = array('a' => array('b' => true), 'c' => array('d' => true, 'e' => 'f'));
     *
     * fetch($d, 'a/b')     => array(true)
     * fetch($d, 'a/*')     => array(true)
     * fetch($d, 'c/d')     => array(true)
     * fetch($d, '* /*')    => array(true, true)  (w wzorcu nie powinno byc spacji)
     * fetch($d, '*')       => array(array('b' => true), array('d' => true, 'e' => 'f'))
     * fetch($d, 'c/e=f?')  => array(array('d' => true, 'e' => 'f'))
     * fetch($d, 'c/e=f?e') => array('f')
     *
     * - wzorzec zaczyna sie od korzenia
     * - w warunku mozna uzyc kilku warunkow jednoczsnie e=f&g=h?
     * - struktura danych zbudowana z tablic, obiektow
     * - poszczegolne poziomy odzielone znakiem '/' (slash)
     * - dostepne odwolania to:
     *      1. klucz (klucz tablicy lub wlasnosc obiektu)
     *      2. * - dopasowuje wszystkie elementy z poziomu
     *      3. warunek (lub kilka warunkow jednoczesnie) dopasowanie elementow ktore spelniaja warunek
     *
     * @param array|object $aoData Struktura danych
     * @param type $sGesQuery Zapytanie/wzorzec w formacie GES
     * @return array wylapane elementy
     */
    public function fetch($aoData, $sGesQuery)
    {
        $elements = array();
        $this->_fetch($elements, $aoData, explode('/', $sGesQuery));
        return $elements;
    }
 
    public function first($aoData, $sGesQuery)
    {
        $elements = $this->fetch($aoData, $sGesQuery);
        return $elements[0];
    }
 
    protected function _fetch(&$aOutput, $mInput, $aQuery)
    {
 
        if (!is_array($mInput) && !is_object($mInput) && count($aQuery)) {
            return;
        }
 
        // get query for current levenl, update ges for next level
        $query = array_shift($aQuery);
 
        // condition
        if (strstr($query, '?')) {
            list($conditions, $query) = explode('?', $query);
            foreach (explode('&', $conditions) as $condition) {
                list($k, $v) = explode('=', $condition);
                if (is_array($mInput)) {
                    if ($mInput[$k] != $v) {
                        return;
                    }
                }
                else if (is_object($mInput)) {
                    if ($mInput->$k != $v) {
                        return;
                    }
                }
            }
        }
 
        // found
        if ($query == '') {
            $aOutput[] = $mInput;
        }
        // each
        else if ($query == '*') {
            foreach ($mInput as $i) {
                $this->_fetch($aOutput, $i, $aQuery);
            }
        }
        // query is a key of $mInput
        else {
            if (is_array($mInput)) {
                if (ctype_digit($query)) {
                    settype($query, 'int');
                }
                $this->_fetch($aOutput, $mInput[$query], $aQuery);
            }
            else if (is_object($mInput)) {
                $this->_fetch($aOutput, $mInput->$query, $aQuery);
            }
        }
    }
 
 
 
 
}