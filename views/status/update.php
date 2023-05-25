<h2>離籍メモ更新</h2>

<?php if(!empty($message)): ?>
<div id="message"><?php echo $message; ?></div>
<?php endif; ?>

<form action="<?php echo $base_url; ?>/status/update" method="post">
<table>
    <tr>
        <th>名前</th>
        <td><?php echo $this->escape($user["name"]); ?></td>
    </tr>
    <tr>
        <th>在籍状況</th>
        <td>
            <?php
            foreach($present_list as $i => $p){
            ?>
                <label><input type="radio" name="present" value="<?php echo $i; ?>"
            <?php 
            if($status["present"] == $i ) echo "checked=\"checked\""; ?>/><?php  echo $p; ?></label>
                <?php
            }
            
            ?>
        </td>
    </tr>
    <tr>
        <th>行き先</th>
        <td>
            <input type="text" name="destination" value="<?php echo $this->escape($status["destination"]); ?>" />
        </td>
    </tr>
    <tr>
        <th>戻り時刻</th>
        <td><input type="text" name="reach_time" value="<?php echo $this->escape($status["reach_time"]); ?>"/>
        </td>
    </tr>
    <tr>
        <th>メモ</th>
        <td>
            <input type="text" name="memo" value="<?php echo $this->escape($status["memo"]); ?>" />
                
        </td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="更新" /></td>
    </tr>
    </table>
    </form>