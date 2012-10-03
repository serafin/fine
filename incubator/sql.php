<?php

class c_helper_sql
{

    const QUERY_SELECT = 'SELECT';
    const QUERY_INSERT = 'INSERT';
    const QUERY_UPDATE = 'UPDATE';
    const QUERY_DELETE = 'DELETE';

    protected $_query  = ''; // set
    protected $_select = array(); // set
    protected $_set    = array(); // se3t
    protected $_from   = ''; // set
    protected $_join   = array(); //aggregate
    protected $_alias  = '';  //set
    protected $_where  = array(); // set
    protected $_whereOperator = ' AND ';
    protected $_group  = array(); // set
    protected $_having = array(); // set
    protected $_order  = array(); // set
    protected $_limit  = array(); // set
    protected $_offset = array(); // set

    /**
     * Statyczny konstruktor
     *
     * @return c_helper_sql
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public static function build(array $param)
    {
        
        $sql = new self();

        // table
        if (isset($param['table'])) {
            $sql->table($param['table']);
            unset($param['table']);
        }

        // field
        if (isset($param['field'])) {
            $sql->table($param['field']);
            unset($param['field']);
        }

        // key
        if (isset($param['field'])) {
            $sql->table($param['field']);
            unset($param['field']);
        }

        

    }

    /**
     * Konstruktor 
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function helper(array $param)
    {
        return c_helper_sql::build($param);
    }

    /* setup */

    /**
     * Ustala/pobiera nazwe tabeli
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
     * Ustala/pobiera nazwe klucza podstawowego
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
            $this->_field = self::$_metadata[$class]['field'];
        }
        else {
            throw new BadMethodCallException("Oczekiwany argument typu: string, array lub boolean lub brak");
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

    /**
     * Przywraca ostatnia uzyta wartosc dla field
     */
    public function fieldBack()
    {
        if ($this->_fieldBack === null) {
            return $this;
        }

        $current          = $this->_field;
        $this->_field     = $this->_fieldBack;
        $this->_fieldBack = $current;

        return $this;
    }

    /* params for queries */

    public function param($asKey = null, $sValue = null)
    {
        switch (func_num_args()) {

            case 0:
                // get all params
                return $this->_param;

            case 1:
                // set params from array
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

                // get param by key
                return $this->_param[$asKey];

            case 2:
                // set param
                $this->_param[$asKey] = $sValue;
                return $this;
        }

    }

    public function paramId($isId = null)
    {
        if (func_num_args() == 0) {
            return $this->_param[$this->_key];
        }

        $this->_param[$this->_key] = $isId;
        return $this;

    }

    public function isParam($sKey)
    {
        return isset($this->_param[$sKey]);
    }

