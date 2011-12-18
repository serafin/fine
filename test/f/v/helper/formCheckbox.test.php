<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();

class test_f_v_helper_formCheckbox extends f_test_unit
{

    public function helper()
    {
        $o = new f_v_helper_formCheckbox();
        
        $this->_testEqual(
            '<input type="checkbox" name="checkbox" value="" />',
            $o->helper(),
            'one checkbox, empty args'
        );
        
        $this->_testEqual(
            '<input type="checkbox" name="foo" value="" />',
            $o->helper('foo'),
            'one checkbox, named foo'
        );
        
        $this->_testEqual(
            '<input type="checkbox" name="foo" value="" checked="checked" />',
            $o->helper('foo', 1)
        );
        
        $this->_testEqual(
            '<input type="checkbox" name="foo" value="" checked="checked" />',
            $o->helper('foo', 'on')
        );
        
        $this->_testEqual(
            '<input type="checkbox" name="foo" value="bar" checked="checked" />',
            $o->helper('foo', 'bar', array('value' => 'bar'))
        );
        
        $this->_testEqual(
            '<input type="checkbox" name="foo" value="bar" />',
            $o->helper('foo', 'baz', array('value' => 'bar'))
        );

        $this->_testEqual(
            '<label><input type="checkbox" name="foo[]" value="bar" />Bar</label>'
            . '<label><input type="checkbox" name="foo[]" value="baz" />Baz</label>',
            $o->helper('foo[]', null, array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'foo with options: bar, baz'
        );
        
        $this->_testEqual(
            '<label><input type="checkbox" name="foo[]" value="bar" />Bar</label>'
            . '<label><input type="checkbox" name="foo[]" value="baz" checked="checked" />Baz</label>',
            $o->helper('foo[]', array('baz'), array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'foo with options: bar, baz'
        );
        
        $this->_testEqual(
            '<label><input type="checkbox" name="foo[]" value="bar" checked="checked" />Bar</label>'
            . '<label><input type="checkbox" name="foo[]" value="baz" checked="checked" />Baz</label>',
            $o->helper('foo[]', array('baz', 'bar'), array(), array('bar' => 'Bar', 'baz' => 'Baz')),
            'foo with options: bar, baz'
        );
        
        $this->_testEqual(
            '<label class="label">[<input type="checkbox" name="foo[]" value="bar" class="checkbox" />|Bar]</label>'
            . "\n" . '<label class="label">[<input type="checkbox" name="foo[]" value="baz" checked="checked" class="checkbox" />|Baz]</label>'
            . "\n" . '<label data="last-label" class="label">(<input type="checkbox" name="foo[]" value="qux" class="checkbox" data="last-input" />,Qux)</label>',
            $o->helper(
                'foo[]',
                array('baz'),
                array(
                    'class'      => 'checkbox',
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

new test_f_v_helper_formCheckbox();

