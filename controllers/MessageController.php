<?php 
class MessageController extends Controller {
    protected $auth_actions = [
        'index',
        'add'
    ];
    protected $msec_list = [
        "電話",
        "伝言",
        "折り返し",
        "来訪"
    ];

    //伝言メモ確認画面
    public function indexAction(){
        //セッションにuserテーブルを記録
        $user = $this->session->get('user');  
        //二次元配列で、to_uUser_idが一致したmessageテーブルを代入 //追加実装2
        $messages = $this->db_manager->get('message')->fetchMessageByUserId($user['id']); 
        
        foreach ($messages as $key => $message){
            if($message["is_urgent"] === 0){
                $messages[$key]["is_urgent"] = "普通";
            }else{
                $messages[$key]["is_urgent"] = "緊急";
            }
        };      
     
     
        if($this->request->isPost()){
            //削除用のフラグ
            $delete_flg = false;
            //二次元配列でdelteのgetpostを取得
            $delete = $this->request->getPost("delete");
          
            foreach ($delete as $message_id => $d){     
                if ($d[0] == 1){    
                    $this->db_manager->get('message')->deleteByMessageIdAndToUserId(
                        $message_id, $user['id']);
                        $delete_flg = true;
                }
            }
            if($delete_flg){
                $this->session->set ("message", "伝言メモを削除しました");
            }else{
                $this->session->set("message", "伝言メモを残しています");
            }
            return $this->redirect('/');
        }
        return $this->render([
            'messages' => $messages,
            'user'     => $user,
            'msec_list'=> $this->msec_list
        ]);
    }


     //送信済みの伝言メモ確認画面
     public function sentAddAction(){
        //セッションにuserテーブルを記録
        $user = $this->session->get('user');
        $messages = $this->db_manager->get('message')->getMessageByToFormUserId($user['id']); 
      
        if($this->request->isPost()){
            //削除用のフラグ
            $delete_flg = false;
            //二次元配列でdelteのgetpostを取得
            $delete = $this->request->getPost("delete");
          
            //echo "<pre>"; var_dump($delete);exit;
            foreach ($delete as $message_id => $d){     
                if ($d[0] == 1){    
                    $this->db_manager->get('message')->deleteByMessageId(
                        $message_id);
                        $delete_flg = true;
                }
            }
            if($delete_flg){
                $this->session->set ("message", "伝言メモを削除しました");
            }else{
                $this->session->set("message", "伝言メモを残しています");
            }
            return $this->redirect('/');
        }
        return $this->render([
            'messages' => $messages,
            'user'     => $user,
            'msec_list'=> $this->msec_list,
        ]);
    }


    //伝言メモ登録画面
    public function addAction(){
        //userテーブルをセッションから取得
        $user = $this->session->get('user');
        //モデルを取得
        $message = $this->db_manager->get("message")->getModel(); 
        if($this->request->isPost()){
            $keys = array_keys($message);
            foreach($keys as $key){
                $message[$key] = $this->request->getPost($key);
            }
            if(! $this->db_manager->get("user")->fetchByUserId($message["to_user_id"])){
                $this->session->set("message", "宛先ユーザが存在しません");
                return $this->redirect('/');
            }
            //$messageのfrom_user_idに$userのidを入れる
            $message["from_user_id"] = $user["id"];
            $this->db_manager->get("message")->insert($message);
            $senduser = $this->db_manager->get("user")->fetchByUserId($message["to_user_id"]);
            
            //メール送信
            $to =  $senduser["maile_address"];   
            $sbj = "【緊急伝言メモ】在籍管理アプリ";
        
            $msg = "―――――――――――――――――――――――――――――\n";
            $msg .= "緊急伝言メモのお知らせ\n";
            $msg .= "―――――――――――――――――――――――――――――\n";
            $msg .= "救急対応が必要な伝言メモが登録されました\n";
            $msg .= "―――――――――――――――――――――――――――――\n";
           
            $msg .= "□伝言メモ\n";
            $msg .= "送信者名： {$user["name"]}\n";
            $msg .= "相手先部門：{$_POST["pass_sec"]}\n";
            $msg .= "相手先電話： {$_POST["pass_tel"]}\n";
            $msg .= "相手先名前： {$_POST["pass_name"]}\n";
            $msg .= "伝言区分： {$_POST["msec"]}\n";
            $msg .= "伝言： {$_POST["message"]} \n";
            $msg .= "―――――――――――――――――――――――――――――\n";
        
            $msg .= "□お知らせ";
            $msg .= "ご不明な点がございましたら気軽にご連絡ください。\n";
            $msg .= "また、今後とも宜しくお願い申し訳上げます。\n";
        
            $msg .= "お問い合わせ先\n";
            $msg .= "http://lolalhost:8080/Zaiseki/\n";

            mb_language("ja");
            mb_internal_encoding('UTF-8');
            $hdr = 'From: irie.oki@no1s.biz '."\r\n";

        if( strlen("to") && $message["is_urgent"] == 1)
        {
            if(isset( $senduser["maile_address"])){
            // 日本語でメールを送信します
            if(mb_send_mail($to,$sbj,$msg,$hdr))
                echo "送信しました\n";
            }
            else
                echo "送信に失敗しました\n";
        }

            $this->session->set("message", "伝言メモを登録しました");
            return $this->redirect('/');
        }else{

            $to_user_id = $this->request->getGet("to_user_id");
            $to_user = $this->db_manager->get("user")->fetchByUserId($to_user_id);

            if(!$to_user){
                $this->session->set("message", "宛先ユーザが存在しません");
                return $this->redirect('/');
            }
            $message["to_user_id"] = $to_user_id;
            $message["to_user_name"] = $to_user["name"];
         
        }
        return $this->render([
            'message' => $message,
            'msec_list' => $this->msec_list,
        ]);
    }

    //全員に伝言を送信するアクション
    public function allAddAction()
    {
         //userテーブルをセッションから取得
        $user = $this->session->get('user');

        //モデルを取得
        $message = $this->db_manager->get("message")->getModel(); 
        if($this->request->isPost()){
            $keys = array_keys($message);
            foreach($keys as $key){
                $message[$key] = $this->request->getPost($key);
            }
            //$messageのfrom_user_idに$userのidを入れる
            
            //追加実装1
            $message["from_user_id"] = $user["id"];
            //userテーブルを取得 userテーブル文インサートするため
            $users = $this->db_manager->get("user")->getAllUser();
            foreach($users as $data){
                //IDが一致したらスキップ　（自分を飛ばす処理）
                if($user["id"] == $data["id"]){
                    continue;
                }
                $message["to_user_id"] = $data["id"];
                $this->db_manager->get("message")->insert($message);
            }    
            $this->session->set("message", "伝言メモを登録しました");
            return $this->redirect('/');
        }
        return $this->render([
            'msec_list' => $this->msec_list
        ]);
    }

    
}