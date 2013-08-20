<?php

class f_di_listing extends f_di implements IteratorAggregate
{
    
    /**
     * @var array Lista nazw serwisow dla iteracji 
     */
    protected $_listing = array();


    /**
     * 
     * @return ArrayIterator
     */
    public function getIterator()
    {
        $services = array();
        
        foreach ($this->_listing as $name) {
            $services[$name] = $this->{$name};
        }
        
        return new ArrayIterator($services);
    }    
    

}