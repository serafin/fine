<?php

/**
 * @todo dodac do metody path gettera
 */
class f_config
{
    
    protected $___path;

    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function  __construct(array $config = array())
    {
        foreach ($config as $k => $v) {
            $this->{$k}($v);
        }
    }

    public function path($sBasePath)
    {
        if ($this->___path !== null) {

            // clear config cache
            foreach ((array)$this as $k => $v) {
                unset($this->{$k});
            }
            
        }
        $this->___path = $sBasePath;
    }

    public function __get($sName)
    {
        $file = str_replace('_', '/', $this->___path . $sName) . '.php';

        $this->{$sName} = include $file;

        if ($this->{$sName} === false) {
            throw new f_config_exception_logic("No config file named $sName ($file) or config file return false");
        }

        return $this->{$sName};

    }

}