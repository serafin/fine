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


    public function __construct(array $config = array())
    {
        $this->_db = $config['_db'];
        $this->_c  = f::$c;
    }
    
    public function __call($name, $arguments)
    {
        
        if (in_array($name, array('query', 'row', 'rows', 'col', 'cols', 'val', 'rowNum', 'rowsNum', 'lastId'))) {
            
            $this->_c->debug->timer();
            $return = call_user_func_array(array($this->_db, $name), $arguments);
            $this->_c->debug->logRaw(array(
                'label' => 'DB',
                'data'  => $name == 'lastId' ? 'SELECT LAST_INSERT_ID()' : $arguments[0],
                'type'  => f_debug::LOG_GROUP,
            ));

            $result = $this->_db->result();
            
            if (is_resource($result)) { // select

                $iSelected  = $this->_db->countSelected();

                if ($iSelected) {
                    mysql_data_seek($result, 0);

                    $num  = in_array($name, array('col', 'cols', 'val', 'rowNum', 'rowsNum', 'lastId'));
                    $rows = array();
                    while ($i = ($num ? mysql_fetch_row($result) : mysql_fetch_assoc($result))) {
                            $rows[] = $i;
                    }
                    $this->_c->debug->table($rows, 'Rows');
                }
                
            }
            else if ($result === true) { // update, insert, delete
                $this->_c->debug->log($this->_db->countAffected(), 'Affected');
            }

            $this->_c->debug->groupEnd();
            
            return $return;
            
        }
        
        return call_user_func_array(array($this->_db, $name), $arguments);
        
    }
    
}
