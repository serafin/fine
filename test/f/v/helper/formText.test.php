<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formText extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formText();
        
        $this->_testEqual(
            '<input type="text" name="text" value="" />',
            $o->helper(),
            'no arguments'
        );
        $this->_testEqual(
            '<input type="text" name="foo" value="" />',
            $o->helper('foo'),
            'name is foo'
        );
        $this->_testEqual(
            '<input type="text" name="foo" value="bar" />',
            $o->helper('foo', 'bar'),
            'name is foo val is bar'
        );
        $this->_testEqual(
            '<input type="text" name="foo" value="bar" class="text" style="color:red" />',
            $o->helper('foo', 'bar', array('class' => 'text', 'style' => 'color:red')),
            'name is foo val is bar + class + css'
        );
        $this->_testEqual(
            '<input type="text" name="foo" value="&lt;&gt;&quot;\'&amp;" />',
            $o->helper('foo', '<>"\'&'),
            'escape val'
        );
        $this->_testEqual(
            '<input type="text" name="foo" value="&lt;&gt;&quot;\'&amp;" data="&lt;&gt;&quot;\'&amp;" />',
            $o->helper('foo', '<>"\'&', array('data' => '<>"\'&')),
            'escape val and data attr'
        );

    }

}

new test_f_v_helper_formText();

