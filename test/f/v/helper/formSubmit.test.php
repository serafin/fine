<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formSubmit extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formSubmit();

        $this->_testEqual(
            '<input type="submit" name="submit" value="submit" />',
            $o->helper(),
            'no args'
        );
        
        $this->_testEqual(
            '<input type="submit" name="submit" value="Go!" />',
            $o->helper(null, 'Go!'),
            'value is Go!'
        );

        $this->_testEqual(
            '<input type="submit" name="submit[edit]" value="Save" style="font-weight:bold" class="submit" />'
          . "\n" . '<input type="submit" name="submit[clone]" value="Clone" class="submit" />'
          . "\n" . '<input type="reset" name="submit[reset]" value="Reset" style="text-decoration:line-through" class="submit" />'
          . "\n" . '<input type="submit" name="submit[del]" value="Delete" style="color:red" class="submit" />',
            $o->helper(
                    'submit',
                    null,
                    array('class' => 'submit'),
                    array(
                        'edit'  => array('value' => 'Save', 'style' => 'font-weight:bold'),
                        'clone' => 'Clone',
                        'reset' => array('value' => 'Reset',
                                         'style' => 'text-decoration:line-through',
                                         'type'  => 'reset'),
                        'del'   => array('value' => 'Delete', 'style' => 'color:red'),
                    ),
                    "\n"
            ),
            'advance use'
        );

    }

}

new test_f_v_helper_formSubmit();

