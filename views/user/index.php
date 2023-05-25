<?php $this->setLayoutVar('title','ユーザ情報') ?>
<h2>ユーザ情報</h2>

<?php if(!empty($message)): ?>
    <div id="message"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors',['errors' => $errors]); ?>
<?php endif; ?>

<form action="<?php echo $base_url; ?>/user" method="post">
    <table>
        <tr>
            <th>ユーザID</th>
            <td><?php echo $this->escape($user['user_name']); ?></td>
        </tr>
        
        <tr>
            <th>名前</th>
            <td><input type="text" name="name" value="<?php echo $this->escape($user['name']); ?>"/></td>
        </tr>
        <tr>
            <th>メールアドレス</th>
            <td><input type="text" name="maile_address" value="<?php echo $this->escape($user['maile_address']); ?>"/></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" value="登録"/></td>
        </tr>
    </table>
</form>