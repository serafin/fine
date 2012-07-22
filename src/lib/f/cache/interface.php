<?php

class f_cache_interface
{

    public function is($sKey);           // is cache @return boolean
    public function set($sKey, $mValue); // set cache @return $this
    public function get($sKey);          // get cache @return mixed
    public function remove($sKey);       // remove cache by key @return $this

}