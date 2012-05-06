<?php

class f_form_decor_helper extends f_form_decor_default
{

    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function render()
    {
        $this->_decoration = f::$c->v->{$this->object->helper()}(
            $this->object->nameRaw(),
            $this->object->val(),
            $this->object->attr(),
            $this->object->option(),
            $this->object->separator()
        );
        return $this->_render();
    }

}