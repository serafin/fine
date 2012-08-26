<?php

class f_v_helper_paging
{

	public function helper(f_paging $paging)
	{
        $all      = $paging->all();
        $page     = $paging->page();
        $pages    = $paging->pages();
		$width    = $paging->param('width');
        $uri      = $paging->uri();
        $uriParam = $paging->uriParam();
		$link     = array();

        if (!($all > 0)) {
            return;
        }

        if (!$width) {
            $width = 3;
        }

        if (!$uri) {
            $uri = array();
            foreach ($_GET as $k => $v) {
                if (is_int($k)) {
                    continue;
                }
                $uri[$k] = $v;
            }
        }

        $link[] = '0';
        if ($pages > 1) {
            if ($page > $width + 1) {
                $link[] = ' ';
            }
            for ($i = $page - $width; $i < $page; $i++) {
                if($i > 0) $link[] = $i;
            }
            if ($page != 0 && $page != $pages - 1) {
                $link[] = $page;
            }
            $set = $page + 1;
            $end = $pages - 1;
            $end2 = $set + $width;
            for ($i = $set; $i < $end && $i < $end2; $i++) {
                $link[] = $i;
            }
            if ($page < ($pages - $width - 2)) {
                $link[] = ' ';
            }
            $link[] = $end;
        }

        if (count($link) < 2) {
            return '';
        }

        // render
        $out = '<div class="box-paging"><ul class="paging-ul">';
        foreach($link as $i){
            if ($i == ' ') {
                $out .= '<li class="paging-li paging-space"> ... </li> ';
            }
            else if ($i == $page) {
                $out .= '<li class="paging-li paging-current">' . ($i+1) . '</li> ';
            }
            else {
                $tmp = $uri;
                $tmp[$uriParam] = $i;
                $out .= '<li class="paging-li paging-page">'
                      . '<a class="paging-a" href="' 
                      . f::$c->uri($tmp)
                      . '">'
                      . ($i + 1)
                      . '</a></li> ';
            }
        }
        $out .= '</ul><div class="paging-end"></div></div>';

        return $out;
	}

}