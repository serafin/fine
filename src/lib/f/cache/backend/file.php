<?php

class f_cache_backend_file implements f_cache_backend_interface
{

    protected $_dir  = 'cache/';

    /**
     * Statyczny konstruktor
     *
     * @param array $config
     * @return self
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Konstruktor
     *
     * @param array $aConfig
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function dir($sDirectory = null)
    {
        if (func_num_args() == 0) {
            return $this->_dir;
        }

        $this->_dir = $sDirectory;
        if (substr($this->_dir, -1) != DIRECTORY_SEPARATOR) {
            $this->_dir .= DIRECTORY_SEPARATOR;
        }
        
        return $this;

    }

    /**
     * Czy dane dla podanego klucza istnieja i sa aktualne
     *
     * @param string $sKey Klucz
     * @param int $iTime Czas waznosci danych w sekundach, jezeli 0 to waznosc nigdy nie wygasa
     * @return boolean
     */
    public function is($sKey, $iTime)
    {
        $path = $this->_path($sKey);

        if (!is_file($path)) {
            return false;
        }

        return (filemtime($path) + $iTime >= time());
    }

    /**
     * Zapisuje dane
     *
     * @param string $sKey Klucz pod ktory maja byc zapisane dane
     * @param string $mData Dane
     * @return self
     */
    public function set($sKey, $mData)
    {
        $sha1 = sha1($sKey);
        $dir  =  $this->_dir . $sha1[0] . $sha1[1];

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $tmp = tempnam($dir, $sha1);
        $org = $dir . DIRECTORY_SEPARATOR . $sha1;

        file_put_contents($tmp, serialize($mData));

        rename($tmp, $org);
        chmod($org, 0666);
        clearstatcache();

        return $this;
    }

    /**
     * Odczytuje dane
     *
     * @param string $sKey Klucz
     * @param int $iTime Czas waznosci danych w sekundach, jezeli 0 to waznosc nigdy nie wygasa
     * @return mixed $data Dane
     */
    public function get($sKey, $iTime)
    {
        $path = $this->_path($sKey);

        if (!is_file($path)) {
            return false;
        }

        if (filemtime($path) + $iTime < time()) {
            return false;
        }

        return unserialize(file_get_contents($path));
    }

    /**
     * Usuwa dane dla podanego klucza
     *
     * @param string $sKey Klucz
     * @return self
     */
    public function remove($sKey)
    {
        $path = $this->_path($sKey);

        if (!is_file($path)) {
            return $this;
        }

        unlink($path);
        clearstatcache();

        return $this;
    }

    /**
     * Usuwa wszystkie lub nieaktualne dane
     *
     * Jezeli parametr $iTime nie zostanie podany to usuwa wszystkie dane.
     * Jezeli parametr $iTime zostanie podany to usuwa nieaktualne dane (wedlug tego parametru).
     *
     * @param int $iTime Czas waznosci danych w sekundach, jezeli 0 to waznosc nigdy nie wygasa
     * @return self
     */
    public function removeAll($iTime = null)
    {

        $range = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f');

        $outdate = func_num_args() == 1 && $iTime > 0 ? $iTime : false;
        $now     = time();

        foreach ($range as $first) {
            foreach ($range as $second) {

                $subdir = $this->_dir . $first . $second;

                if (!is_dir($subdir)) {
                    continue;
                }

                foreach (new DirectoryIterator($subdir) as $file) {

                    /* @var $file DirectoryIterator */

                    if (!$file->isFile()) {
                        continue;
                    }

                    if ($outdate !== false && $file->getMTime() + $outdate >= $now) {
                        continue;
                    }

                    unlink($file->getPathname());
                }
                
            }
        }

    }

    /**
     * Generuje sciezka do pliku wedlug podanego klucza
     *
     * @param string $sKey Klucz
     * @return string Sciezka do pliku
     */
    protected function _path($sKey)
    {
        $sha1 = sha1($sKey);
        return $this->_dir . $sha1[0] . $sha1[1] . DIRECTORY_SEPARATOR . $sha1;
    }
}