<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formTextarea extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formTextarea();
        
        $this->_testEqual(
            '<textarea name="foo">&lt;&gt;&quot;\'&amp;</textarea>',
            $o->helper('foo', '<>"\'&'),
            'escape'
        );
    }

}

new test_f_v_helper_formTextarea();

