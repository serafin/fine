<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_valid_alpha extends f_test_unit
{

    public function main()
    {
        $o = new f_valid_alpha();
        
        $this->_testFalse($o->isValid('abc123'));
        $this->_testFalse($o->isValid('abc 123'));
        $this->_testTrue($o->isValid('abcxyz'));
        $this->_testFalse($o->isValid('AZ@#4.3'));
        $this->_testFalse($o->isValid('aBc123'));
        $this->_testTrue($o->isValid('aBcDeF'));
        $this->_testFalse($o->isValid(''));
        $this->_testFalse($o->isValid(' '));
        $this->_testFalse($o->isValid("\n"));
    }

}

new test_f_v_valid_alpha();

