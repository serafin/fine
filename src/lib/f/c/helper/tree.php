<?php

class f_c_helper_tree implements f_di_asNew_interface
{

    public $id     = array();
    public $parent = array();
    
    protected $_root   = 0;
    protected $_child  = 'child';
    protected $_id     = 'id';
    protected $_parent = 'parent';

    public function __construct($aConfig = array())
    {
        foreach ($aConfig as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function data($aData)
    {
        $this->remove();
        return $this->add($aData);
    }

    public function add($aData)
    {
        foreach ($aData as $i) {
            $this->id[$i[$this->_id]] = $i;
            $this->parent[$i[$this->_parent]][] = $i[$this->_id];
        }
        return $this;
    }

    public function id($sId = null)
    {
        if ($sId === null) {
            return $this->_id;
        }
        $this->_id = $sId;
        return $this;
    }

    public function model($sName)
    {
        $this->_id     = "{$sName}_id";
        $this->_parent = "{$sName}_id_{$sName}";
        return $this;
    }

    public function parent($sParent)
    {
        if ($sParent === null) {
            return $this->_parent;
        }
        $this->_parent = $sParent;
        return $this;
    }

    public function root($iRootId)
    {
        if ($iRootId === null) {
            return $this->_root;
        }
        $this->_root = $iRootId;
        return $this;
    }

    public function render()
    {
        return $this->_tree($this->_root);
    }

    public function remove()
    {
        $this->id = array();
        $this->parent = array();
        return $this;
    }

    public function child($sChildrenKey = null)
    {
        if ($sChildrenKey === null) {
            return $this->_child;
        }
        $this->_child = $sChildrenKey;
        return $this;
    }

    protected function _tree($parent)
    {
        $aReturn = array();
        foreach ($this->parent[$parent] as $i) {
            $a = $this->id[$i];
            if (isset($this->parent[$i])) {
                $a[$this->_child] = $this->_tree($i);
            }
            $aReturn[] = $a;
        }
        return $aReturn;
    }

}