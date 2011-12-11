<?php

require "../src/lib/f/autoload/includePath.php";
f_autoload_includePath::_()->path(array('./', '../src/lib/')) ->register();
f_error::_()->level(E_ALL ^ E_NOTICE)->render(true)->register();

class fVHelperForm extends f_test_unit
{

    public function f_v_helper_formPassword()
    {
        $o = new f_v_helper_formPassword();
        
        $this->_testEqual(
            '<input type="password" name="password" value="" />',
            $o->helper(),
            'simple, no args'
        );
        $this->_testEqual(
            '<input type="password" name="foo" value="" />',
            $o->helper('foo', 'bar'),
            'dont display value'
        );
        $this->_testEqual(
            '<input type="password" name="foo" value="" maxlength="255" />',
            $o->helper('foo', 'bar', array('maxlength' => '255')),
            'dont display value + attr'
        );
    }

    public function f_v_helper_formSelect()
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

    public function f_v_helper_formCheckbox()
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

    public function f_v_helper_formRadio()
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

    public function f_v_helper_formTextarea()
    {
        $o = new f_v_helper_formTextarea();
        
        $this->_testEqual(
            '<textarea name="foo">&lt;&gt;&quot;\'&amp;</textarea>',
            $o->helper('foo', '<>"\'&'),
            'escape'
        );
    }

    public function f_v_helper_formSubmit()
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

    public function f_v_helper_formText()
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

new fVHelperForm();

