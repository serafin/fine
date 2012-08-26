<?php

class f_debug_db
{
    
    /**
     * @var f_db_mysql
     */
    protected $_db;
    
    /**
     * @var container
     */
    protected $_c;

    /**
     * Prefix labela debuga
     * @var string
     */
    protected $_label;


    public function __construct(array $config = array())
    {
        $this->_db    = $config['db'];
        $this->_label = $config['label'];
        $this->_c     = f::$c;
    }
    
    public function __call($name, $arguments)
    {

        /* @var $debug f_debug */
        $debug = $this->_c->debug;

        if (in_array($name, array('query', 'row', 'rows', 'col', 'cols', 'val', 'rowNum', 'rowsNum', 'lastInsertId'))) {
            
            $debug->timer()->start();
            $return = call_user_func_array(array($this->_db, $name), $arguments);
            $debug->timer()->stop();
            $data = $name == 'lastInsertId' ? 'SELECT LAST_INSERT_ID()' : $arguments[0];
            $debug->log($data, "{$this->_label}$name", f_debug::LOG_TYPE_CODE_SQL,
                        $debug->timer()->get() > 1 ? f_debug::LOG_STYLE_WARNING : f_debug::LOG_STYLE_DB,
                        f_debug::LOG_TREE_BRANCH
                        );
            $result = $this->_db->result();
            
            if (is_resource($result)) { // select

                $iSelected  = $this->_db->countSelected();

                if ($iSelected) {

                    mysql_data_seek($result, 0);
                    $rows   = array();
                    $j      = 0;
                    $method = in_array($name, array('col', 'cols', 'val', 'rowNum', 'rowsNum', 'lastId'))
                            ? 'fetchNumUsingResult'
                            : 'fetchUsingResult';

                    while ($i = $this->_db->{$method}($result)) {
                        if ($j > 1000) { // blokada
                            $rows[] = array_fill_keys(array_keys($i), '...');
                            break;
                        }
                        $rows[] = $i;
                        $j++;
                    }
                    $debug->log($rows, 'Selected rows: ' . $iSelected , f_debug::LOG_TYPE_TABLE);
                }
                
            }
            else if ($result === true) { // update, insert, delete
                $this->_c->debug->log($this->_db->countAffected(), 'Affected rows', f_debug::LOG_TYPE_VAL);
            }
            $this->_c->debug->log(null, null, f_debug::LOG_TYPE_NO_DATA, null, f_debug::LOG_TREE_CLOSE);

            return $return;
            
        }
        
        return call_user_func_array(array($this->_db, $name), $arguments);
        
    }
    
}
