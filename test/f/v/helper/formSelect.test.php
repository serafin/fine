<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formSelect extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formSelect();
        
        $this->_testEqual(
            '<select name="foo"><option value="bar">Bar</option><option value="baz">Baz</option></select>',
            $o->helper('foo', null, array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'simple with 2 options'
        );
        $this->_testEqual(
            '<select name="foo"><option value="bar">Bar</option><option value="baz" selected="selected">Baz</option></select>',
            $o->helper('foo', 'baz', array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'simple with 2 options + second selected'
        );
        $this->_testEqual(
            '<select name="foo[]" multiple="multiple"><option value="bar">Bar</option><option value="baz" selected="selected">Baz</option></select>',
            $o->helper('foo[]', array('baz'), array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'multiple with 2 options + second selected'
        );


        $this->_testEqual(
            '<select name="foo[]" multiple="multiple" size="3">'
            . '<option value="bar" class="someClass">[Bar]</option>'
            . "\n" . '<option value="baz" selected="selected" class="someClass">[Baz]</option>'
            . "\n" . '<option value="qux" selected="selected" class="someClass" data="last-input">(Qux)</option>'
            . '</select>',
            $o->helper(
                'foo[]',
                array('baz', 'qux'),
                array(
                    'size'     => '3',
                    '_option'  => array('class' => 'someClass'),
                    '_prepend' => '[',
                    '_append'  => ']',
                ),
                array(
                    'bar' => 'Bar',
                    'baz' => 'Baz',
                    'qux' => array(
                        'data'     => 'last-input',
                        '_text'    => 'Qux',
                        '_prepend' => '(',
                        '_append'  => ')',
                     )
                ),
                "\n"
            ),
            'advance'
        );

    }

}

new test_f_v_helper_formSelect();

