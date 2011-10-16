<?php

class f_m implements IteratorAggregate
{

    /**
     * Wynik zapytania (rokordy, rekord, krotki danych, wartosc pola lub falsz)
     *
     * @var mixed
     */
    public $_;

    protected static $_metadata      = array();
    protected static $_configPackage = array(
        'paging' => 'f_paging', // f_paging_interface
        'prefix' => 'm_',       // class prefix for models
    );
    
    protected $_table;
    protected $_key;
    protected $_field;
    protected $_fieldBack;
    protected $_select   = array();
    protected $_join     = array();
    protected $_param    = array();
    protected $_linkage  = array();
    protected $_result;
    protected $_valid;
    protected $_error;

    /**
     * Tworzy nowy obiekt klasy modelu (PHP >= 5.3)
     *
     * @return $this
     */
    public static function _(array $config = array())
    {
        $class = get_called_class();
        return new $class($config);
    }

    /**
     * Ustala tabele, pola, klucz glowny modelu i nazwe serwisu bazy danych
     */
    public function __construct(array $config = array())
    {
        $this->_class = $class = get_class($this);

        if (! isset(self::$_metadata[$class])) {
            
            $part = explode('_', substr($class, strlen(self::$_configPackage['prefix'])));

            $reflection = new ReflectionClass($this);
            foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
                $name = $property->getName();
                if ($name[0] != '_') {
                    self::$_metadata[$class]['field'][] = $name;
                }
            }

            if (count($part) == 1) {
                self::$_metadata[$class]['prefix'] = 'm_';
                self::$_metadata[$class]['db']     = 'db';
                self::$_metadata[$class]['table']  = $part[0];
                $key = $part[0] . '_id';
                self::$_metadata[$class]['key'] = in_array($key, self::$_metadata[$class]['field']) ? $key : null;
            }
            else {
                self::$_metadata[$class]['prefix'] = 'm_' . $part[1] . '_';
                self::$_metadata[$class]['db']     = 'db_' . $part[1];
                self::$_metadata[$class]['table']  = $part[1];
                $key = $part[1] . '_id';
                self::$_metadata[$class]['key'] = in_array($key, self::$_metadata[$class]['field']) ? $key : null;
            }
        }

        $this->_table = self::$_metadata[$class]['table'];
        $this->_field = self::$_metadata[$class]['field'];
        $this->_key   = self::$_metadata[$class]['key'];

        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    /**
     *
     * @param string _db, _paging lub model dependent
     * @return object
     */
    public function __get($key)
    {
        switch ($key) {
            
            case '_db':
                $service   = self::$_metadata[$this->_class]['db'];
                $this->_db = f::$c->{$service};
                return $this->_db;
                
            case '_paging':
                $paging        = self::$_configPackage['paging'];
                $this->_paging = new $paging();
                return $this->_paging;
                        
            default:
                return $this->{$key} = $this->_modelLinked($key);
                
        }
    }

    /**
     * Implementacja interfejsu IteratorAggregate
     * @return ArrayIterator
     */
    public function getIterator()
    {
        if (! is_array($this->_)) {
            $this->selectAll();
        }
        return new ArrayIterator($this->_);
    }

    /* setup */
    
    /**
     * Ustala lub pobiera nazwe tabeli w zależności od parametru
     *
     * @param string|null $sTable Nazwa tabeli
     * @return string|$this Nazwa tabeli
     */
    public function table($sTable = null)
    {
        if (func_num_args() == 0) {
            return $this->_table;
        }
        else {
            $this->_table = $sTable;
            return $this;
        }
    }

    /**
     * Ustala lub pobiera nazwe klucza podstawowego
     *
     * @param string|null $sPrimaryKey Nazwa klucza podstawowego
     * @return string|$this Nazwa klucza podstawowego
     */
    public function key($sPrimaryKey = null)
    {
        if (func_num_args() == 0) {
            return $this->_key;
        }
        else {
            $this->_key = $sPrimaryKey;
            return $this;
        }
    }

