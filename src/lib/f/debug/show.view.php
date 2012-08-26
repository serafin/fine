<?
$ec = " onclick=\"(this.nextSibling.nextSibling.style.display=='' ? this.nextSibling.nextSibling.style.display='none' : this.nextSibling.nextSibling.style.display='')\"";
?>
<style type="text/css">
.box-f_debug {padding:5px 5px 5px 5px; background: #fff;  font:12px monospace; color:#000;}
.box-f_debug .f_debug-th { background: #eee; font:12px monospace; color:#000;}
.box-f_debug .f_debug-td { background: #fff; font:12px monospace; color:#000;}
.box-f_debug .f_debug-head { border:0 solid #ddd;border-width: 1px 0 0 12px; padding:2px; font-size:14px;margin:1px 0 0 0; cursor: pointer;}
.box-f_debug .f_debug-error { border-color: #ff0000; }
.box-f_debug .f_debug-warning { border-color: #ffff00; }
.box-f_debug .f_debug-db { border-color: #6E9CD8; }
.box-f_debug .f_debug-system { border-color: #5ECA16; }

.box-f_debug .f_debug-head:hover { background: #f1f1f1; }
.box-f_debug .f_debug-error:hover { background: #ffeeee; }
.box-f_debug .f_debug-warning:hover { background: #ffffdd; }
.box-f_debug .f_debug-db:hover { background: #e5f0ff; }
.box-f_debug .f_debug-system:hover { background: #e7ffd6; }

.box-f_debug .f_debug-offset {float:right; color:#999; }
.box-f_debug .f_debug-strong {font-weight: bold; }
.box-f_debug .f_debug-preview { color: #bbb; }
.box-f_debug .f_debug-body { padding-left:25px; }
.box-f_debug .f_debug-data {  margin:10px 0 10px 0; }
.box-f_debug .f_debug-body pre { padding-left:25px; font-size: 12px;}
</style>
<?
    $style = '';
    foreach ($this->log as $log) {
        if ($log['style'] == f_debug::LOG_STYLE_WARNING) {
            $style = 'f_debug-warning';
            continue;
        }
        else if ($log['style'] == f_debug::LOG_STYLE_ERROR) {
            $style = 'f_debug-error';
            break;
        }
    }
?>
<div class="box-f_debug">
    <div class="f_debug-head <?= $style ?>" <?= $ec ?>>
        <span class="f_debug-offset">fine v<?= f::VERSION ?> | <?= date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) ?></span>
        <span class="f_debug-strong">debug</span>
    </div>
    <div class="f_debug-body" style="display:none">
        <? foreach ($this->log as $log) : ?>

            <?
                if ($log['tree'] == f_debug::LOG_TREE_CLOSE 
                    && $log['type'] == f_debug::LOG_TYPE_NO_DATA
                    && $log['label'] == null
                ) {
                    echo '</div>';
                    continue;
                }
            ?>

            <?
                // style
                $style = '';
                if ($log['style'] == f_debug::LOG_STYLE_WARNING) {
                    $style = 'f_debug-warning';
                }
                else if ($log['style'] == f_debug::LOG_STYLE_ERROR) {
                    $style = 'f_debug-error';
                }
                else if ($log['style'] == f_debug::LOG_STYLE_DB) {
                    $style = 'f_debug-db';
                }
                else if ($log['style'] == f_debug::LOG_STYLE_SYSTEM) {
                    $style = 'f_debug-system';
                }

                // prepare type
                if (!$log['type']) { // empty
                    $log['type'] = f_debug::LOG_TYPE_DUMP;
                }
                else if ($log['type'] == f_debug::LOG_TYPE_TABLE && !is_array($log['data']) && !is_array(current($log['data']))) { // not table
                    $log['type'] = f_debug::LOG_TYPE_DUMP;
                }
                else if ($log['type'] == f_debug::LOG_TYPE_LIST && !is_array($log['data'])) { // not list
                    $log['type'] = f_debug::LOG_TYPE_DUMP;
                }

                // data
                $data = "";
                switch ($log['type']) {

                    case f_debug::LOG_TYPE_NO_DATA:
                        break;

                    case f_debug::LOG_TYPE_DUMP:
                        $data =  "<pre>" . htmlspecialchars(f_debug::varDumpPretty($log['data'])) . "</pre>";
                        break;
                    case f_debug::LOG_TYPE_VAL:
                        $data = "<pre>" . htmlspecialchars(f_debug::scalar($log['data'])) . "</pre>";
                        break;

                    case f_debug::LOG_TYPE_LIST:
                        $data .= '<ul class="f_debug-ul">';
                        foreach ($log['data'] as $i) {
                            $data .= '<li class="f_debug-li"><pre>' . htmlspecialchars(f_debug::scalar($i)) . '</pre></li>';
                        }
                        $data .= '</ul>';
                        break;

                    case f_debug::LOG_TYPE_TABLE:
                        $data .= '<table><thead><tr>';
                        foreach (array_keys(current($log['data'])) as $key) {
                            $data .= '<th class="f_debug-th">' .  htmlspecialchars(f_debug::scalar($key)) . '</th>';
                        }
                        $data .= '</tr></thead><tbody>';
                        foreach($log['data'] as $i) {
                            $data .= '<tr>';
                            foreach($i as $j) {
                                $data .= '<td class="f_debug-td">' .  htmlspecialchars(f_debug::scalar($j)) . '</td>';
                            }
                            $data .= '</tr>';
                        }
                        $data .= '</tbody></table>';
                        break;

                    case f_debug::LOG_TYPE_CODE_HTML:
                        $data = f_debug::highlight($log['data'], 'html');
                        break;

                    case f_debug::LOG_TYPE_CODE_PHP:
                        $data = f_debug::highlight($log['data'], 'php');
                        break;

                    case f_debug::LOG_TYPE_CODE_SQL:
                        if (!is_scalar($log['data'])) {
                            $log['data'] = f_debug::varDumpPretty($log['data']);
                        }
                        $data = f_debug::highlight($log['data'], 'mysql');
                        break;

                    case f_debug::LOG_TYPE_TEXT_PLAIN:
                        if (!is_scalar($log['data'])) {
                            $log['data'] = f_debug::varDumpPretty($log['data']);
                        }
                        $data = "<pre>" . htmlspecialchars(f_debug::scalar($log['data'])) . "</pre>";
                        break;

                    case f_debug::LOG_TYPE_TEXT_HTML:
                        $data = "<pre>" . f_debug::scalar($log['data']) . "</pre>";
                        break;

                }
                
                // preview
                $preview = "";
                switch ($log['type']) {

                    case f_debug::LOG_TYPE_NO_DATA:
                        break;

                    case f_debug::LOG_TYPE_DUMP:
                        $preview = str_replace("\n", " ", f_debug::varDumpPretty($log['data']));
                        break;

                    case f_debug::LOG_TYPE_VAL:

                        $preview = str_replace("\n", " ", f_debug::scalar($log['data']));
                        break;

                    case f_debug::LOG_TYPE_LIST:
                        foreach ($log['data'] as $k => $v) {
                            $log['data'][$k] = f_debug::scalar($v);
                        }
                        $preview = str_replace("\n", " ", implode(" ", $log['data']));
                        break;

                    case f_debug::LOG_TYPE_TABLE:
                        reset($log['data']);
                        $preview = str_replace("\n", " ", implode(" ", array_keys(current($log['data']))));
                        break;

                    case f_debug::LOG_TYPE_CODE_HTML:
                    case f_debug::LOG_TYPE_CODE_PHP:
                    case f_debug::LOG_TYPE_CODE_SQL:
                    case f_debug::LOG_TYPE_TEXT_PLAIN:
                    case f_debug::LOG_TYPE_TEXT_HTML:
                        $preview = str_replace("\n", " ", f_debug::scalar($log['data']));
                        break;

                }

                $preview = strlen($preview) > 80 ? substr($preview, 0, 80) . '...' : $preview;

           ?>

            <div class="f_debug-head <?= $style ?>" <?= $ec ?>>

                <span class="f_debug-offset">
                    <? if (isset($log['time'])) : ?>
                        <?= sprintf('%01.4f', $log['time']) ?>s |
                    <? endif ?>

                    <?= sprintf('%01.4f', $log['offset']) ?>s
                </span>

                <?= $log['label'] ?>

                <span class="f_debug-preview">
                    <?= htmlspecialchars($preview) ?>
                </span>

            </div>

            <div class="f_debug-body" style="display: none;">

                <? if (isset($data[0])) : ?>
                    <div class="f_debug-data">
                        <?= $data ?>
                    </div>
                <? endif ?>
                
                <? if ($log['tree'] == f_debug::LOG_TREE_BRANCH) : ?>

                <? elseif ($log['tree'] == f_debug::LOG_TREE_CLOSE) : ?>
                    </div></div>
                <? else : ?>
                    </div>
                <? endif ?>

        <? endforeach ?>
    </div>
</div>