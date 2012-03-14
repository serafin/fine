<?php

class f_paging extends f_c
{

    public $all;
    public $limit   = 10;
    public $current = null;
    
    public $page = 0;
    public $prev = null;
    public $next = null;
    public $offset = 0;
    
    public $uriParam = 'page';
    public $uri;
    
    public function __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k} = $v;
        }

        if ($this->all) {
            if ($this->current === null) {
                $this->current = (int)f::$c->request->get($this->uriParam);
            }
            $this->paging();
        }
    }

    public function __toString()
    {
        return $this->render();
    }

    public function paging()
    {
        if ($this->all > 0) {

            $this->page = (int) ceil($this->all / $this->limit);
            $this->current = ($this->current > 0 && $this->current < $this->page) ? $this->current : 0;
            $this->offset = $this->limit * $this->current;
            $this->prev = ($this->current > 0) ? $this->current - 1 : null;
            $this->next = ($this->current < $this->page - 1) ? $this->current + 1 : null;
        }

        return $this;
    }

    public function render()
    {
        return f::$c->v->{$this->viewHelper}($this);
    }

}