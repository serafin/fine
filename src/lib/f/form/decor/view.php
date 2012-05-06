<?php

class f_form_decor_view extends f_form_decor_default
{

    protected $_view;
    protected $_path;
    protected $_variable = 'object';

    /**
     * @return f_form_decor_view
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function variable($sVariable = null)
    {
        if (func_num_args() == 0) {
            return $this->_variable;
        }
        $this->_variable = $sVariable;
        return $this;
    }

    public function view($sView = null)
    {
        if (func_num_args() == 0) {
            return $this->_view;
        }
        $this->_view = $sView;
        return $this;
    }

    public function path($sPath = null)
    {
        if (func_num_args() == 0) {
            return $this->_path;
        }
        $this->_path = $sPath;
        return $this;
    }

    public function render()
    {
        
        f::$c->v->{$this->_variable} = $this->object;

        $this->_decoration = $this->_path !== null
                          ? f::$c->v->renderPath($this->_path)
                          : f::$c->v->render($this->_view);

        return $this->_render();
    }

}