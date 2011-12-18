<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formRadio extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formRadio();
        
        $this->_testEqual(
            '<label><input type="radio" name="foo" value="bar" />Bar</label>'
            . '<label><input type="radio" name="foo" value="baz" />Baz</label>',
            $o->helper('foo', null, array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'foo with options: bar, baz'
        );
        
        $this->_testEqual(
            '<label class="label">[<input type="radio" name="foo" value="bar" class="radio" />|Bar]</label>'
            . "\n" . '<label class="label">[<input type="radio" name="foo" value="baz" checked="checked" class="radio" />|Baz]</label>'
            . "\n" . '<label data="last-label" class="label">(<input type="radio" name="foo" value="qux" class="radio" data="last-input" />,Qux)</label>',
            $o->helper(
                'foo',
                'baz',
                array(
                    'class'      => 'radio',
                    '_label'     => array('class' => 'label'),
                    '_prepend'   => '[',
                    '_append'    => ']',
                    '_separator' => '|',
                ),
                array(
                    'bar' => 'Bar',
                    'baz' => 'Baz',
                    'qux' => array(
                        'data'       => 'last-input',
                        '_text'      => 'Qux',
                        '_prepend'   => '(',
                        '_append'    => ')',
                        '_separator' => ',',
                        '_label'     => array('data' => 'last-label'),
                     )
                ),
                "\n"
            ),
            'advance'
        );
        
    }

}

new test_f_v_helper_formRadio();

