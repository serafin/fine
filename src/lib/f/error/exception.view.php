<style type="text/css">

body {margin:0; parring:0;}
.box-f_error { color:#f00;padding:50px 20px;background:#fff; margin:0;  font:14px courier new, monospace; color:#000; }
.box-f_error .f_error-head { color:red; font-family:arial,helvetica,tahoma;font-size:100px; padding:0 20px 20px 20px;}
.box-f_error .f_error-foot { color:#999;text-align:right;font:28px cursive;padding:0 20px 0 0;}
.box-f_error .f_error-table { width:100%;border-collapse:collapse; }
.box-f_error .f_error-th { padding: 10px 15px 10px 15px;color:#888;vertical-align:top; font-size:18px;font-weight:normal;vertical-align:top;text-align:right; line-height:100%;}
.box-f_error .f_error-td { padding: 10px 15px 10px 15px;color:#000;vertical-align:top; font-size:18px; font-weight:normal;vertical-align:top; line-height:100%;}
.box-f_error .f_error-td .f_error-th { padding: 5px 10px 20px 0px;font-size:14px;}
.box-f_error .f_error-td .f_error-td { padding: 5px 10px 20px 0px;font-size:14px;color:#000;}
.box-f_error .f_error-trace { border:0 solid #ddd;border-width: 1px 0 0 12px; padding:10px; font-size:16px;margin:30px 0 10px 0;}
.box-f_error .f_error-source { margin-left:20px;}

</style>

<div class="box-f_error">

    <div class="f_error-head">:(</div>

    <table>
        <tr><th class="f_error-th">Exception</th><td class="f_error-td"><?= get_class($this->exception) ?></td></tr>
        <tr><th class="f_error-th">Code     </th><td class="f_error-td"><?= htmlspecialchars($this->code) ?></td></tr>
        <tr><th class="f_error-th">Message  </th><td class="f_error-td"><?= htmlspecialchars($this->msg) ?></td></tr>

        <? foreach ((array)get_object_vars($this->exception) as $k => $v) : ?>
            <?
                if ($k == '_metadata') {
                    continue;
                }
            ?>
            <tr>
                <th class="f_error-th"><?= htmlspecialchars($k) ?></th>
                <td class="f_error-td">

                    <? $metadata = $this->exception->_metadata[$k]; ?>

                    <? if ($metadata == 'sql') : ?>
                        <?= f_debug::highlight($v, 'sql') ?>
                    <? else : ?>
                        <?= htmlspecialchars($v) ?>
                    <? endif ?>

                </td>
            </tr>
        <? endforeach; ?>

        <tr><th class="f_error-th">File     </th><td class="f_error-td"><?= htmlspecialchars($this->file) ?></td></tr>
        <tr><th class="f_error-th">Line     </th><td class="f_error-td"><?= htmlspecialchars($this->line) ?></td></tr>
        <tr><th class="f_error-th">Source   </th><td class="f_error-td"><?= f_debug::highlightFile($this->file, 'php', $this->line, 10) ?></td></tr>
        <tr>
            <td colspan="2"  class="f_error-td">
            <? foreach ($this->trace as $k => $v) : ?>
                <div class="f_error-trace">
                    #<?= 
                        "$k {$v['class']}{$v['type']}{$v['function']}("
                        . htmlspecialchars(f_debug::dumpFunctionArgs($v['args']))
                        . ") {$v['file']}:{$v['line']}"
                    ?>
                </div>
                <div class="f_error-source">
                    <?= f_debug::highlightFile($v['file'], 'php', $v['line'], 10) ?>
                </div>
            <? endforeach ?>
            <div class="f_error-trace">#<?= ++$k ?></div>
            {main}
            </td>
        </tr>
        <tr><td colspan="2" class="f_error-foot">Fine <?= f::VERSION ?></td></tr>
    </table>
</div>
