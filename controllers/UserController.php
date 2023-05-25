<?php
class UserController extends Controller
{
    //認証が必要なアクション名の配列
    protected $auth_actions = [
        'index',
        'logout'
    ];

    //ユーザ新規登録画面のアクション
    public function registerAction()
    {
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }
        $user_name = "";
        $password = "";
        //追加4
        $mail = "";
        $errors = [];

        if ($this->request->isPost()) {
            $token = $this->request->getPost('_token');

            if (!$this->checkCsrfToken('user/register', $token)) {
                return $this->redirect('/user/register');
            }
            //ポスト値を変数に代入　user_name $ password
            $user_name = $this->request->getPost('user_name');
            $password = $this->request->getPost('password');

            //追加4
            $mail = $this->request->getPost('maile_address');
            if (!strlen($user_name)) {
                $errors[] = 'ユーザIDを入力してください';
            } else if (!preg_match('/^\w{3,20}$/', $user_name)) {
                $errors[] = 'ユーザIDは半角英数字およびアンダースコアを3～20文字以内で入力してください';
            } else if (!$this->db_manager->get('User')->isUniqueUserName($user_name)) {
                $errors[] = 'ユーザIDは既に使用されています';
            }

            if (!strlen($password)) {
                $errors[] = 'パスワードを入力してください';
            } else if (4 > strlen($password) || strlen($password) > 30) {
                $errors[] = 'パスワードは4～30文字以内で入力してください';
            }
            //追加4
            if (!strlen($mail)) {
                $errors[] = 'メールアドレスを入力してください';
            }
            
            if (count($errors) === 0) {
                $this->db_manager->get('User')->insert($user_name, $password, $mail);


                $this->session->setAuthenticated(true);
                $user = $this->db_manager->get('User')->fetchByUserName($user_name);
                $this->session->set('user', $user);

                //mailの処理
                $to = $mail;
                $sbj = "件名：【新規登録完了】在籍管理アプリ";

                $msg = "―――――――――――――――――――――――――――――\n";

                $msg .= "新規ユーザ登録完了のお知らせ\n";
                $msg .= "―――――――――――――――――――――――――――――\n";
                $msg .= "この度は、「在籍管理アプリ」をご利用ありがとうございます。このメールは、ユーザ登録
                が完了したことをご確認いただくためお送りしているものです。\n";
                $msg .= "―――――――――――――――――――――――――――――\n";

                $msg .= "□ユーザ情報\n";
                $msg .= "ユーザ名：$user_name \n";
                $msg .= "パスワード：$password\n";
                $msg .= "―――――――――――――――――――――――――――――\n";

                $msg .= "□お知らせ";
                $msg .= "ご不明な点がございましたら気軽にご連絡ください。\n";
                $msg .= "また、今後とも宜しくお願い申し訳上げます。\n";

                $msg .= "お問い合わせ先\n";
                $msg .= "http://lolalhost:8080/Zaiseki/\n";

                mb_language("ja");
                mb_internal_encoding('UTF-8');
                $hdr = 'From: irie.oki@no1s.biz ' . "\r\n";
                if (isset($to)) {
                    // 日本語でメールを送信します
                    if (mb_send_mail($to, $sbj, $msg, $hdr))
                        echo "送信しました\n";
                    else
                        echo "送信に失敗しました\n";
                }
                return $this->redirect('/');
            }
        }
        return $this->render(
            [
                'user_name' =>  $user_name,
                'password'  =>  $password,
                'maile_address' => $mail,
                'errors'    =>  $errors,
                '_token'    =>  $this->generateCsrfToken('user/register')
            ],
            'register'
        );
    }

    //ユーザ情報更画面のアクション 
    public function indexAction()
    {
        $message = "";
        $user = $this->session->get('user');
        $errors = [];

        if ($this->request->isPost()) {
            $data["id"] = $user["id"];
            $data["name"] = $this->request->getPost("name");
            //追加4　修正
            $data["maile_address"] = $this->request->getPost("maile_address");
            if (!strlen($data["maile_address"])) {
                $errors[] = 'メールアドレスを入力してください';
            }

            if (count($errors) === 0) {
                $this->db_manager->get("User")->update($data);
                $user = $this->db_manager->get('User')->fetchByUserName($user["user_name"]);
                $this->session->set('user', $user);
                $message = "更新しました";
            }
        }
        return $this->render(['user' => $user, 'message' => $message, 'errors' => $errors]);
    }


    //ユーザログイン画面のアクション
    public function loginAction()
    {
        //認証済みならHOME画面へ遷移
        if ($this->session->isAuthenticated()) {
            return $this->redirect('/');
        }
        $user_name = "";
        $password = "";
        $maile_address = "";
        $errors = [];

        if ($this->request->isPost()) {
            $token = $this->request->getPost('_token');
            if (!$this->checkCsrfToken('user/login', $token)) {
                return $this->redirect('/user/login');
            }
            $user_name = $this->request->getPost('user_name');
            $password = $this->request->getPost('password');
            if (!strlen($user_name)) {
                $errors[] = 'ユーザIDを入力してください';
            }

            if (!strlen($password)) {
                $errors[] = 'パスワードを入力してください';
            }

            if (count($errors) === 0) {
                $user_repository = $this->db_manager->get('user');
                $user = $user_repository->fetchByUserName($user_name);
                if (!$user || ($user['password'] !== $user_repository->hashPassword($password))) {
                    $errors[] = 'ユーザIDかパスワードが不正です';
                } else {
                    //認証OKなのでホーム画面遷移
                    $this->session->setAuthenticated(true);
                    $this->session->set('user', $user);
                    return $this->redirect('/');
                }
            }
        }
        return $this->render([
            'user_name' => $user_name,
            'password'  => $password,
            'maile_address' => $maile_address,
            'errors'    => $errors,
            '_token'    => $this->generateCsrfToken('user/login')
        ]);
    }

    //ユーザログアウトのアクション
    public function logoutAction()
    {
        $this->session->clear();
        $this->session->setAuthenticated(false);
        return $this->redirect('/user/login');
    }
}
