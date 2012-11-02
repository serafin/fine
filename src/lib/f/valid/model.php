<?php

/**
 * f_valid_model
 * =============
 *
 * Walidacja modelu, bazy danych.
 * `f_valid_model` zastepuje walidatory `f_valid_dbEqual`, `f_valid_dbExist`, `f_valid_dbNotExist`.
 *
 * Podstawowe wlasnosci:
 *  - `model()`   - model
 *  - `method()`  - metoda modelu ktora zostanie uruchomiona i wynik bedzie wykorzystywany przy porownaniu
 *  - `param()`   - modelu zostanie wstrzyknieta wartosc walidowana wlasnie pod ten parametr, opcjonalny
 *  - `compare()` - operator porowania
 *  - 'val()'     - druga wartosc do porownaia
 *
 * Dodatkowe metody ktore ulatwiaja ustawienie podstawowych wlasnosci:
 *  - `exitst()`    - sprawdzanie czy rekord istnieje
 *  - `notExitst()` - sprawdzanie czy rekord nie istnieje
 *  - `equal()`     - sprawdzanie czy wynik bedzie rowny podanemu
 *
 * W skrocie walidacja dziala tak:
 *
 *  public funciton isValid($mValue)
 *  {
 *      return
 *          $this->model()->param(array($this->param() => $mValue))->{$this->method()} // np. `2`
 *          $this->compare()                                                           // np. `==`
 *          $this->val()                                                               // np. `1`
 *  }
 * 
 *
 * Przyklad: czy adres email jest wolny
 * ------------------------------------
 *
 *  $valid = new f_valid_model();
 *  $valid->model(m_user::_());
 *  $valid->notExist('user_email');
 *
 *  echo $valid->isValid($_POST['user_email']);
 *
 *
 * Przyklad: zmiana adresu email
 * -----------------------------
 *
 *  $valid = new f_valid_model();
 *  $valid->model(m_user::_()->param(array('user_id !=' => 1234)));
 *  $valid->notExist('user_email');
 *
 *  $valid->isValid($_POST['user_email']);
 * 
 * 
 * Przyklad: zabezpieczenie przed flodowaniem
 * ------------------------------------------
 * 
 *  $valid = new f_valid_model();
 *  $valid->model(m_comment::_()->param(array(
 *      'comment_id_user'   => 1234,
 *      'comment_insert' <=' => time() - 60 * 60
 *  )));
 *  $valid->method('fetchCount');
 *  $valid->compare(f_valid_model::COMPARE_LESS_THAN);
 *  $valid->val(5);
 *
 *  $valid->isValid(null);
 *
 */
class f_valid_model extends f_valid_abstract
{

    const COMPARE_EQUAL                 = '==';
    const COMPARE_NOT_EQUAL             = '!=';
    const COMPARE_LESS_THAN             = '<';
    const COMPARE_GREATER_THAN          = '>';
    const COMPARE_LESS_THAN_OR_EQUAL    = '<=';
    const COMPARE_GREATER_THAN_OR_EQUAL = '>=';

    const NOT_VALID = 'NOT_VALID';

    protected $_msg = array(
        self::NOT_VALID => 'Nieprawidłowa wartość',
    );

    /**
     * @var f_m Model
     */
    protected $_model;

    /**
     * @var string Param
     */
    protected $_param;

    /**
     * @var string Operator
     */
    protected $_compare;

    /**
     * @var int|string Wartosc wyrazenia do porowania z wynikiem metody moelu
     */
    protected $_valToCompare;

    /**
     * @var string Method
     */
    protected $_method;

    /* main properties */

    /**
     * Ustala/pobiera model
     *
     * @param type $oModel
     * @return f_valid_model|f_m
     */
    public function model($oModel = null)
    {
        if (func_num_args() == 0) {
            return $this->_model;
        }
        $this->_model = $oModel;
        return $this;
    }

    /**
     * Ustala/pobiera klucz parametry
     *
     * Parametry pod ktorym bedzie wstawiona wartosc argumentu funkcji `isValid()`
     *
     * @param type $sParam
     * @return f_valid_model|string
     */
    public function param($sParam = null)
    {
        if (func_num_args() == 0) {
            return $this->_param;
        }
        $this->_param = $sParam;
        return $this;
    }

    /**
     * Ustala/pobiera operator porownania
     *
     * @param type $tCompare Operator porownania jedna wartosc z self::COMPARE_*
     * @return f_valid_model|const
     */
    public function compare($tCompare = null)
    {
        if (func_num_args() == 0) {
            return $this->_compare;
        }
        $this->_compare = $tCompare;
        return $this;
    }

    /**
     * Wartosc do porowania z wynikiem metody modelu
     *
     * @param int|string $isCompareValue
     * @return f_valid_model
     */
    public function val($isCompareValue = null)
    {
        if (func_num_args() == 0) {
            return $this->_valToCompare;
        }
        $this->_valToCompare = $isCompareValue;
        return $this;
    }

    /**
     * Ustala pobiera metode
     *
     * Metoda bedzie odpalona przy wywolaniu funkcji `isValid($mValue)`
     *
     * @param type $sParam
     * @return \f_valid_model
     */
    public function method($sMethod = null)
    {
        if (func_num_args() == 0) {
            return $this->_method;
        }
        $this->_method = $sMethod;
        return $this;
    }

    /* validation logic */

    /**
     *
     * @param type $mValue
     * @return boolean
     */
    public function isValid($mValue)
    {
        if (!is_object($this->_model)) {
            throw new f_valid_exception_logic("Wlasnosc `model()` nie jest obiektem");
        }

        if (strlen($this->_method) == 0 || !method_exists($this->_model, $this->_method)) {
            throw new f_valid_exception_logic("Model `model()` nie posiada metody `method()`");
        }

        if ($this->_valToCompare === null) {
            throw new f_valid_exception_logic("Wlasnosc `val()` nie jest zdefiniowana");
        }

        $this->_val($mValue); // Wartosc 1

        if (strlen($this->_param) > 0) {
            $this->_model->param($this->_param, $this->_val);
        }

        $a = $this->_model->{$this->_method}();
        $b = $this->_valToCompare;

        switch ($this->_compare) {

            case self::COMPARE_EQUAL:
                return $a == $b;

            case self::COMPARE_NOT_EQUAL:
                return $a != $b;

            case self::COMPARE_LESS_THAN:
                return $a < $b;

            case self::COMPARE_GREATER_THAN:
                return $a > $b;

            case self::COMPARE_LESS_THAN_OR_EQUAL:
                return $a <= $b;

            case self::COMPARE_GREATER_THAN_OR_EQUAL:
                return $a >= $b;

            default:
                throw new f_valid_exception_logic("Nie prawidlowa wartosc dla wlasnosci `operator()`");

        }

        return true;
    }

    /* helpers */

    public function exist($sModelField)
    {
        return $this
            ->param($sModelField)
            ->method('fetchCount')
            ->compare(self::COMPARE_GREATER_THAN)
            ->val(0);

    }

    public function notExist($sModelField)
    {
        return $this
            ->param($sModelField)
            ->method('fetchCount')
            ->compare(self::COMPARE_EQUAL)
            ->val(0);
    }
    
    public function equal($isValue, $sModelField)
    {
        return $this
            ->param($sModelField)
            ->method('fetchCount')
            ->compare(self::COMPARE_EQUAL)
            ->val($isValue);
    }

}
