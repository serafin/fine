Obiekt cache to połączenie dwóch obiektów: "frontend cache" i "backend cache".
"Frontend cache" udostepnia programiscie interfejs obsługi (sprawdzenie czy jest cache, odczyt,
zapis, usuniecie). "Backend cache" odpowiedzialny jest za sposb przechowywania danych (pliki na dysku, baza danych).

~~~
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


Cache buforu wyjscia
====================

~~~
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
                    $aNumber[<span class="number_key"><?= $k ?></span>] =
                    <span class="number_val"><?= $v ?></span>;
                </li>
            <? endforeach; ?>
        </ul>
    </div>
    <?= $oCache->stop(); ?>
<? endif; ?>
~~~


API
===
~~~
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


    public function backend($oBackend = null); // get/set instanceof f_cache_backend_interface
    public function prefix($sPrefix = null);   // get/set key prefix @return $this|string
    public function on($bCachingOn);           // on/off caching @return $this

    public function set($sKey, $mValue); // set cache @return $this
    public function get($sKey);          // get cache @return mixed
    public function is($sKey);           // is cache @return boolean
    public function remove($sKey);       // remove cache by key @return $this
    public function removeAll();         // remove all cache @return $this
    public function save($mValue);       // alias for set() where $sKey is last used key
                                         // @return $this


}

class f_cache_output extends f_cache
{

    public function start($sKey); // if cache is: echo cache content and return true;
                                  // else: run ob_start() return false

    public function stop();       // saves ob_get_content() to last used key, run ob_end_flush()

}

interface f_cache_backend_interface
{

    public function set($sKey, $mData);
    public function get($sKey);
    public function is($sKey);
    public function remove($sKey);
    public function removeAll();

}

class f_cache_backend_file implements f_cache_backend_interface
{

    /** czas powinien byc w czesci frontend tylko jak to zrobic logicznie */
    protected $_time = 300; // if 0: cache never expires

    protected $_dir  = 'cache/';

    public function time($iSeconds = null); // get/set time

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


    protected $_time  = 300;
    protected $_model = 'app_m_cache'; // cache_id (string), cache_time (int), cache_data(blob)
                                       // cache_id = md5($sKey)

    public function time($iSeconds = null);

}
~~~
