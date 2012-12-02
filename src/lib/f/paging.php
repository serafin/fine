<?php

class f_paging
{

    /**
     * Liczba wszystkich elementow
     * @var int 
     */
    protected $_all;

    /**
     * Liczba elementow na jednej stronie
     * @var int 
     */
    protected $_limit = 10;

    /**
     * Numer aktualnej strony
     * @var int
     */
    protected $_page = null;

    /**
     * Liczba wszystkich stron
     * @var type Liczba wszystkich stron
     */
    protected $_pages = null;

    /**
     * Numer poprzednie strony
     * @var int|null
     */
    protected $_prev = null;

    /**
     * Numer nastepnej strony
     * @var type
     */
    protected $_next = null;

    /**
     * Offset pierwszego elementu aktualnej storny
     * @var int|null
     */
    protected $_offset = 0;

    /**
     * Parametru adresu ktory odpowiada za aktualna strone
     * Wykorzystywany kiedy nie podana zostanie aktualna strona
     * i do generowania adresu przez helper widoku
     * @var string
     */
    protected $_uriParam = 'page';

    /**
     * Marker do generowania adresu kiedy uri zostanie ustawione jako string
     * @var string
     */
    protected $_uriVar = '{page}';

    /**
     * Adres dla helpera widoku do generowania linkow
     * @var array|string
     */
    protected $_uri;

    /**
     * Numer pierwszej storny
     * Jezeli w adresie pierwsza strona ma byc jako liczba 1 to _firstPage musi miec wartosc 1
     * @var type 
     */
    protected $_firstPage = 1;

    /**
     * Kontener dla dodatkowych parametrow
     * @var array
     */
    protected $_param;

    /**
     * Helper widoku ktory ma wyrenderowac obiekt stronicowania
     * @param array $config
     */
    protected $_helper = 'paging';

    /**
     * Konstruktor statyczny
     *
     * @param array $config
     * @return f_paging
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    /**
     * Konstruktor
     *
     * @param array $config
     * @return f_paging
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function __toString()
    {
        return $this->render();
    }

    /* Obliczanie */

    public function paging()
    {
        if ($this->_all > 0) {

            if ($this->_page === null) {
                $this->_page = (int) f::$c->request->get($this->_uriParam) - $this->_firstPage;
            }

            $this->_pages  = (int) ceil($this->_all / $this->_limit);

            $this->_page   = ($this->_page > 0 && $this->_page < $this->_pages)
                           ? $this->_page
                           : 0;

            $this->_offset = $this->_limit * $this->_page;

            $this->_prev   = ($this->_page > $this->_firstPage) 
                           ? $this->_page - 1 + $this->_firstPage
                           : null;
            
            $this->_next   = ($this->_page < $this->_pages - 1)
                           ? $this->_page + 1 + $this->_firstPage
                           : null;
        }

        return $this;
    }

    /* Dane wejsciowe */

    /**
     * Ustala/pobiera liczbe wszystkich elementow
     *
     * @param int $iAllItems
     * @return int|f_paging
     */
    public function all($iAllItems = null)
    {
        if (func_num_args() == 0) {
            return $this->_all;
        }

        $this->_all = $iAllItems;
        return $this;
    }

    /**
     * Ustala/pobiera numer aktualnej strony
     *
     * @param int $iCurrentPage
     * @return int|f_paging
     */
    public function page($iCurrentPage = null)
    {
        if (func_num_args() == 0) {
            return $this->_page + $this->_firstPage;
        }

        $this->_page = $iCurrentPage - $this->_firstPage;
        return $this;
    }

    /**
     * Ustala/pobiera limit - liczbe elementow na stronie
     *
     * @param type $iLimit
     * @return int|f_paging
     */
    public function limit($iLimit = null)
    {
        if (func_num_args() == 0) {
            return $this->_limit;
        }

        $this->_limit = $iLimit;
        return $this;
    }

    /* Dane wyjsciowe  */

