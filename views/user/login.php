<?php $this->setLayoutVar('title','ログイン') ?>

<h2>ログイン</h2>
<form action="<?php echo $base_url; ?>/user/login" method="post">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>" />
    <?php if(isset($errors) && count($errors) > 0): ?>
        <?php echo $this->render('errors',array('errors'=>$errors)); ?>
    <?php endif; ?>
    <?php echo $this->render('user/inputs', [
            'user_name' => $user_name, 'password' => $password, 'maile_address' => $maile_address
    ]); ?>
    <p>
        <input type="submit" value="ログイン" />
    </p>
</form>