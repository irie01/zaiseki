<table>
    <tr>
        <th>ユーザID</th>
        <td>
            <input type="text" name="user_name" value="<?php echo $this->escape($user_name); ?>"/>
        </td>
    </tr>
    <tr>
    <th>パスワード</th>
        <td>
            <input type="text" name="password" value="<?php echo $this->escape($password); ?>"/>
        </td>
    </tr>
    <th>メールアドレス</th>
        <td>
            <input type="text" name="maile_address" value="<?php echo $this->escape($maile_address); ?>"/>
        </td>
    </tr>
</table>