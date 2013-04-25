<?php

class f_progress implements f_progress_interface 
{

    protected $_all;

    protected $_current = 0;
    
    protected $_view;

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

    public function all($iAllTask = null)
    {
        if (func_num_args() == 0) {
            return $this->_all;
        }
        $this->_all = $iAllTask;
        $this->_notifyView();
        return $this;
    }

    public function done()
    {
        $this->_current = $this->_all;
        $this->_notifyView();
        return $this;
    }

    public function set($iDoneTasks)
    {
        $this->_current = $iDoneTasks;
        $this->_notifyView();
        return $this;
    }

    public function get()
    {
        return $this->_current;
    }

    public function up($iNumber = 1)
    {
        $this->_current += $iNumber;
        $this->_notifyView();
        return $this;
    }

    public function progress($iPrecision = 2)
    {
        if (!$this->_all) {
            return 0;
        }
        
        if ($this->_current + 1 == $this->_all) {
            return sprintf("%1.{$iPrecision}f", 1);
        }
        
        $move     = pow(10, $iPrecision);
        return sprintf("%1.{$iPrecision}f", floor(sprintf("%1.8f", $this->_current / $this->_all) * $move) / $move);
    }
    
    /**
     * Set/get view
     * 
     * @param object $view
     * @return self|object 
     */
    public function view($view = null) 
    {
        if (func_num_args() == 0) {
            return $this->_view;
        }
        $this->_view = $view;
        return $this;
    }
    
    protected function _notifyView()
    {
        if ($this->_view === null || !$this->_all) {
            return;
        }
        
        $this->_view->update($this);
    }
    
}