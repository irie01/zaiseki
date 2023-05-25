<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php if (isset($title)): echo $this->escape($title) . '-';endif; ?>在籍管理アプリケーション
    </title>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $base_url; ?>/css/style.css" />
    </head>
    <body>
        <div id="header">
            <h1><a href="<?php echo $base_url; ?>/">在籍管理アプリ</a></h1>
        </div>
        <div id="nav">
            <p>
                <?php if ($session->isAuthenticated()): ?>
                    <?php
                        if(empty($this->defaults["session"]->get("user")["name"])){
                            $name = "名無し";
                        }else{
                            $name = $this->defaults["session"]->get("user")["name"];
                        }
                    ?>
                   <?php echo $name; ?>さん
                    <a href="<?php echo $base_url; ?>/">ホーム</a>
                    <a href="<?php echo $base_url; ?>/user">ユーザ情報</a>
                    <a href="<?php echo $base_url; ?>/user/logout">ログアウト</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>/user/login">ログイン</a>
                    <a href="<?php echo $base_url; ?>/user/register">新規ユーザ登録</a>
                <?php endif; ?>
            </p>
        </div>
        <div id="main">
            <?php echo $_content; ?>
        </div>
    </body>
</html>