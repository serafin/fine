<?php

/**
 * Obsluga CSV - obecnie tylko odczyt
 * 
 * example: 
 * 
 * foreach (f_csv::_()->file('example.csv')->field('id email name') as $k => $row) {
 *      echo $row['id'] . "\n";
 * }
 * 
 */
class f_csv implements Iterator
{

    protected $_resource;
    protected $_row;
    protected $_index;
    protected $_separator = ',';
    protected $_charset   = 'UTF-8'; 
    protected $_start     = 0; // start line

    public function toArray()
    {
        $return = array();
        foreach ($this as $i) {
            $return[] = $i;
        }
        return $return;
    }

    public function rewind()
    {
        $this->_index = 0;
        rewind($this->_resource);
    }

    public function current()
    {
        $this->_row = fgetcsv($this->_resource, 4096, $this->_separator);
        $this->_index++;
        return $this->_row;
    }

    public function key()
    {
        return $this->_index;
    }

    public function next()
    {
        return !feof($this->_resource);
    }

    public function valid()
    {
        if (!$this->next()) {
            fclose($this->_resource);
            return FALSE;
        }
        return TRUE;
    }

}