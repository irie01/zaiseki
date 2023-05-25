<?php $this->setLayoutVar('title','ホーム') ?>
<h2>在籍情報一覧</h2>

<?php if(!empty($message)): ?>
<div id="message"><?php echo $message; ?></div>
<?php endif; ?>
<!-- 追加6 -->
<?php if(!empty($urgent)):?>
<div id="urgent">緊急の伝言メモがあります</div> 
<?php endif; ?>


<div>
    <a href="<?php echo $base_url; ?>/status/update">離籍メモを更新する</a>
    <a href="<?php echo $base_url; ?>/message/index">伝言メモを確認する</a>
</div>

<table>
    <tr>
        <th>伝言を残す</th>
        <th>全てのユーザに伝言メモを作成</th>
        <th>送信済みの伝言メモ</th>
        <th>名前</th>
        <th>状況</th>
        <th>行き先</th>
        <th>帰りの予定</th>
        <th>メモ</th>
        <th>最終更新時間</th>
    </tr>

    <?php foreach($statuses as $status): ?>
    <?php 
        $name = empty($status["name"]) ? "名無しさん" : $status["name"];
        $modified_at = empty($status["modified_at"]) ? "未設定" : date("Y年m月d日 H時i分", strtotime($status["modified_at"]));
        $present = !isset($status["present"]) ? "" : $present_list[$status["present"]];
    ?>
    <tr>
        <td>
            <a href="<?php echo $base_url; ?>/message/add?to_user_id=<?php echo $status["id"]; ?>">伝言</a>
        </td>
        <td>
            <a href="<?php echo $base_url; ?>/message/allAdd?to_user_id=<?php echo $status["id"]; ?>">伝言を作成</a>
        </td>
        <td>
            <a href="<?php echo $base_url; ?>/message/sentAdd?to_user_id=<?php echo $status["id"]; ?>">伝言を確認</a>
        </td>
        <td><?php echo $this->escape($name); ?></td>
        <td><?php echo $this->escape($present); ?></td>
        <td><?php echo $this->escape($status["destination"]); ?></td>
        <td><?php echo $this->escape($status["reach_time"]); ?></td>
        <td><?php echo $this->escape($status["memo"]); ?></td>
        <td><?php echo $modified_at; ?></td>
    </tr>
    <?php endforeach; ?>
        </table>