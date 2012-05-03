<?php

class f_form_decor_view extends f_form_decor_abstract
{

    protected $_view;
    protected $_path;

    public function view($sView = null)
    {
        if ($sView === null) {
            return $this->_view;
        }
        $this->_view = $sView;
        return $this;
    }

    public function path($sPath = null)
    {
        if ($sPath === null) {
            return $this->_path;
        }
        $this->_path = $sPath;
        return $this;
    }

    public function render()
    {
        $this->_decoration = $this->_path !== null
                          ? f::$c->v->renderPath($this->_path)
                          : f::$c->v->render($this->_view);

        $this->_render();
    }

}