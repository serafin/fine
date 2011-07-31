<?php

class c_setup
{

    public function indexAction()
    {
        $this->{f::$env}();
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