    /**
     * Pobiera liczbe wszystkich stron
     * 
     * @return int
     */
    public function pages()
    {
        return $this->_pages;
    }

    /**
     * Pobiera offset pierwszego elementu aktualnej storny
     *
     * @return type
     */
    public function offset()
    {
        return $this->_offset;
    }

    /**
     * Pobiera numer nastepnej strony
     *
     * @return int|boolean Numer strony lub null
     */
    public function next()
    {
        return $this->_next;
    }

    /**
     * Pobiera numer poprzedniej strony
     *
     * @return int|boolean Numer strony lub null
     */
    public function prev()
    {
        return $this->_prev;
    }


    /* Renderowanie */

    public function toString()
    {
        return $this->render();
    }

    public function render()
    {
        return f::$c->v->{$this->_helper}($this);
    }

    /**
     * Ustala/pobiera nazwe helpera widoku
     *
     * @param type $sHelper
     * @return string|f_paging
     */
    public function helper($sHelper = null)
    {
        if (func_num_args() == 0) {
            return $this->_helper;
        }

        $this->_helper = $sHelper;
        return $this;
    }

    /* Konfiguracja zachowania stronicowania */

    /**
     * Ustala/pobiera numer pierwszej strony
     *
     * @param type $iFirstPage
     * @return \f_paging
     */
    public function firstPage($iFirstPage = null)
    {
        if (func_num_args() == 0) {
            return $this->_firstPage;
        }

        $this->_firstPage = $iFirstPage;
        return $this;
    }

    /* Parametry adresu */

    /**
     * Ustala/pobiera adres wykorzystywany przez helper widoku do generowania linku
     *
     * @param type $asUri 
     * @return array|string|f_paging
     */
    public function uri($asUri = null)
    {
        if (func_num_args() == 0) {
            return $this->_uri;
        }

        $this->_uri = $asUri;
        return $this;
    }

    /**
     * Ustala/pobiera nazwe parametru zadania w kotrym jest przechowywany numer aktualnej strony
     *
     * Wykorzystywany do:
     *  - automatycznego pobierania numeru strony z zadania jezeli nie zostanie 
     *    podany numer strony metoda `page()`. 
     *  - generowania linku przez helper widoku
     *
     * @param type $sUriParam
     * @return string|f_paging
     */
    public function uriParam($sUriParam = null)
    {
        if (func_num_args() == 0) {
            return $this->_uriParam;
        }

        $this->_uriParam = $sUriParam;
        return $this;
    }

    /**
     * Marker do generowania adresu kiedy uri zostanie ustawione jako string
     *
     * Mozna jako uri podac string np. /news/list/page/{page}
     * jezeli uriVar ustawiny jest na `{page}` to helper widoku podminu marker na aktualna strone
     *
     * @param type $sUriVariable
     * @return \f_paging
     */
    public function uriVar($sUriVariable = null)
    {
        if (func_num_args() == 0) {
            return $this->_uriVar;
        }

        $this->_uriVar = $sUriVariable;
        return $this;
    }

    /*  Parametry uzytkownika */

    public function param($asKey, $sValue = null)
    {
        if (is_array($asKey)) {
            foreach ($asKey as $k => $v) {
                if (is_int($k)) {
                    $this->_param[] = $v;
                }
                else {
                    $this->_param[$k] = $v;
                }
            }
            return $this;
        }
        if ($sValue === null) {
            return $this->_param[$asKey];
        }
        $this->_param[$asKey] = $sValue;
        return $this;
    }

    public function isParam($sKey)
    {
        return isset($this->_param[$sKey]);
    }

    public function removeParam($asKey = null)
    {
        if ($asKey === null) {
            $this->_param = array();
        }
        else {
            if (!is_array($asKey)) {
                $asKey = array($asKey);
            }
            foreach ($asKey as $i) {
                unset($this->_param[$i]);
            }
        }
        return $this;
    }

}