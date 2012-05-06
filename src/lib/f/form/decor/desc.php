<?php

class f_form_decor_desc extends f_form_decor_tag
{

    const GRAVITY_LEFT  = 'GRAVITY_LEFT';
    const GRAVITY_RIGHT = 'GRAVITY_RIGHT';

    protected $_placement = self::PLACEMENT_APPEND;
    protected $_tag       = 'span';
    protected $_gravity   = self::GRAVITY_LEFT;
    protected $_separator = '';


    /**
     * @return f_form_decor_desc
     */
    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function separator($sSeparator = null)
    {
        if (func_num_args() == 0) {
            return $this->_separator;
        }
        $this->_separator = $sSeparator;
        return $this;
    }

    public function gravity($tGravity = null)
    {
        if (func_num_args() == 0) {
            return $this->_gravity;
        }
        $this->_gravity = $tGravity;
        return $this;
    }

    public function render()
    {
        $desc = $this->object->desc();

        if ($desc === null) {
            return $this->buffer;
        }

        if ($this->_tag === null) {

            switch ($this->_gravity) {

                case self::GRAVITY_LEFT:
                    $this->_decoration  = $this->_prepend . $desc . $this->_separator;
                    $this->_decoration2 = $this->_append;
                    break;

                case self::GRAVITY_RIGHT:
                    $this->_decoration  = $this->_prepend;
                    $this->_decoration2 = $this->_separator . $desc . $this->_append;
                    break;

                default :
                    throw new f_form_decor_exception_domain('Wrong value for gravity property');
                    break;
            }

        }
        else {
            $this->_prepare();

            switch ($this->_gravity) {

                case self::GRAVITY_LEFT:
                    $this->_decoration .= $this->_separator . $desc;
                    break;

                case self::GRAVITY_RIGHT:
                    $this->_decoration2 = $desc . $this->_separator . $this->_decoration2;
                    break;

                default :
                    throw new f_form_decor_exception_domain('Wrong value for gravity property');
                    break;

            }

        }

        return $this->_render();
    }

}