    /**
     * Ustala lub pobiera pola modelu
     *
     * @param array|boolean|string|null $asField Pola jako tablica lub string gdzie pola oddzielone są znakiem spacji,
     *      true resetuje pola, false czysci pola (to samo co array())
     * @return array|$this Pola
     */
    public function field($absField = null)
    {
        if (func_num_args() == 0) {
            return $this->_field;
        }

        $this->_fieldBack = $this->_field;
        
        if (is_string($absField)) {
            $this->_field = explode(' ', $absField);
        }
        else if (is_array($absField)) {
            $this->_field = $absField;
        }
        else if ($absField === false) {
            $this->_field = array();
        }
        else if ($absField === true) {
            $this->_field = $this->_m[$this->_config][$this->_table]['field'];
        }
        else {
            throw new f_m_exception(array(
                'type' => f_m_exception::INVALID_ARGUMENT,
                'msg'  => "Oczekiwany argument typu: string, array lub boolean lub brak",
            ));
        }
        return $this;
    }

    /**
     * Dodaje pole lub pola
     *
     * @param array|string $asField Pola jako tablica lub string gdzie pola oddzielone są znakiem spacji
     * @return array|$this
     */
    public function addField($asField)
    {
        $this->_fieldBack = $this->_field;
        
        foreach (is_array($asField) ? $asField : explode(' ', $asField) as $k => $v ) {
            if (is_int($k)) {
                $this->_field[] = $v;
            }
            else {
                $this->_field[$k] = $v;
            }
        }
        
        return $this;
    }

    /**
     * Usuwa pole lub pola
     * 
     * @param array|string $asField Pola jako tablica lub string gdzie pola oddzielone sa znakiem spacji
     * @return $this
     */
    public function removeField($asField)
    {
        $this->_fieldBack = $this->_field;

        foreach (is_array($asField) ? $asField : explode(' ', $asField) as $i) {
            $aRemove[$i] = true;
        }
        foreach ($this->_field as $k => $v) {
            if (isset ($aRemove[$v])) {
                unset($this->_field[$k]);
            }
        }
        
        return $this;
    }

    /* values */
    
    /**
     * Ustawia lub pobiera wartosci modelu, nie zmienia wartosci klucza glownego
     *
     * @param array|null $aKeyValue Wartości jako tablica asocjacyjna
     * @param array|string $asRestrictionField Pola w ktorych maja byc ustawione wartosci
     * @return array|$this Wartości jako tablica asocjacyjna
     */
    public function val($aKeyValue = null, $asRestrictionField = null)
    {
        if (func_num_args() == 0) {
            foreach ($this->_field as $i) {
                $a[$i] = $this->{$i};
            }
            return $a;
        }
        else if ($asRestrictionField === null) {
            foreach ($this->_field as $i) {
                if (isset($aKeyValue[$i]) && $i != $this->_key) {
                    $this->{$i} = $aKeyValue[$i];
                }
            }
        }
        else {
            if (! is_array($asRestrictionField)) {
                $asRestrictionField = explode(' ', $asRestrictionField);
            }
            foreach ($this->_field as $i) {
                if (isset($aKeyValue[$i]) && $i != $this->_key && in_array($asRestrictionField, $i)) {
                    $this->{$i} = $aKeyValue[$i];
                }
            }
        }
        return $this;
    }

    /**
     * Ustala pola modelu i ustawia wartości modelu, nie zmienia wartosci klucza glownego
     *
     * @param array $aKeyValue Pola jako klucze $aKeyValue, wartosci modelu jako wartosci $aKeyValue
     * @return $this
     */
    public function valField($aKeyValue)
    {
        $this->field(array_keys($aKeyValue));
        $this->val($aKeyValue);
        return $this;
    }

    /**
     * Czysci wartosci modelu
     */
    public function removeVal()
    {
        return;
        $this->_ = null;
        foreach (self::$_metadata[$this->_class]['field'] as $field) {
            $this->{$field} = null;
        }
        /** @todo wyczyscic modele depend */
    }

    /**
     * Ustala lub pobiera wartość dla klucza glownego
     *
     * @param string|null $sValue Id
     * @return string|$this Id
     */
    public function id($sValue = null)
    {
        if (func_num_args()) {
            return $this->{$this->_key};
        }
        else {
            $this->{$this->_key} = $sValue;
            return $this;
        }
    }
    
