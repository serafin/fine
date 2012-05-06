<?php

class f_form_decor_append extends f_form_decor_default
{

    protected $_content;

    /**
     * @return f_form_decor_append
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function content($sContent = null)
    {
        if ($sContent === null) {
            return $this->_content;
        }
        $this->_content = $sContent;
        return $this;
    }

    public function render()
    {
        $this->_decoration = $this->_content;
        return $this->_render();
    }

}
