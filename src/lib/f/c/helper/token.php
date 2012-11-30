<?php

/**
 * @todo
 * - dodac metode md5();
 * - dodac protected _type, dodac CONST TYPE_MD5 i sha1, jako wartosc nazwa md5 i sha1 (tak jak nazwy fukcji)
 * - metoda helper to alias do token()
 * - metoda token zwraca token zalezny od _type czyli return $this->{$this->_type}();
 */
class f_c_helper_token
{

    public function helper()
    {
        return md5(uniqid(rand(), true));
    }
    
    public function sha1()
    {
        return sha1(uniqid(rand(), true));
    }

}