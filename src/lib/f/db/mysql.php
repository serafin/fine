<?php

class f_db_mysql
{

    protected $_connect;
    protected $_query;
    protected $_result;

    public function connect($sHostname, $sUsername, $sPassword)
    {
        if (($this->_connect = mysql_connect($sHostname, $sUsername, $sPassword, true))) {
            return $this;
        }
        throw new f_db_exception_connection($this->errorMsg(), $this->errorNo());
    }

    public function selectDb($sDatabaseName)
    {
        if (mysql_select_db($sDatabaseName, $this->_connect)) {
            return $this;
        }
        throw new f_db_exception_connection($this->errorMsg(), $this->errorNo());
    }

    public function link($rConnectionLinkIdentifier = null)
    {
        if (func_num_args ()) {
            $this->_connect = $rConnectionLinkIdentifier;
            return $this;
        }
        return $this->_connect;
    }

    /**
     * Dodaje znaki unikowe dla potrzeb poleceń SQL, biorąc po uwagę zestaw znakow używany w połączeniu
     *
     * @param $sString string
     * @return string
     */
    public function escape($sString)
    {
        return mysql_real_escape_string($sString, $this->_connect);
    }

    public function result()
    {
        return $this->_result;
    }
    
    /**
     * Wykonuje zapytanie
     *
     * Zwraca
     *  - zasob zapytania lub falsza dla SELECT, SHOW, EXPLAIN i DESCRIBE;
     *  - true lub false dla UPDATE, DELETE...
     *
     * @param string $sQuery Zapytanie SQL
     * @return resource
     */
    public function query($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            return $this->_result;
        }
        throw $this->_exceptionQuery();
    }

    /**
     * Zwraca wyselekcjonowany rekord jako tablice asocjacyjną
     *
     * @param string $sQuery Zapytanie SQL
     * @return array|false
     */
    public function row($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            return mysql_fetch_assoc($this->_result);
        }
        throw $this->_exceptionQuery();
    }

    /**
     * Zwraca wyselekcjonowane rekordy jako dwu wymiarową tablice, gdzie tablice wymiaru 2 jest asocjacyjna
     *
     * @param string $sQuery Zapytanie SQL
     * @return array|false
     */
    public function rows($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            $a = array();
            while ($i = mysql_fetch_assoc($this->_result)) {
                $a[] = $i;
            }
            return $a;
        }
        throw $this->_exceptionQuery();
    }


    /**
     * Zwraca jedno wymiarową tablice numeryczną
     * gdzie wartością pola tablicy jest pierwsze pole z wyselekcjonowanych rekordow
     *
     * @param string $sQuery Zapytanie SQL
     * @return array|false
     */
    public function col($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            $a = array();
            while ($i = mysql_fetch_row($this->_result)) {
                $a[] = $i[0];
            }
            return $a;
        }
        throw $this->_exceptionQuery();
    }

    /**
     * Zwraca jedno wymiarową tablice asocjacyjną gdzie kluczem jest pierwsze pole a wartością drugie z wyselekcjonowanych rekordow
     *
     * @param string $sQuery Zapytanie SQL
     * @return array|false
     */
    public function cols($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            $a = array();
            while ($i = mysql_fetch_row($this->_result)) {
                $a[$i[0]] = $i[1];
            }
            return $a;
        }
        throw $this->_exceptionQuery();
    }

    /**
     * Zwraca wartosc pierwszego pola z pierwszego wyselekcjonowanego rekordu
     *
     * @param string $sQuery Zapytanie SQL
     * @return string
     */
    public function val($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            if (($a = mysql_fetch_row($this->_result))) {
                return $a[0];
            }
            return null;
        }
        throw $this->_exceptionQuery();
    }
    
    /**
     * Zwraca wyselekcjonowany rekord jako tablice zwykłą (numeryczną)
     *
     * @param string $sQuery Zapytanie SQL
     * @return array
     */
    public function rowNum($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            return mysql_fetch_row($this->_result);
        }
        throw $this->_exceptionQuery();
    }

    /**
     * Zwraca wyselekcjonowane rekordy jako dwu wymiarową tablice zwykłą (numeryczną)
     *
     * @param string $sQuery Zapytanie SQL
     * @return array|flase Tablica lub falsz
     */
    public function rowsNum($sQuery)
    {
        $this->_query = $sQuery;
        if (($this->_result = mysql_query($sQuery, $this->_connect))) {
            $a = array();
            while ($i = mysql_fetch_row($this->_result)) {
                $a[] = $i;
            }
            return $a;
        }
        throw $this->_exceptionQuery();
    }

    /**
     * Zwraca wartosc klucza głownego ostatnio dodanego rekordu
     *
     * @return int Wartosc klucza ostatnio dodanego rekordu
     */
    public function lastInsertId()
    {
        return $this->val("SELECT LAST_INSERT_ID()");
    }

    public function lastQuery()
    {
        return $this->_query;
    }

    /**
     * Zwraca tablicę asocjacyjną zawierającą pobrany wiersz, lub FALSE jeżeli nie ma więcej wierszy w wyniku.
     *
     * @return array|false
     */
    public function fetch()
    {
        return mysql_fetch_assoc($this->_result);
    }

    public function fetchUsingResult($rQueryResult)
    {
        return mysql_fetch_assoc($rQueryResult);
    }

    /**
     * Zwraca tablicę zwykłą (numeryczną) zawierającą pobrany wiersz, lub FALSE jeżeli nie ma więcej wierszy w wyniku.
     *
     * @return array|false
     */
    public function fetchNum()
    {
        return mysql_fetch_row($this->_result);
    }

    public function fetchNumUsingResult($rQueryResult)
    {
        return mysql_fetch_row($rQueryResult);
    }

    /**
     * Zwraca liczbę wierszy w wyniku
     *
     * @return int|false
     */
    public function countSelected()
    {
        return mysql_num_rows($this->_result);
    }

    /**
     * Zwraca liczbę wierszy w wyniku
     *
     * @return int|false
     */
    public function countSelectedUsingResult($rQueryResult)
    {
        return mysql_num_rows($rQueryResult);
    }

    /**
     * Zwraca liczbe zmodyfikowanych wierszy
     *
     * @return int
     */
    public function countAffected()
    {
        return mysql_affected_rows($this->_connect);
    }

    /**
     * Zamyka połączenie z serwerem MySQL
     *
     * @return boolean
     */
    public function close()
    {
        if ($this->_connect) {
            mysql_close($this->_connect);
            $this->_connect = null;
            return true;
        }
        return false;
    }

    public function errorMsg()
    {
        return mysql_error($this->_connect);
    }

    public function errorNo()
    {
        return mysql_errno($this->_connect);
    }

    /**
     * Buduje bezpieczne zapytanie SQL metoda "zaslepek"
     *
     * @param string $sQuery Zapytanie z "zaslepkami"
     * @param array $aArgs zmienne do przeparsowania
     * @return string Zapytanie SQL
     *
     */
    public function prepare($sQuery, $asVar)
    {
        if (!is_array($asVar)) {
            $asVar = array($asVar);
        }
        $offest = 0;
        $query  = $sQuery;
        foreach ($asVar as $i) {
            if (false !== $pos = strpos($query, '?')) {
                $i      = $this->escape($i);
                $offest = $pos + strlen($i);
                $query = substr($query, 0, $pos) . $i . substr($query, $pos + 1);
            }
            else {
                throw new f_db_exception_invalidArgument(
                    "Too not enough \"?\" chars(" . substr_count($sQuery, "?") . ") in query"
                    . " and too many vars(" . count($asVar) . ") passed in second argument"
                );
            }
        }
        return $sQuery;
    }

    protected function _exceptionQuery()
    {
        $exception            = new f_db_exception_query($this->errorMsg(), $this->errorNo());
        $exception->Query     = $this->_query;
        $exception->_metadata = array('Query' => 'mysql');

        return $exception;
    }

}