<?php

/**
 * @todo zrobic
 *  - konstruktor jest zle
 *  - raz jest remove raz clear, czego uzyc?
 */

//class f_filter
//{
//
//    protected $_filter = array();
//
//    public function __construct($aoFilter = null)
//    {
//        if ($aoFilter) {
//            $this->add($aoFilter);
//        }
//    }
//
//    public function add($aoFilter)
//    {
//        if (! is_array($aoFilter)) {
//            $aoFilter = array($aoFilter);
//        }
//        foreach ($aoFilter as $oFilter) {
//            $this->_filter[] = $oFilter;
//        }
//        return $this;
//    }
//
//    public function clear()
//    {
//        $this->_filter = array();
//        return $this;
//    }
//
//    public function get()
//    {
//        return $this->_filter;
//    }
//
//    public function filter($mData)
//    {
//        foreach ($this->_filter as $oFilter) {
//            $mData = $oFilter->filter($mData);
//        }
//        return $mData;
//    }
//
//}
//
