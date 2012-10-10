<?php

class f_v_helper_paging
{

    public function helper(f_paging $paging)
    {

        /* setup */

        $out      = '';
        $all      = $paging->all();          // liczba wszystkich elementow
        $page     = $paging->page();         // aktualna strona
        $first    = $paging->firstPage();    // pierwsza strona, standardowo 1
        $pages    = $paging->pages();        // wszystkich stron
        $uri      = $paging->uri();          // adres jako parametry dla helpera uri
        $uriParam = $paging->uriParam();     // parametry adresy okreslajacy strone
        $var      = $paging->uriVar();       // zmienna do podstawienia strony w adresie stringowym
        $width    = $paging->param('width'); // liczba linkow pomiedzy aktualna strona i spacja
        $href     = $paging->param('href');
        $onclick  = $paging->param('onclick');
        $style    = $paging->param('style');
        $link     = array();

        if (!($all > 0)) {
            return '';
        }

        if (!$width) {
            $width = 3;
        }


        /*  table of pages np. 1 [ ] 3 4 5 [6] 7 8 9 [ ] 1234 */

        if (!($pages > 1)) {
            return ''; // nie ma wiecej strony niz 1 to nie generujemy stronicowania
        }

        /* df */

        if($pages<4) {
            $link = array($first, $first+1);
            if($pages==3) {
                $link[] = $first+2;
            }
        }
        else {
            // calculate
            $start = $page - (int)($width/2);
            if ( $start < $first ) {
                $start = $first;
            }
            $end = $start + $width - 1;
            $last = $first + $pages - 1;
            if ( $end > $last ) {
                $end = $last;
                $start = $end - $width + 1;
                if ( $start < $first ) {
                    $start = $first;
                }
            }
            // array
            if ( $start > $first ) {
                $link[] = $first;
                $link[] = ' ';
            }
            for ($i=$start; $i<=$end; $i++) {
                $link[] = $i;
            }
            if ( $end < $last ) {
                $link[] = ' ';
                $link[] = $last;
            }
        }

        /* render */

        $itemtpl = "";

        if (isset($href) || isset($onclick)) {
            $itemtpl = '<li class="paging-li paging-page"><a class="paging-a" '
            . (isset($href) ? 'href="' . $href . '"' : '')
            . (isset($onclick) ? 'onclick="' . $onclick . '"' : '')
            . '>' . $var . '</a></li> ';
        }
        else {
            if (!$uri) {
                $uri =  f::$c->uri->assembleRequest(array($uriParam => '___page___'));
                $uri = str_replace('___page___', $var, $uri);
            }

            if (is_array($uri)) {
                $uri[$uriParam] = $var;
                $uri            = f::$c->uri($uri); // adres jako string z markerem {page}
            }

            $itemtpl .= '<li class="paging-li paging-page">'
            . '<a class="paging-a" href="' . $uri . '">' . $var . '</a>'
            . '</li> ';
        }

        
        if($style && $style == 'bootstrap'){
            $out = '<div class="pagination"><ul>';
            foreach ($link as $i) {
                if ($i == ' ') {
                    $out .= '<li class="disabled"><a> ... </a></li> ';
                }
                else if ($i == $page) {
                    $out .= '<li class="active"><a>' . $i . '</a></li> ';
                }
                else {
                    $out .= str_replace($var, $i, $itemtpl);
                }
            }
            $out .= '</ul></div>';
        }
        else {        
            $out = '<div class="box-paging"><ul class="paging-ul">';
            foreach ($link as $i) {
                if ($i == ' ') {
                    $out .= '<li class="paging-li paging-space"> ... </li> ';
                }
                else if ($i == $page) {
                    $out .= '<li class="paging-li paging-current">' . $i . '</li> ';
                }
                else {
                    $out .= str_replace($var, $i, $itemtpl);
                }
            }
            $out .= '</ul><div class="paging-end"></div></div>';
        }

        return $out;
    }

}