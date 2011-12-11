<?php

class v_container extends f_v_container
{

    protected function _c()
    {
        return $this->c = f::$c;
    }

    protected function _config()
    {
        return $this->config = $this->c->config;
    }

    protected function _flash()
    {
        return $this->flash = $this->c->flash;
    }

    protected function _reg()
    {
        return $this->reg = $this->c->reg;
    }

    protected function _request()
    {
        return $this->request = $this->c->request;
    }

    protected function _uri()
    {
        return $this->uri = $this->c->uri;
    }

}