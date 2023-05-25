<h2>伝言メモ作成 (全ユーザ対象)</h2>
<form action="<?php echo $base_url; ?>/message/allAdd" method="post">
<table>
    <tr>
        <th>相手先部門</th>
        <td><input type="text" name="pass_sec" /></td>
    </tr>
    <tr>
        <th>相手先電話</th>
        <td><input type="text" name="pass_tel" /></td>
    </tr>
    <tr>
        <th>相手先名前</th>
        <td><input type="text" name="pass_name" /></td>
    </tr>
    <tr>
        <th>伝言区分</th>
        <td>
            <?php foreach($msec_list as $i => $m){
            ?>
                <label><input type="radio" name="msec" value="<?php echo $i; ?>"<?php if($i === 0 )
                        echo "checked=\"checked\""; ?>/><?php echo $m; ?></label>
            <?php
            }
            ?>
        </td>
    </tr>
    <tr>
        <th>伝言</th>
        <td><textarea name="message"></textarea></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="submit" value="更新" />
        </td>
    </tr>
</table>
</form>