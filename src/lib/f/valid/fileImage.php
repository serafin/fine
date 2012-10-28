<?php

class f_valid_fileImage extends f_valid_abstract
{

    const VALUE_EMPTY  = 'VALUE_EMPTY';
    const NOT_OPENABLE = 'NOT_OPENABLE';

    protected $_msg = array(
        self::VALUE_EMPTY  => 'Brak pliku',
        self::NOT_OPENABLE => 'Nie można otworzyć pliku jako obraz',
    );

    public static function _(array $config = array())
    {
        return new self($config);
    }

    public function isValid($mValue)
    {
        // $mValue from $_FILES ?
        if (!is_string($mValue) && is_array($mValue) && isset($mValue['tmp_name'])) {
            $mValue = $mValue['tmp_name'];
        }

        $mValue = (string)$mValue;
        $this->_val($mValue);

        if ('' === $mValue) {
            $this->_error(self::VALUE_EMPTY);
            return false;
        }

        if (f_image::_()->load($mValue)->error()) {
            $this->_error(self::NOT_OPENABLE);
            return false;
        }

        return true;
    }

}