    /* params for queries */
    
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
        if ($sKey === null) {
            $this->_param = array();
        }
        else {
            if (! is_array($asKey)) {
                $asKey = array($asKey);
            }
            foreach ($asKey as $i) {
                unset ($this->_param[$i]);
            }
        }
        return $this;
    }

    /* queries */
    
    /**
     * Selekcjonuje jeden rekord z tabeli, zapisuje go do pola _, zapisuje pobrane dane do pol publicznych obiektu
     *
     * @param array|integer|string $aisParam Parametry
     * @return $this
     */
    public function select($aisParam = null)
    {
        if ($aisParam !== null && $data = $this->_db->row($this->_sql($aisParam, true, true, true))) {
            $this->val($data);
            $this->_ = $data;
            if ($this->_key !== null && isset($data[$this->_key])) {
                $this->{$this->_key} = $data[$this->_key];
            }
        }
        else {
            $this->removeVal();
        }
        return $this;
    }

    /**
     * Selekcjonuje wiele rekordow z tabeli, zaisuje je do _, nie zapisuje pobranych danych do pol publicznych obiektu
     *
     * @param array|integer|string|null $aisParam Parametry
     * @return $this
     */
    public function selectAll($aisParam = null)
    {
        $this->_ = $this->_db->rows($this->_sql($aisParam, true, true, true));
        return $this;
    }

    /**
     * Zwraca jedno wymiarową tablice numeryczną gdzie wartościami tablicy jest pierwsze pole z wyselekcjonowanych rekordow
     *
     * @param array|integer|string|null $aisParam Parametry
     * @return array|false Tablice | W przpadku nie wyselekcjonowania żadnych rekordow
     */
    public function selectCol($aisParam = null)
    {
        $this->_ = $this->_db->col($this->_sql($aisParam, true, true, true));
        return $this;
    }

    /**
     * Pobiera jedno wymiarową tablice asocjacyjną gdzie kluczem jest pierwsze pole a wartością drugie z wyselekcjonowanych rekordow
     *
     * @param array|integer|string|null $aisParam Parametry
     * @return $this
     */
    public function selectCols($aisParam = null)
    {
        $this->_ = $this->_db->cols($this->_sql($aisParam, true, true, true));
        return $this;
    }

    /**
     * Pobiera wartosc pierwszego pola z pierwszego wyselekcjonowanego rekordu, zapisuje ja do _
     *
     * @param array|integer|string|null $aisParam Parametry
     * @return $this
     */
    public function selectVal($aisParam = null)
    {
        $this->_ = $this->_db->val($this->_sql($aisParam, true, true, true));
        return $this;
    }

    /**
     * Wykonuje zapytanie SELECT COUNT, wartosc zapytania zapisuje do pola _
     *
     * @param array|integer|string|null $aisParam Parametry
     * @param string $sExpr Domyślnie *
     * @return $this
     */
    public function selectCount($aisParam = null, $sExpr = '*')
    {
        $this->_ = $this->_db->one("SELECT COUNT($sExpr)".$this->_sql($aisParam, false, true, true));
        return $this;
    }

    /**
     * Selekcjonuje rekordy, ktore pobierane sa metoda next()
     *
     * @param array|integer|string|null $aisParam Parametry
     * @return $this
     */
    public function selectLoop($aisParam = null)
    {
        $this->_result = $this->_db->query($this->_sql($aisParam, true, true, true));
        return $this;
    }

    /**
     * Pobiera kolejny rekod zapytania wykonanego przez metode selectLoop()
     *
     * @return $this
     */
    public function selectNext()
    {
        if (($data = $this->_db->fetch($this->_result))) {
            $this->val($data);
            $this->_ = $data;
            if ($this->_key !== null && isset($data[$this->_key])) {
                $this->{$this->_key} = $data[$this->_key];
            }
        }
        else {
            $this->removeVal();
        }
        return $this;
    }

    /**
     * Pobiera do modelu ostatnio dodany rekorod do bazy
     *
     * @return $tthis
     */
    public function selectLast()
    {
        $row = $this->_db->row("SELECT * FROM `$this->_table` WHERE `$this->_key` = LAST_INSERT_ID()");
        $this->val($row);
        $this->{$this->_key} = $aRow[$this->_key];
        return $this;
    }

    /**
     * Dodaje rekord do tabeli
     *
     * Nieprawidłowe klucze w tablicy są pomijane, musi sie zgadzac kolejność i ilość
     *
     * @param array $aData Tablica jedno wymiarowa asocjacyjna
     * @return f_m
     */
    public function insert($aData = null)
    {
        
        if (func_num_args()) {
            foreach ($aData as $k => $v) {
                if (! is_int($k) && ! in_array($k, $this->_field)) {
                    unset($aData[$k]);
                }
            }
        }
        else {
            $aData = $this->val();
        }

        $aSet = array();
        foreach ($aData as $k => $v) {
            $aSet[$k] = "`$k` = '{$this->_db->escape($v)}'";
        }
        if ($this->_linkage) {
            foreach ($this->_linkage as $k => $v) {
                $aSet[$k] = "`$k` = '{$this->_db->escape($v)}'";
            }
        }
        
        $this->_db->query("INSERT INTO `{$this->_table}` SET " . implode(', ', $aSet));

        return $this;
    }

    /**
     * Dodaje rekordy do tabeli
     *
     * Nieprawidlowe klucze w tablicy są pomijane, musi sie zgadzac kolejnosc i ilosc
     *
     * @param array $aData Tablica dwuwymiarowa gdzie drugi wymiar to tablica asocjacyjna
     * @return f_m
     */
    public function insertAll($aData = null)
    {
        if (func_num_args() == 0) {
            $aData = $this->_;
        }
        
        $aModelField = array();
        foreach ($this->_field as $i) {
            $aModelField[$i] = true;
        }

        $aField  = array();
        $aValues = array();
        foreach (current($aData) as $k => $v) {
            if (isset($aModelField[$k])) {
                $aField[$k] = true;
            }
        }
        if ($this->_linkage) {
            $aLinkage = $this->_linkage;
            foreach ($aLinkage as $k => $v) {
                $aField[$k] = true;
                $aLinkage[$k] = $this->_db->escape($v);
            }
        }

        foreach ($aData as $data) {
            $aRow = array();
            foreach ($aField as $field => $true) {
                $aRow[$field] = "'{$this->_db->escape($data[$field])}'";
            }
            if ($this->_linkage) {
                foreach ($aLinkage as $k => $v) {
                    $aRow[$k] = "'$v'";
                }
            }
            $aValues[] = "(".implode(', ', $aRow).")";
        }

        $this->_db->query("INSERT INTO `{$this->_table}` (`".implode('`, `', array_keys($aField))."`) VALUES ".implode(', ', $aValues));

        return $this;
    }

    /**
     * Modyfikuje rekord lub rekordy
     *
     * @param array $aData Tablica jedno wymiarowa asocjacyjna
     * @param array|integer|string|null $aisParam Parametry
     * @return $this
     */
    public function update($aData = null, $aisParam = null)
    {
        if ($aisParam === null) {
            if ($this->{$this->_key} !== null) {
                $aisParam = $this->{$this->_key};
            }
            else {
                throw new LogicException("Oczekiwany warunek modyfikowania rekordow.");
                return;
            }
        }

        if ($aData) {
            foreach ($aData as $k => $v) {
                if (! is_int($k) && ! in_array($k, $this->_field)) {
                    unset($aData[$k]);
                }
            }
        }
        else {
            $aData = $this->val();
        }
        
        $aSet = array();
        foreach ($aData as $k => $v) {
            if (is_int($k)) {
                $aSet[] = $v;
            }
            else {
                $aSet[$k] = "`$k` = '{$this->_db->escape($v)}'";
            }
        }
        if ($this->_linkage) {
            foreach ($this->_linkage as $k => $v) {
                $aSet[$k] = "`$k` = '{$this->_db->escape($v)}'";
            }
        }

        $this->_db->query("UPDATE `{$this->_table}` SET " . implode(", ", $aSet) . $this->_sql($aisParam, false, false, true));
        return $this;
    }

    /**
     * Modyfikuje wszystkie rekordy w tabeli
     *
     * @param array $aData Tablica jedno wymiarowa asocjacyjna
     * @return $this
     */
    public function updateAll($aData = null)
    {
        $this->update($aData, array('1'));
        return $this;
    }

    /**
     * Usuwa rekord lub rekordy
     * Gdy brak jakiegokolwiek warunku zapytanie DELETE nie zostanie wykonane, aby usunąć wszystkie rekordy użyj metody deleteAll
     *
     * @param array|integer|string|null $aisParam Parametry
     * @return int 0 - sucess, 1 - Bład zapytania DELETE, <2,n> - Błąd w metodzie przeciązającej tą metode;
     */
    public function delete($aisParam = null)
    {
        if ($aisParam === null) {
            if ($this->{$this->_key} !== null) {
                $aisParam = $this->{$this->_key};
            }
            else {
                throw new LogicException("Oczekiwany warunek kasacji rekordow");
                return;
            }
        }
        $this->_db->query("DELETE FROM `{$this->_table}`".$this->_sql($aisParam, false, false, true));
        return $this;
    }

    public function deleteAll()
    {
        $this->delete(array('1'));
        return $this;
    }

    /**
     * Zapisuje rekord do bazy, wkonuje zapytanie INSERT jesli wartosc klucza podstawowego jest rowna null, lub UPDATE w przeciwnym wypadku
     *
     * @param array $aData Tablica jedno wymiarowa asocjacyjna
     * @param integer|null $iId Id rekordu
     * @return $this
     */
    public function save($aData = null, $iId = null)
    {
        if ($aData !== null) {
            $this->val($aData);
        }
        if ($iId !== null) {
            $this->{$this->_key} = $iId;
        }

        if ($this->{$this->_key} === null) {
            $this->insert();
        }
        else {
            $this->update();
        }
        
        return $this;
    }

    /**
     * Wykonuje zapytanie SELECT COUNT, wartosc zapytania zapisuje do pola _
     *
     * @param array|integer|string|null $aisParam Parametry
     * @param string $sExpr Domyślnie *
     * @return $this
     */
    public function count($aisParam = null, $sExpr = '*')
    {
        $this->selectCount($aisParam, $sExpr);
        return $this->_;
    }

    /* join */

    /**
     * Wykonuje JOIN dołączenie do tabeli według referencji
     *
     * @param string $asRefName Nazwa referencji
     * @param array|string $asField Pola jako tablica lub string gdzie pola są oddzielone znakiem spacji
     * @param string $asModel Nazwa modelu
     * @return $this
     */
    public function join($asRefName, $asField = null, $asModel = null)
    {
        $this->_join($asRefName, $asField, $asModel, 'JOIN');
        return $this;
    }

    /**
     * Wykonuje LEFT JOIN dołączenie do tabeli według referencji
     *
     * @param string $asRefName Nazwa referencji
     * @param array|string $asField Pola jako tablica lub string gdzie pola są oddzielone znakiem spacji
     * @param string $asModel Nazwa modelu
     * @return $this
     */
    public function joinLeft($asRefName, $asField = null, $asModel = null)
    {
        $this->_join($asRefName, $asField, $asModel, 'LEFT JOIN');
        return $this;
    }

    /* additional */
    
    public function paging($aConfig = array())
    {
        if (! isset($aConfig['all']) && ! isset($this->_paging->all)) {
            $aConfig['all'] = $this->count();
        }

        foreach ($aConfig as $k => $v) {
            $this->_paging->{$k} = $v;
        }

        $this->_paging->paging();

        $this->param(array(
            'limit'  => $this->_paging->limit,
            'offset' => $this->_paging->offset,
        ));

        return $this;
    }


    /* private api */
    
    /**
     * @todo napisac opis
     * @param <type> $isKey
     * @param <type> $sValue 
     */
    public function modelLinkage($isKey, $sValue)
    {
        $this->_linkage[$isKey] = $sValue;
    }

    
    
    /* */
    
    /**
     * @friend f_m
     */
    public function modelRel()
    {
        $this->_rel();
    }

    /**
     * Buduje zapytanie SQL
     *
     * @param array|integer|null|string $aisParam Parametry
     * @param boolean $bSelect Czy budowac fragment SELECT
     * @param boolean $bFrom Czy budowac fragment FROM
     * @param boolean $bLinkage Czy budowac fragment powiazania
     * @return string Zapytanie SQL lub fragment zapytania
     */
    protected function _sql($aisParam, $bSelect, $bFrom, $bLinkage)
    {
        $select  = null;
        $from    = null;
        $where   = null;
        $groupby = null;
        $having  = null;
        $orderby = null;
        $limit   = null;
        $offset  = null;

        $aField  = $this->_field;

        // where, group by, having, order by, offset, limit, operator, fields
        if (! is_array($aisParam)) {
            $aisParam = $aisParam === null ? array() : array("`{$this->_key}` = '{$this->_db->escape($aisParam)}'");
        }
        /** @? po co to? */
//        if ($bDefaultParam) {
            $aisParam = array_merge($this->_param, $aisParam);
//        }
        $aWhere   = array();
        foreach ($aisParam as $key => $value) {

            if (is_int($key)) {
                $aWhere[] = $value;
                continue;
            }

            $type = '=';
            switch ($key) {
                case 'field'    : $aField   = is_array($value) ? $value : explode(' ', $value); break;
                case 'operator' : $operator = $value;                                           break;
                case 'where'    : $aWhere[] = $value;                                           break;
                case 'group'    :
                case 'group by' : $groupby  = ' GROUP BY ' . $value; break;
                case 'having'   : $having   = ' HAVING '   . $value; break;
                case 'order'    :
                case 'order by' : $orderby  = ' ORDER BY ' . $value; break;
                case 'offset'   : $offset   = ' OFFSET '   . $value; break;
                case 'limit'    : $limit    = ' LIMIT '    . $value; break;
                case 'paging'   : $offset   = ' OFFSET '   . $value->offset;
                                  $limit    = ' LIMIT '    . $value->limit; break;
                default:
                    if (strpos($key, '|') !== false) {
                        list ($key, $type) = explode('|', $key, 2);
                    }
                    if  (is_array($value) && $type === '=') {
                        $type = 'IN';
                    }
                    switch ($type) {
                        case 'BETWEEN' : case 'NOT BETWEEN' :
                            $aWhere[] = "`$key` $type '{$this->_db->escape($value[0])}' AND '{$this->_db->escape($value[1])}'";
                            break;
                        case 'IN' : case 'NOT IN' :
                            foreach ($value as $k => $v) {
                                $value[$k] = $this->_db->escape($v);
                            }
                            $aWhere[] = "`$key` $type ('".implode("','", $value)."')";
                            break;
                        default:
                            $aWhere[] = "`$key` $type '{$this->_db->escape($value)}'";
                            break;
                    }
            }
        }
        $operator = isset($operator) ? (isset($operator[0]) ? ' ' . $operator . ' ' : ' ') : ' AND ' ;
        if ($aWhere) {
            $where = " WHERE ".implode($operator, $aWhere);
        }

        if ($this->_linkage && $bLinkage) {
            $aLinkage = array();
            foreach ($this->_linkage as $k => $v) {
                $aLinkage[$k] = "`$k` = '{$this->_db->escape($v)}'";
            }

            $where = (empty($where) ? ' WHERE ' : substr($where, 0, 7) . '(' . substr($where, 7) . ') AND ')
                . implode(" AND ", $aLinkage);
        }

        // select
        if ($bSelect) {
            $aSelect = array();
            foreach ($aField as $k => $v) {
                $aSelect[] =  "`$v`" . (is_int($k) ? '' : ' as ' . $k);
            }
            $select = 'SELECT '.implode(', ', array_merge($aSelect, $this->_select));
        }

        // from
        if ($bFrom) {
            $from   = " FROM `{$this->_table}`" . ($this->_join ? ' ' . implode(' ', $this->_join) : '');
        }

        return $select . $from . $where . $groupby . $having . $orderby . $limit . $offset;
    }


    protected function _rel()
    {

    }

    protected function _rel11($sRefModel, $sField = null, $sRelatedField = null)
    {
        $field = $sField !== null ? $sField : "{$this->_table}_id";

        if ($sRelatedField !== null) {
            list($relTable) = explode('_', $sRelatedField, 2);
            $relField       = $sRelatedField;
        }
        else {
            $relTable = $sRefModel;
            $relField = "{$sRefModel}_id";
        }

        self::$_metadata[$this->_class]['ref'][$sRefModel] = array(
            'field'    => $field,
            'relTable' => $relTable,
            'relField' => $relField,
        );
    }
    
    protected function _relN1($sRefModel, $sField = null, $sRelatedField = null)
    {
        $field = $sField !== null ? $sField : "{$this->_table}_id_$sRefModel";

        if ($sRelatedField !== null) {
            list($relTable) = explode('_', $sRelatedField, 2);
            $relField       = $sRelatedField;
        }
        else if (strpos($sRefModel, '_') === false) {
            $relTable = $sRefModel;
            $relField = "{$sRefModel}_id";
        }
        else {
            list($model) = explode('_', $sRefModel, 2);
            $relTable = $model;
            $relField = "{$model}_id";
        }
        
        self::$_metadata[$this->_class]['ref'][$sRefModel] = array(
            'field'    => $field,
            'relTable' => $relTable,
            'relField' => $relField,
        );
    }

    protected function _rel1N($sDepModel, $sField = null, $sRelatedField = null)
    {
        $field = $sField !== null ? $sField : "{$this->_table}_id";

        if ($sRelatedField !== null) {
            list($relTable) = explode('_', $sRelatedField, 2);
            $relField       = $sRelatedField;
        }
        else if (strpos($sDepModel, '_') === false) {
            $relTable = $sDepModel;
            $relField = "{$sDepModel}_id_{$this->_table}";
        }
        else {
            list($model, $option) = explode('_', $sDepModel, 2);
            $relTable = $model;
            $relField = "{$model}_id_{$this->_table}_{$option}";
        }

        self::$_metadata[$this->_class]['dep'][$sDepModel] = array(
            'field'    => $field,
            'relTable' => $relTable,
            'relField' => $relField,
        );
    }

    private function _modelDependent($sDependent)
    {
        if (!isset(self::$_metadata[$this->_class]['ref'])) {
            $this->_rel();
            if (! isset (self::$_metadata[$class]['ref'])) {
                self::$_metadata[$class]['ref'] = array();
            }
            if (! isset (self::$_metadata[$class]['dep'])) {
                self::$_metadata[$class]['dep'] = array();
            }
        }

        if (isset(self::$_metadata[$this->_class]['dep'][$sDependent])) {
            $rel = self::$_metadata[$this->_class]['dep'][$sDependent];
        }
        else if (isset(self::$_metadata[$this->_class]['ref'][$sDependent])) {
            $rel = self::$_metadata[$this->_class]['dep'][$sDependent] = self::$_metadata[$this->_class]['ref'][$sDependent];
        }
        else {
            throw new Exception("Odwolanie do nieistniejacej relacji o nazwie $sDependent w modelu $this->_class");
            return;
        }

        $class  = self::$_metadata[$this->_class]['prefix'].$rel['relTable'];
        $oModel = new $class;
        $oModel->modelLinkage($rel['relField'], $this->{$rel['field']});
        $oModel->{$rel['relField']} = $this->{$rel['field']};

        return $oModel;
    }

    private function _join($asRefName, $asField, $asModel, $sType)
    {
        if (is_array($asRefName)) {
            $aliasJoin = key($asRefName);
            $asRefName = current($asRefName);
        }
        if (is_array($asModel)) {
            $aliasModel = key($asModel);
            $asModel    = current($asModel);
        }

        if ($asModel == null) {
            $class = $this->_class;
            $model = self::$_metadata[$this->_class]['table'];
        }
        else {
            $class = self::$_metadata[$this->_class]['prefix'] . $asModel;
            $model = $asModel;
        }

        if (!isset(self::$_metadata[$class]['ref'])) {
            if ($asModel == null) {
                $this->_rel();
            }
            else {
                $o = new $class;
                $o->modelRel();
            }
            if (! isset (self::$_metadata[$class]['ref'])) {
                self::$_metadata[$class]['ref'] = array();
            }
            if (! isset (self::$_metadata[$class]['dep'])) {
                self::$_metadata[$class]['dep'] = array();
            }
        }

        if (isset(self::$_metadata[$class]['ref'][$asRefName])) {
            $rel = self::$_metadata[$class]['ref'][$asRefName];
        }
        else if (isset(self::$_metadata[$class]['dep'][$asRefName])) {
            $rel = self::$_metadata[$class]['ref'][$asRefName] = self::$_metadata[$class]['dep'][$asRefName];
        }
        else {
            throw new Exception("Odwolanie do nieistniejacej relacji o nazwie $asRefName w modelu $this->_class");
            return;
        }

        if ($asField !== false) {
            if ($asField === null) {
                $classJoin = self::$_metadata[$this->_class]['prefix'] . $rel['relTable'];
                if (! isset (self::$_metadata[$classJoin])) {
                    new $classJoin();
                }
                $asField = self::$_metadata[$classJoin]['field'];
            }
            else if (is_string($asField)){
                $asField = explode(' ', $asField);
            }
            foreach ($asField as $k => $v) {
                $this->_select[] = ($aliasJoin === null ? '' : "`$aliasJoin`" . '.') . (is_int($k) ?   ($aliasJoin === null ? "`$v`" : "`{$v}` as `{$aliasJoin}_{$v}`") : "`$v` as $k");
            }
        }
        $this->_join[] =  $sType. ' `' . $rel['relTable'] . '`' . ($aliasJoin === null ? '' : " as `$aliasJoin`")
            . ' ON (' . ($aliasModel === null ? '' : "`$aliasModel`.") . "`{$rel['field']}`"
            . ' = ' . ($aliasJoin === null ? '' : "`$aliasJoin`.") . "`{$rel['relField']}`" . ')';

    }
    
}