<?php

class f_c_helper_fileUnlink
{

    /**
     * Usuwa plik lub pliki
     * 
     * np. f_c_helper_fileUnlink::helper(array('plik', 'plik2), 'folder/', '.html');
     *
     * @param array|string $asFile Plik lub pliki
     * @param string $sPrefix Przedrostek lokalizacji pliku lub plikow
     * @param string $sSufix  Przyrostek lokalizacji pliku lub plikow
     */
    public function helper($asFile, $sPrefix = null, $sSuffix = null)
    {
        if (!is_array($asFile)) {
            $asFile = explode(" ", $asFile);
        }
        foreach ($asFile as $file) {
            if (is_file($sPrefix . $file . $sSuffix)) {
                unlink($sPrefix . $file . $sSuffix);
            }
        }
    }

}