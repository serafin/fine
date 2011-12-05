<?php

class c_setup
{

    public function indexAction()
    {
        $this->{$this->env}();
    }

    public function dev()
    {
        /** @todo auto tworzenie nie istniejacych modeli
         * 
         */
    }

    public function prod()
    {
    }

}