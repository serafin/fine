<?php

class f_form_decor_errorMatrix extends f_form_decor_tag
{

    protected $_placement = self::PLACEMENT_APPEND;

    /**
     * @return f_form_decor_error
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    protected $_tag           = 'ul';
    protected $_itemPrepend   = '<li>';
    protected $_itemAppend    = '</li>';
    protected $_itemSeparator = '';
    
    /**
     * Additional elements - to show errors from other elements
     * @var array
     */
    protected $_element = array();
    
    /**
     * Ignore owner element?
     * @var boolean
     */
    protected $_ignoreOwner = false;

    public function element($aoAdditionalElement = null)
    {
        if (func_num_args() === 0) {
            return $this->_element;
        }
        if (! is_array($aoAdditionalElement)) {
            $aoAdditionalElement = array($aoAdditionalElement);
        }
        $this->_element = $aoAdditionalElement;
        return $this;
    }

    public function ignoreOwner($bIgnore = null)
    {
        if ($bIgnore === null) {
            return $this->_ignoreOwner;
        }
        $this->_ignoreOwner = (boolean)$bIgnore;
        return $this;
    }

    public function itemPrepend($sItemPrepend = null)
    {
        if (func_num_args() == 0) {
            return $this->_itemPrepend;
        }
        $this->_itemPrepend = $sItemPrepend;
        return $this;
    }

    public function itmeAppend($sItemAppend = null)
    {
        if (func_num_args() == 0) {
            return $this->_itemAppend;
        }
        $this->_itemAppend = $sItemAppend;
        return $this;
    }

    public function itemSeparator($sItemSeparator = null)
    {
        if (func_num_args() == 0) {
            return $this->_itemSeparator;
        }
        $this->_itemSeparator = $sItemSeparator;
        return $this;
    }

    public function render()
    {
        // errors
        $errors = array();
        if ($this->_ignoreOwner === false && $this->object->ignoreError() === false) {
            $errors = $this->object->error();
        }
        
        foreach ((array)$this->_element as $i) {
            $errors += $i->error();
        }

        // no errors = no decoration
        if (!$errors) {
            return $this->buffer;
        }

        
        $option = $this->object->option();
        $out    = '';
        foreach ($option['row'] as $kr => $row){
            // list
            $list = array();
            if($errors[$kr]){
                foreach ($errors[$kr] as $error) {
                    $list[] = $this->_itemPrepend . htmlspecialchars($error) . $this->_itemAppend;
                }
                $out .= '<div>'.$row.'</div>'.implode($this->_itemSeparator, $list);
            }
        }

        // decoration
        if ($this->_tag !== null) {
            $this->_prepare();
            $this->_decoration .= $out;
        }
        else {
            $this->_decoration  = $this->_prepend . $out;
            $this->_decoration2 = $this->_append;
        }

        return $this->_render();

    }

}