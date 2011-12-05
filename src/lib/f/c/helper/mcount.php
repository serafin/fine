<?php

/** @todo */

class x
{
	/**
	 * Setup dla counter, increment, decrement
	 *
	 * @param &string $sRefName
	 * @param &array|string|integer $ids
	 * @param &string $fieldCountName
	 * @param &array $aRef
	 */
	private function _counter(&$sRefName, &$aisID, &$sCountFieldName, &$aRef)
	{

		$aRef = $this->_ref($sRefName, null, $null1, $null2);
		if ($sCountFieldName === null) {
			if (strpos($sRefName, '_') !== false) {
				list($sNull, $sEnd) = explode('_', $sRefName);
			}
			$sCountFieldName = $aRef[1] . '_count_' . $this->_table . ( isset($sEnd) ? $sEnd : '' );
		}
		if ($aisID === null) {
			$aisID = array($this->{$aRef[0]});
		}
		else if (!is_array($aisID)) {
			$aisID = array($aisID);
		}
		foreach ($aisID as $k => $v) {
			$aisID[$k] = db::escape($v);
		}
	}

	/**
	 * Zwieksza pole przetrzymujące informacje o ilość powiązanych rekordów o jeden
	 *
	 * @param string $sRefName Nazwa referencji
	 * @param array|string|integer $aisID
	 * @param string $sCountFieldName Nazwa pola przetrzymującego informacje o ilość powiązanych rekordów standardowo {nazwa tabeli powiazanej}_count_{nazwa tej tabeli}
	 */
	public function counterIncrement($sRefName, $aisID = null, $sCountFieldName = null)
	{
		$this->_counter($sRefName, $aisID, $sCountFieldName, $aRef);
		if ($aRef) {
			db::query("UPDATE {$aRef[1]} SET $sCountFieldName = $sCountFieldName + 1 WHERE {$aRef[2]} IN('".implode("','", $aisID)."')");
		}
	}

	/**
	 * Zmienjsza pole przetrzymujące informacje o ilość powiązanych rekordów o jeden
	 *
	 * @param string $sRefName Nazwa referencji
	 * @param array|string|integer $aisID
	 * @param string $sCountFieldName Nazwa pola przetrzymującego informacje o ilość powiązanych rekordów standardowo {nazwa tabeli powiazanej}_count_{nazwa tej tabeli}
	 */
	public function counterDecrement($sRefName, $aisID = null, $sCountFieldName = null)
	{
		$this->_counter($sRefName, $aisID, $sCountFieldName, $aRef);
		if ($aRef) {
			db::query("UPDATE {$aRef[1]} SET $sCountFieldName = $sCountFieldName - 1 WHERE {$aRef[2]}  IN('".implode("','", $aisID)."')");
		}
	}

	/**
	 * Oblicza ilość powiązanych rekodów i zapisuje do pola przetrzymującego informacje o ilość powiązanych rekordów
	 *
	 * @param string $sRefName Nazwa referencji
	 * @param array|string|integer $aisID
	 * @param string $sCountFieldName Nazwa pola przetrzymującego informacje o ilość powiązanych rekordów standardowo {nazwa tabeli powiazanej}_count_{nazwa tej tabeli}
	 * @param string Dodatkowy warunek
	 */
	public function counterCount($sRefName, $aisID = null, $sCountFieldName = null, $sWhereCondition = '')
	{
		$this->_counter($sRefName, $aisID, $sCountFieldName, $aRef);
		if (! empty($sWhereCondition)) {
			$sWhereCondition = ' AND '.$sWhereCondition;
		}
		if ($aRef) {
			db::query("UPDATE {$aRef[1]} as a SET a.$sCountFieldName = ( SELECT COUNT(*) FROM $this->_table as b WHERE b.{$aRef[0]} = a.{$aRef[2]} $sWhereCondition ) WHERE a.{$aRef[2]} IN('".implode("','", $aisID)."')");
		}
	}
}