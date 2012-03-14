<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_valid_alnum extends f_test_unit
{

    public function main()
    {
        $o = new f_valid_alnum();
        
        $this->_testTrue($o->isValid('abc123'));
        $this->_testFalse($o->isValid('abc 123'));
        $this->_testTrue($o->isValid('abcxyz'));
        $this->_testFalse($o->isValid('AZ@#4.3'));
        $this->_testTrue($o->isValid('aBc123'));
        $this->_testFalse($o->isValid(''));
        $this->_testFalse($o->isValid(' '));
        $this->_testFalse($o->isValid("\n"));
        $this->_testTrue($o->isValid('foobar1'));
    }

}

new test_f_v_valid_alnum();