    public function removeParam($asKey = null)
    {
        if (func_num_args() == 0) {
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

    /**
     * Dodaje join
     *
     * @param string $sJoinFragment
     * @return $this
     */
    public function join($sJoinFragment)
    {
        $this->_param['join'][] = $sJoinFragment;
        return $this;
    }

    /**
     * Buduje zapytanie SQL
     *
     * @return string Zapytanie SQL lub fragment zapytania
     */
    protected function sql()
    {

        $select          = null;
        $from            = null;
        $where           = null;
        $groupby         = null;
        $having          = null;
        $orderby         = null;
        $limit           = null;
        $offset          = null;
        $field           = $this->_field;
        $aParam          = array_merge((array)$this->_param, (array)$aParam);
        $logicalOperator = ' AND ';

        foreach ($aParam as $paramKey => $paramValue) {

            if (is_int($paramKey)) {
                $where[] = $paramValue;
                continue;
            }

            switch ($paramKey) {

                case self::PARAM_FIELD:
                    $field = is_array($paramValue) ? $paramValue : explode(' ', $paramValue);
                    break;

                case self::PARAM_OPERATOR:
                    $logicalOperator = $paramValue;

                    if (strlen($logicalOperator) > 0) {
                        if (substr($logicalOperator, 0, 1) != ' ') {
                            $logicalOperator = ' '. $logicalOperator;
                        }
                        if (substr($logicalOperator, -1) != ' ') {
                            $logicalOperator .= ' ';
                        }
                    }

                    break;

                case self::PARAM_GROUP:
                    $groupby = ' GROUP BY ' . $paramValue;
                    break;

                case self::PARAM_HAVING:
                    $having = ' HAVING ' . $paramValue;
                    break;

                case self::PARAM_ORDER:
                    $orderby = ' ORDER BY ' . $paramValue;
                    break;

                case self::PARAM_OFFSET:
                    $offset = ' OFFSET ' . $paramValue;
                    break;

                case self::PARAM_LIMIT:
                    $limit = ' LIMIT ' . $paramValue;
                    break;

                case self::PARAM_PAGING:
                    $offset = ' OFFSET ' . $paramValue->offset();
                    $limit  = ' LIMIT ' . $paramValue->limit();
                    break;

                case 'join':
                    break;

                default:

                    // operator porownania
                    $comparisonOperator = '=';
                    if (strpos($paramKey, ' ') !== false) {
                        list ($paramKey, $comparisonOperator) = explode(' ', $paramKey, 2);
                    }
                    if  (is_array($paramValue) && $comparisonOperator === '=') {
                        $comparisonOperator = 'IN';
                    }

                    switch ($comparisonOperator) {

                        case 'BETWEEN':
                        case 'NOT BETWEEN':
                            $where[] = "`$paramKey` $comparisonOperator"
                                     . " '{$this->_db->escape($paramValue[0])}'"
                                     . " AND '{$this->_db->escape($paramValue[1])}'";
                            break;

                        case 'IN':
                        case 'NOT IN':
                            foreach ($paramValue as $k => $v) {
                                $paramValue[$k] = $this->_db->escape($v);
                            }
                            $where[] = "`$paramKey` $comparisonOperator ('" . implode("','", $paramValue) . "')";
                            break;

                        default:
                            $where[] = "`$paramKey` $comparisonOperator '{$this->_db->escape($paramValue)}'";
                            break;

                    }

            }

        }

        if ($where) {
            $where = " WHERE " . implode($logicalOperator, $where);
        }

        // linkage
        if ($bLinkage && $this->_hardlink) {

            $hardlink = array();
            foreach ($this->_hardlink as $k => $v) {
                if (is_int($k)) {
                    $hardlink[] = $v;
                }
                else {
                    $hardlink[] = "`$k` = '{$this->_db->escape($v)}'";
                }
            }
            $hardlink = implode(' AND ', $hardlink);

            $where = $where ? " WHERE ($hardlink) AND (" . substr($where, 7) . ")" : " WHERE $hardlink";

        }

        // select
        if ($bSelect) {

            // this model
            foreach ($field as $k => $v) {
                $select[] =  "`$v`" . (is_int($k) ? '' : ' as ' . $k);
            }

            // joins
            if (isset($aParam['join'])) {

                foreach ($aParam['join'] as $joinKey => $join) {

                    if (!array_key_exists('join_select', $join)) {

                        $cacheSelect = array();
                        $joinField   = $join['join_field'];

                        if ($joinField === false) { // nie dodajemy pol
                            $this->_param['join'][$joinKey]['join_select'] = null;
                            continue;
                        }

                        if ($joinField === null) { // nie podano pol, to dodajemy wszystkie
                            $joinClass = self::$_metadata[$this->_class]['prefix'] . $join['rel_rel_table'];
                            if (!isset(self::$_metadata[$joinClass])) {
                                new $joinClass();
                            }
                            $joinField = self::$_metadata[$joinClass]['field'];
                        }
                        else if (is_string($joinField)) { // podano jako string - pola oddzielone spacja
                            $joinField = explode(' ', $joinField);
                        }

                        $joinAlias = $join['join_alias'];


                        foreach ($joinField as $k => $v) {
                            $cacheSelect[] = ($joinAlias === null ? '' : "`$joinAlias`" . '.')
                                           . (is_int($k)
                                                ? ($joinAlias === null ? "`$v`" : "`{$v}` as `{$joinAlias}_{$v}`")
                                                : "`$v` as $k"
                                              );
                        }

                        $this->_param['join'][$joinKey]['join_select'] = $join['join_select']
                                                                       = implode(', ', $cacheSelect);

                    }

                    $select[] = $join['join_select'];
                }
            }

            $select = 'SELECT ' . implode(', ', $select);
        }

        // from
        if ($bFrom) {

            // this model
            $from[] = "`{$this->_table}`";

            // joins
            if (isset($aParam['join'])) {

                foreach ($aParam['join'] as $joinKey => $join) {


                    if (!array_key_exists('join_from', $join)) {

                        $joinSql = $join['join_type'] . ' `' . $join['rel_rel_table'] . '`'
                                . ($join['join_alias'] === null ? '' : " as `{$join['join_alias']}`")
                                . ' ON ('
                                . ($join['join_model_alias'] === null ? '' : "`{$join['join_model_alias']}`.")
                                . "`{$join['rel_field']}`"
                                . ' = '
                                . ($join['join_alias'] === null ? '' : "`{$join['join_alias']}`.")
                                . "`{$join['rel_rel_field']}`";

                        if ($join['rel_condition']) {
                            foreach ($join['rel_condition'] as $k => $v) {
                                $joinSql .= is_int($k)
                                            ? " AND $v"
                                            : " AND `$k` = '" . $this->_db->escape($v). "'";
                            }
                        }
                        $joinSql .= ')';

                        $this->_param['join'][$joinKey]['join_from'] = $join['join_from']
                                                                     = $joinSql;
                    }

                    $from[] = $join['join_from'];

                }

            }

            $from = ' FROM ' . implode(' ', $from);

        }

        return $select . $from . $where . $groupby . $having . $orderby . $limit . $offset;

    }

}