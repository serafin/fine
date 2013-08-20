<?php

/**
 * Simple Lazy Object Holder
 * 
 *  $object = new f_c_helper_sloh(array(
 *      'tpl'   => 'm_%s',
 *  ));
 *  $object->cat; // obiekt m_cat
 *  $object->dog; // obiekt m_dog
 *  $object->dog; // obiekt m_dog ten sam co wyzej
 * 
 * Po sloh mozna iterowac podajac wczesniej liste wszytkich bytow `$object->being(array('cat', 'dog'))`
 * 
 */
class f_c_helper_sloh implements IteratorAggregate, f_di_asNew_interface
{
    
    protected $_tpl   = "%s";
    protected $_being = array();
    
    /**
     * Statyczny konstruktor
     * 
     * @param array $config
     * @return f_c_helper_sloh
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }
    
    public function getIterator()
    {
        foreach ($this->_being as $name => $module) {
            if ($module === true) {
                $this->_load($name);
            }
        }
        
        return new ArrayIterator($this->_being);
    }
    
    /**
     * Pobiera/ustala wzorzec nazwy klas
     * 
     * @param type $sClassNameTpl Wzorzec gdzie z `%s` gdzie bedzie opcja
     */
    public function tpl($sClassNameTpl = null)
    {
        // getter
        if (func_num_args() == 0) {
            return $this->_tpl;
        }
        
        // setter
        $this->_tpl = $sClassNameTpl;
        
        return $this;
    }
    
    public function being($aBeing = null)
    {
        // getter
        if (func_num_args() == 0) {
            return array_keys($this->_being);
        }
        
        // setter
        foreach ($aBeing as $being) {
            $this->_being[$being] = true;
        }
        
        return $this;
    }

    public function __get($name)
    {
        return $this->$name = $this->_load($name);
    }
    
    public function __isset($name)
    {
        return isset($this->_being[$name]);
    }

    /* private api */
    
    protected function _load($name)
    {
        if ($this->_being[$name] === true || !isset($this->_being[$name])) {
            $class               = sprintf($this->_tpl, $name);
            $module              = new $class;
            $this->_being[$name] = $module;
        }
        
        return $this->_being[$name];
    }

}