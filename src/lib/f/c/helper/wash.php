<?php

class f_c_helper_wash
{

	public static function helper($sText, $cSpace = '_', $sAllow = '.()')
	{
		// usuwanie wszystkich niedozwolonych znaków
		$sText = preg_replace('![^'.$cSpace.'a-zA-Z0-9'.$sAllow.'\s]+!',      '', $sText);

		// wiele znaków spacji obok siebie zostaje zastąpionych w jednym znakiem spacji
		$sText = preg_replace('!['.$cSpace.'\s]+!'                     , $cSpace, $sText);
        
		return $sText;
	}
	

}
