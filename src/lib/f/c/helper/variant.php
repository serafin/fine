<?php

class f_c_helper_variant
{
    
    /**
     * Normalizuje wartosc wedlug podanych wariantow
     * 
     * $this->variant('yes', 'yes', 'no');        => yes
     * $this->variant('no', 'yes', 'no');         => no
     * $this->variant('no', 'yes', 'no');         => no
     * $this->variant('yes', array('no', 'yes')); => yes
     * $this->variant('no', array('no', 'yes'));  => no
     * $this->variant('', array('no', 'yes'));    => no
     * $this->variant('sure', array('no', 'yes', 'sure')); => sure
     * 
     * Badanie jest dokonywane za pomoco in_array
     *
     * @param mixed $mValue Badana wartosc
     * @param array|mixed $amVariant Wariant lub tablica wariantow
     * @param mixed $mDefault Wartosc standardowa, zwracana kiedy badana wartosc nie bedzie znajdowala sie w wariantach
     *                       Parametr opcjonalny, jezeli nie jest podany to brany jest pierwszy wariant z lewej 
     * @return mixed Wartosc znormalizowana
     */
    public function helper($mValue, $amVariant, $mDefault = null)
    {
        if (!is_array($amVariant)) {
            $amVariant = array($amVariant);
        }
        
        // jest badana wartosc w wariantach
        if (in_array($mValue, $amVariant)) {
            return $mValue;
        }
        
        // nie jest, zwracamy default 
        return (func_num_args() === 3 ? $mDefault : $amVariant[0]);
    }

}