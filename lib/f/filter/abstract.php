<?php

abstract class f_filter_abstract
{

    public function  __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public static function _()
    {
        return new get_called_class();
    }

    public function config(array $config)
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

}