<?php

interface f_cache_backend_interface
{

    /**
     * Czy dane dla podanego klucza istnieja i sa aktualne
     *
     * @param string $sKey Klucz
     * @param int $iTime Czas waznosci danych w sekundach, jezeli 0 to waznosc nigdy nie wygasa
     * @return boolean
     */
    public function is($sKey, $iTime);

    /**
     * Zapisuje dane
     *
     * @param string $sKey Klucz pod ktory maja byc zapisane dane
     * @param string $mData Dane
     * @return self
     */
    public function set($sKey, $mData);

    /**
     * Odczytuje dane
     *
     * @param string $sKey Klucz
     * @param int $iTime Czas waznosci danych w sekundach, jezeli 0 to waznosc nigdy nie wygasa
     * @return mixed $data Dane
     */
    public function get($sKey, $iTime);

    /**
     * Usuwa dane dla podanego klucza
     *
     * @param string $sKey Klucz
     * @return self
     */
    public function remove($sKey);

    /**
     * Usuwa wszystkie lub nieaktualne dane
     *
     * Jezeli parametr $iTime nie zostanie podany to usuwa wszystkie dane.
     * Jezeli parametr $iTime zostanie podany to usuwa nieaktualne dane (wedlug tego parametru).
     *
     * @param int $iTime Czas waznosci danych w sekundach, jezeli 0 to waznosc nigdy nie wygasa
     * @return self
     */
    public function removeAll($iTime = null);

}
