<?php 
class StatusController extends Controller
{
    //ログインが必要なアクションを
    protected $auth_actions = [
        'index',
        'update'
    ];

    protected $present_list = [
        "在籍",
        "外出",
        "会議中",
        "食事"
    ];

    public function indexAction(){
        
        $user = $this->session->get ('user');
        $statuses = $this->db_manager->get('Status')->getAll();
        $urgent = $this->db_manager->get('message')->getUrgent($user["id"]);

        //メッセージをセッションから取得
        $message = $this->session->get("message");
        $this->session->remove("message");
        return $this->render([
            'statuses' => $statuses,
            'user'     => $user,
            'present_list' => $this->present_list,
            'message'  => $message,
            'urgent' => $urgent
        ]);
    }

    public function updateAction(){
        $message = "";
        $errors = [];
        //userテーブルをセッションに記録
        $user = $this->session->get('user');
        //配列としてキーをカラム名、値をフィールドで受け取り変数に代入
        $status = $this->db_manager->get("status")->getStatus($user['id']);
      
        if($this->request->isPost()){
            $model = $this->db_manager->get("status")->getModel();
            $keys = array_keys($model);
            foreach($keys as $key ){
                $status[$key] = $this->request->getPost($key);
            }
            if(isset($status ['user_id'])){
                $this->db_manager->get("status")->update($status);
                $message = "更新しました";
            }else{
                //$user["id"]にはstatusテーブルのuser_idが入っている
                $status ["user_id"] = $user["id"];
                $this->db_manager->get("status")->insert($status);
                $message = "追加しました";
            }
        }
        return $this->render([
            'user' => $user,
            'status' => $status,
            'message'=> $message,
            'errors' => $errors,
            'present_list' => $this->present_list
        ]);
    }
}