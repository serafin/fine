<?php

class f_form_decor_form extends f_form_decor_tag
{

    protected $_tag = 'form';

    /**
     * @return f_form_decor_form
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function short($bShort = null)
    {
        throw new f_form_decor_badMethodCall('Form tag can not be shrot');
    }

    public function render()
    {
        $this->_attr = $this->object->attr() + $this->_attr;

        $this->_prepare();

        return $this->_render();
    }

}
