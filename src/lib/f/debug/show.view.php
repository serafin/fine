<?

$ec = " onclick=\"(this.nextSibling.nextSibling.style.display=='' ? this.nextSibling.nextSibling.style.display='none' : this.nextSibling.nextSibling.style.display='')\"";

?>
<style type="text/css">
.box-f_debug {padding:5px 5px 5px 5px; background: #222;  font:14px courier new, monospace; color:#ccc;}    
.box-f_debug th  {  background: #333; font:14px courier new, monospace; color:#ccc;}
.box-f_debug td {  background: #222; font:14px courier new, monospace; color:#ccc;}
.box-f_debug .f_debug-head { background:#111; padding:3px 5px 3px 5px; font-size:14px;margin:5px 0 0px 0; border-radius:10px; cursor: pointer;}
.box-f_debug .f_debug-offset {float:right; }
.box-f_debug .f_debug-strong {font-weight: bold; }
.box-f_debug .f_debug-preview { color: #555; }
.box-f_debug .f_debug-body { padding-left:25px; }
.box-f_debug .f_debug-body pre { padding-left:25px; font-size: 12px;}
</style>

<div class="box-f_debug">
    
    <div class="f_debug-head" <?= $ec ?>>
        
        <span class="f_debug-offset"><?= date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) ?></span>
        

        <span class="f_debug-strong">f_debug</span> | 
    </div>
    
    <div class="f_debug-body" style="display:none">
        
        
        <? foreach ($this->log as $log) : ?>
        
            <? if ($log['type'] == f_debug::LOG_GROUP) : ?>
        
                <div class="f_debug-head" <?= $ec ?>>

                    <span class="f_debug-offset"><?= sprintf('%01.4f', $log['offset']) ?>s</span>

                    <?= $log['label'] ?>


                </div>

                <div class="f_debug-body" style="display:none">

            <? elseif ($log['type'] == f_debug::LOG_GROUP_END) : ?>
                    
                </div>
        
            <? else : ?>
                <div class="f_debug-head" <?= $ec ?>>

                    <span class="f_debug-offset">
                        <? if (isset($log['time'])) : ?>
                            <?= sprintf('%01.4f', $log['time']) ?>s |
                        <? endif ?>
                
                        <?= sprintf('%01.4f', $log['offset']) ?>s
                    </span>

                    <?= $log['label'] ?>
                    

                    <span class="f_debug-preview">
                        <? 
                            if (in_array($log['type'], array(f_debug::LOG_LOG, f_debug::LOG_WARN, f_debug::LOG_ERROR))) {

                                $preview = '';
                                $data    = $log['data'];
                                if (is_string($data) || is_float($data) || is_numeric($data)) {
                                    $preview = $data;
                                }
                                else {
                                    $dump    = f_debug::varDumpPretty($data);
                                    if (strlen($dump) > 80) {
                                        $dump = substr($dump, 0, 80) . '...';
                                    }
                                    $preview =  $dump;
                                }
                                echo $preview;
                            }
                        ?>
                    </span>

                </div>

                <div class="f_debug-body" style="display:none">
                    <? if ($log['type'] == f_debug::LOG_TABLE) : ?>
                            
                        <? if (!is_array(current($log['data']))) : ?>
                            <pre><?= f_debug::varDumpPretty($log['data']) ?></pre>
                        <? else : ?>
                    
                            <table>
                                <tr>
                                    <? foreach(array_keys(current($log['data'])) as $key) : ?>
                                        <th><?= htmlspecialchars($key)?></th>
                                    <? endforeach ?>
                                </tr>
                                <? foreach($log['data'] as $data) : ?>
                                    <tr>
                                        <? foreach($data as $i) : ?>
                                            <td><?= htmlspecialchars($i)?></td>
                                        <? endforeach ?>
                                    </tr>
                                <? endforeach ?>
                            </table>
                            
                        <? endif ?>        
                    
                    <? else : ?>
                        <pre><?= htmlspecialchars(f_debug::varDumpPretty($log['data'])) ?></pre>
                    <? endif ?>        
                </div>
            <? endif ?>        
        
        <? endforeach ?>
        
        
        
        
    </div>
    
</div>