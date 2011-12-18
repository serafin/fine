<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formPassword extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formPassword();
        
        $this->_testEqual(
            '<input type="password" name="password" value="" />',
            $o->helper(),
            'simple no args'
       );
        
        $this->_testEqual(
            '<input type="password" name="foo" value="bar" />',
            $o->helper('foo', 'bar'),
            'display value'
        );
        
        $this->_testEqual(
            '<input type="password" name="foo" value="bar" maxlength="255" />',
            $o->helper('foo', 'bar', array('maxlength' => '255')),
            'attributes'
        );
    }

}

new test_f_v_helper_formPassword();

