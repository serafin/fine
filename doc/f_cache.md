# f_cache 

Cache to połączenie dwóch obiektów: "frontend cache" i "backend cache".
"Frontend cache" udostepnia programiscie interfejs obsługi (sprawdzenie czy jest cache, odczyt,
zapis, usuniecie). "Backend cache" odpowiedzialny jest za sposb przechowywania danych (pliki na dysku, baza danych).

~~~php
<?php

$oCache = new f_cache(array(
    'prefix'  => 'user_',
    'backend' => new f_cache_backend_file(),
));

if (! $aData = $oCache->get('online')) {
    $aData = $oDb->rows("SELECT * FROM online");
    $oCache->save($aData);
}

print_r($aData);
~~~


## Cache buforu wyjscia

~~~php
<?php

$oCache = new f_cache_output(array(
    'backend' => new f_cache_backend_file(),
));

?>

<? if(! $oCache->start('number')) : ?>
    <? $aNumber = getArrayOfSuperRandNumbers(1, 10000); ?>
    <div class="box-number">
        <ul>
            <? foreach ($aNumber as $k => $v) : ?>
                <li>
                    $aNumber[<?= $k ?>] = <?= $v ?><br />;
                </li>
            <? endforeach; ?>
        </ul>
    </div>
    <? $oCache->stop(); ?>
<? endif; ?>
~~~


# Zarys API
~~~php
<?php

class f_cache
{

    public $backend; // instanceof f_cache_backend_interface

    protected $_prefix;  // key prefix
    protected $_lastKey; // last used key (it's for f_cache::save(), f_cache_output::stop())
    protected $_on;      // if true: all works
                         // else:
                         //     - get() and is() @return false
                         //     - set(), save(), remove(), removeAll() don't call backend methods
                         // backend always works
    protected $_time;    // default 300 s.


    public function backend($oBackend = null); // get/set instanceof f_cache_backend_interface
    public function prefix($sPrefix = null);   // get/set key prefix @return $this|string
    public function on($bCachingOn);           // on/off caching @return $this

    public function set($sKey, $mValue); // set cache @return $this
    public function get($sKey);          // get cache @return mixed
    public function is($sKey);           // is cache @return boolean
    public function remove($sKey);       // remove cache by key @return $this
    public function save($mValue);       // alias for set() where $sKey is last used key


}

class f_cache_output extends f_cache
{

    public function start($sKey); // if cache is: echo cache content and return true;
                                  // else: run ob_start() return false

    public function stop($sKey = null);
                                 // saves ob_get_content(), run ob_end_flush()
                                 // $sKey is optional, if not set last key is used

}

interface f_cache_backend_interface
{

    public function set($sKey, $mData);
    public function get($sKey, $iTime);
    public function is($sKey, $iTime);
    public function remove($sKey);
    public function removeAll($iTime = null);
                                   // remove all with time older then $iTime 
                                   // or if $iTime not set remove all

}

class f_cache_backend_file implements f_cache_backend_interface
{

    protected $_dir  = 'cache/';

    /**
     * $hash = md5($sKey);
     * ./cache/.htaccess => ORDER DENY ALLOW\nDENY FROM ALL
     * ./cache/{$hash[0]}{$hash[1]}/{$hash}
     *
     */
    protected function _path($sKey); // @return string
}

class f_cache_backend_db implements f_cache_backend_interface
{


    protected $_model = 'app_m_cache'; // cache_id (string), cache_time (int), cache_data(blob)
                                       // cache_id = md5($sKey)

}
~~~

