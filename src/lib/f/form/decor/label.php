<?php

class f_form_decor_label extends f_form_decor_tag
{

    const GRAVITY_LEFT  = 'GRAVITY_LEFT';
    const GRAVITY_RIGHT = 'GRAVITY_RIGHT';

    protected $_placement = self::PLACEMENT_EMBRACE;
    protected $_tag       = 'label';
    protected $_gravity   = self::GRAVITY_LEFT;
    protected $_separator = '';


    /**
     * @return f_form_decor_label
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
        $label = $this->object->label();

        if ($label === null) {
            return $this->buffer;
        }

        $type = $this->object->type();

        if ($this->object->multi()                          // jezeli mutli
            && ($type == 'checkbox' || $type == 'radio')    // checkbox lub radio
            && $this->_tag == 'label'                       // tag to label
            && $this->_placement == self::PLACEMENT_EMBRACE // umiejscowienie to objecie
        ) {
            $this->_tag = null; // to wylaczam tag, bo f_v_helper_form{Checkbox|Radio} z opcjami (multi) dodaje <label>
        }


        if ($this->_tag === null) {

            switch ($this->_gravity) {

                case self::GRAVITY_LEFT:
                    $this->_decoration  = $this->_prepend . $label . $this->_separator;
                    $this->_decoration2 = $this->_append;
                    break;

                case self::GRAVITY_RIGHT:
                    $this->_decoration  = $this->_prepend . $label . $this->_separator;
                    $this->_decoration2 = $this->_append;
                    break;

                default :
                    throw new f_form_decor_exception_domain('Wrong value for gravity property');
                    break;

            }

        }
        else {
            $id = $this->object->id();
            if (strlen($id) > 0) {
                $this->_attr['for'] = $id;
            }
            
            $this->_prepare();

            switch ($this->_gravity) {

                case self::GRAVITY_LEFT:
                    $this->_decoration  .= $label . $this->_separator;
                    break;

                case self::GRAVITY_RIGHT:
                    $this->_decoration2 = $this->_separator . $label . $this->_decoration2;
                    break;

                default :
                    throw new f_form_decor_exception_domain('Wrong value for gravity property');
                    break;

            }


        }
        return $this->_render();
    }

}