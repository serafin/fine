<?php

class f_v_helper_paging
{

	public function helper(f_paging $paging)
	{

        /* setup */

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
		$link     = array();

        if (!($all > 0)) {
            return;
        }

        if (!$width) {
            $width = 3;
        }


        /*  table of pages np. 1 [ ] 3 4 5 [6] 7 8 9 [ ] 1234 */

        if (!($pages > 1)) {
            return; // nie ma wiecej strony niz 1 to nie generujemy stronicowania
        }

        /* @todo wygenerowac tablice linkow uwzgleniajac $first */

        // pierwsza strona
        $link[] = '0';

        // lewa spacja
        if ($page > $width + 1) {
            $link[] = ' ';
        }

        // lewy width
        for ($i = $page - $width; $i < $page; $i++) {
            if($i > 0) $link[] = $i;
        }

        // aktualna strona
        if ($page != 0 && $page != $pages - 1) {
            $link[] = $page;
        }

        $set = $page + 1;
        $end = $pages - 1;
        $end2 = $set + $width;

        // prawy width
        for ($i = $set; $i < $end && $i < $end2; $i++) {
            $link[] = $i;
        }

        // prawa spacja
        if ($page < ($pages - $width - 2)) {
            $link[] = ' ';
        }

        // ostatni strona
        $link[] = $end;


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
                $uri = array();
                foreach ($_GET as $k => $v) {
                    if (is_int($k)) {
                        continue;
                    }
                    $uri[$k] = $v;
                }
            }

            if (is_array($uri)) {
                $uri[$uriParam] = $var;
                $uri            = f::$c->uri($uri); // adres jako string z markerem {page}
            }
            
            $itemtpl .= '<li class="paging-li paging-page">'
                      . '<a class="paging-a" href="' . $uri . '">' . $var . '</a>'
                      . '</li> ';
        }

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

        return $out;
	}

}