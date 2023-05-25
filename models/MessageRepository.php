<?php 
class MessageRepository extends DbRepository
{
    public function getModel(){
        return [
            "to_user_id" =>"",
            "pass_sec"   =>"",
            "pass_tel"   =>"",
            "pass_name"  =>"",
            "msec"       =>"",
            "message"    =>"",
            "is_urgent"  =>"",
            "from_user_id"=>""
        ];
    }

       //追加実装1
       public function getMessageByToUserId($to_user_id){
        $sql = "SELECT
                    message_id, to_user_id, pass_sec, pass_tel,
                    pass_name, msec, `message`, from_user_id
                FROM message 
                WHERE to_user_id = :to_user_id ";
            return $this->fetchAll($sql, [
                "to_user_id" => $to_user_id
            ]);
    }

    public function delete($message_id){
        $sql = "DELETE FROM message WHERE message_id = :message_id";
        return $this->execute($sql, [
            "message_id" => $message_id
        ]);
    }
    
    public function insert($message){  
        $sql = "INSERT INTO message
                (to_user_id, pass_sec, pass_tel, pass_name, msec, message, is_urgent, from_user_id)
                VALUES
                (:to_user_id, :pass_sec, :pass_tel, :pass_name, :msec, :message, :is_urgent, :from_user_id)
                ";
                $stmt = $this->execute($sql, $message);
    }

    public function deleteByMessageIdAndToUserId($message_id, $to_user_id){
        $sql = "DELETE FROM message
                WHERE message_id = :message_id
                AND to_user_id = :to_user_id";
        $data = [
            "message_id" => $message_id,
            "to_user_id" => $to_user_id     
        ];
        $this->execute($sql,$data);
    }

    public function deleteByMessageId($message_id){
        $sql = "DELETE FROM message
        WHERE message_id = :message_id";
        $data = [
            "message_id" => $message_id,  
        ];
        $this->execute($sql,$data);

    }
    //追加2 userテーブルと結合
    public function fetchMessageByUserId($to_user_id){
        $sql = "SELECT *  
                FROM message as m
                INNER JOIN user as u
                ON m.from_user_id = u.id  
                WHERE m.to_user_id = :to_user_id
                ";
        $data = [
            "to_user_id" => $to_user_id     
        ];
        return $this->fetchAll($sql,$data);
    }
    
    //自分に送られてきているときのデータを取り出す toID = MyID
    public function MessageByUserId($to_user_id){
        $sql = "SELECT *  
                FROM message as m
                INNER JOIN user as u
                ON m.from_user_id = u.id  
                WHERE m.to_user_id = :to_user_id
                ";
        $data = [
            "to_user_id" => $to_user_id     
        ];
        return $this->fetchAll($sql,$data);
    }

    public function getMessageByToFormUserId($from_user_id){
        $sql = "SELECT *
                FROM message as m
                INNER JOIN user as u
                ON m.from_user_id = u.id  
                WHERE m.from_user_id = :from_user_id
                ";
        $data = [
            "from_user_id" => $from_user_id    
        ];
        return $this->fetchAll($sql,$data);
    }

    //追加6
    public function getUrgent($to_user_id){
        $sql = "SELECT * 
                FROM message
                WHERE to_user_id = :to_user_id 
                AND is_urgent = 1";
        $data = [
            "to_user_id" => $to_user_id  
        ];
        return $this->fetch($sql, $data);
    }
    
} 