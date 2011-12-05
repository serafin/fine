<?php

class f_di_ref_param implements f_di_ref_interface
{

    protected $_id;

    public static function _($id)
    {
        return new self($id);
    }

    public function __construct($id)
    {
        $this->_id = $id;
    }

    public function id()
    {
        return $this->_id;
    }

}
