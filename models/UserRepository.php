<?php 
class UserRepository extends DbRepository
{
    //追加
    public function insert($user_name, $password, $mail){
        $password = $this->hashPassword($password);
        $now = new DateTime();
        $sql = "
            INSERT INTO user(user_name, password, name, maile_address, created_at)
                VALUES(:user_name, :password, '名無し', :maile_address, :created_at)
        ";
        $stmt = $this->execute($sql, [
                ':user_name' => $user_name,
                ':password'  => $password,
                ':maile_address' => $mail,
                ':created_at'=> $now->format('y-m-d H:i:s')
        ]);
    }

    public function hashPassword($password){
        return sha1($password . 'SecretKey');
    }

    public function fetchByUserName($user_name){
        $sql = "SELECT * FROM user WHERE user_name = :user_name ";
        return $this->fetch($sql,[':user_name' =>$user_name]);
    }

    public function isUniqueUserName($user_name){
        $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name";
        $row = $this->fetch($sql, [
                    ':user_name' => $user_name
        ]);
        if($row['count'] ===0){
            return true;
        }
        return false;
    }
    
    //編集
    public function update($user){
        $sql = "UPDATE user SET name = :name, maile_address = :maile_address WHERE id = :id";
        $stmt = $this->execute($sql,$user);
    }

    
    public function fetchByUserId($id){
        $sql = "SELECT * FROM user WHERE id =:id";
        return $this->fetch($sql, [':id' => $id]);
    }

    //追加1　userテーブルを取得する
    public function getAllUser(){
        $sql = "SELECT *  
                FROM user
                ";
        return $this->fetchAll($sql);
    }
}