<?php
 
class c_public extends f_c_action
{
 
    /**
     * # CSSI (CSS Implode) - Packowanie plikow css w jeden i wersjonowanie.
     *
     * ## Wymagania
     *
     *  - pliki css o rozszerzeniu `css` sa w folderze /public/css/{folder}
     *  - nazwy plikow o wzorcu `^v[0-1]+\.css$` sa zastrzezone
     *  - wpis w configu /app/config/public.php['css'][{folder}]['v'] = {integer}
     *  - adres do plikus css to: `/public/css/{folder}/v{$v}.css`
     *    gdzie `$v` to `/app/config/public.php['css'][{folder}]['v']`
     *
     * ## Aktualizacja cssa
     *
     * ### Srodowisko developerskie
     *
     * Modyfikujemy pliki w folderze. Nie podbijamy wersji cssa. Po uruchomieniu `/public/css/{folder}/v{$v}.css` plik generuje sie
     * dynamicznie i nie jest cachowany. W pliku sa dodatkowe komentarze informujace o tym z ktorego plik dana tresc jest.
     *
     * ### Srodowisko produkcyjne
     *
     * Modyfikujemy pliki w folderze. Podbijamy wersje cssa o jeden. Po uruchomieniu `/public/css/{folder}/v{$v}.css` plik generuje sie
     * dynamicznie i jest cachowany pod adresem `/public/css/{folder}/v{$v}.css`. Przy nastepnym wywolaniu apache podslya bezposrednio
     * plik i nie uruchamia skryptu aplikacji, akcji `c_public->cssAction()`. Poprzednia wersja cache jest usuwana.
     * Kiedy wywolamy adres `/public/css/{folder}/v{$v}.css` gdzie $v jest rozne od wersji ktora jest wpisana w konfigu, to nastapi
     * przekierowa na aktualna wersje cssa. Podbijanie wersji css najlepiej robic przed samym wgrywaniem plikow na serwer produkcyjny.
     *
     */
    public function cssAction()
    {
        $this->render->off();
 
        $filePatten = '/^v[0-9]*\.css$/';
        $isEnvDev   = $this->env == 'dev';
 
        // setup input /public/css/{$inputDir}/v{$inputV}.css
        $dir     = $_GET[2];
        $file    = $_GET[3];
        $version = (int)substr($file, 1, -4);
 
        // file and version format ok?
        if (!preg_match($filePatten, $file)) {
            $this->notFound();
        }
 
        // is this file under CSSI system?
        if (!isset($this->config->public['css'][$dir]['v'])) {
            $this->notFound();
        }
 
        // is this current version? if not, go to current file version
        if ($this->config->public['css'][$dir]['v'] != $version) {
            $this->redirect->uri(array('public', 'css', $dir, 'v' . $this->config->public['css'][$dir]['v'] . '.css'));
        }
 
        // implode all css files in `/public/css/$dir/*.css`
        $output = "";
        foreach (glob("./public/css/$dir/*.css") as $i) {
 
            // restricted css file name
            if (preg_match($filePatten, basename($i))) {
                continue;
            }
 
            // add comment
            if ($isEnvDev) {
                $output .= "\n/* $dir/" .  basename($i) . " */\n";
            }
 
            $output .= file_get_contents($i);
 
            if ($isEnvDev) {
                $output .= "\n\n";
            }
        }
 
        // replace_regexp
        if (isset($this->config->public['css'][$dir]['replace_regexp'])) {
            foreach ($this->config->public['css'][$dir]['replace_regexp'] as $k => $v) {
                $output = preg_replace($k, $v, $output);
            }
        }
 
        /** @todo compress css in production env */
 
        if (!$isEnvDev) {
 
            // save cache
            file_put_contents('public/css/' . $dir . '/v' . $version . '.css', $output);
 
            // remove out-of-date cache
            $outofdate = 'public/css/' . $dir . '/v' . ($version-1) . '.css';
            if ($version > 1 && is_file($outofdate)) {
                unlink($outofdate);
            }
 
        }
 
        // send cssi to client
        $this->response
                ->header('Content-Type', 'text/css')
                ->body($output)
                ->send();
    }
 
 
}