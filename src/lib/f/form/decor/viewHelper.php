<?php

class f_form_decor_viewHelper
{

    public function render()
    {
        return f::$c->v->{$this->element->viewHelper()}(
            $this->element->nameRaw(),
            $this->element->val(),
            $this->element->attr(),
            $this->element->option(),
            $this->element->separator()
        );
    }

}