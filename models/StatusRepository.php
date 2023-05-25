<?php
class StatusRepository extends DbRepository
{
    public function getModel(){
        return [
            "present" => "",
            "destination" => "",
            "reach_time" => "",
            "memo" => ""
        ];
    }

    public function getAll(){
        $sql = "SELECT u.id, u.name, s.present, s.destination, s.reach_time, s.memo, s.modified_at
                FROM user as u
                LEFT JOIN status as s
                ON u.id = s.user_id
                ";
                return $this->fetchAll($sql);
    }

    public function getStatus($user_id){
        $sql = "SELECT   user_id, present, destination, reach_time, memo
                FROM status WHERE user_id = :user_id";
        return $this->fetch($sql, [
                "user_id" => $user_id
        ]);
    }

    public function update($status){
        $sql = "UPDATE status SET 
                    present = :present,
                    destination = :destination,
                    reach_time = :reach_time,
                    memo = :memo,
                    modified_at = now()
                WHERE user_id = :user_id
                ";
                $stmt = $this->execute($sql, $status);
    }

    public function insert($status){    
        $sql = "INSERT INTO status
                (user_id, present, destination, reach_time, memo, modified_at, created_at)
                VALUES
                (:user_id, :present, :destination, :reach_time, :memo, now(),now())";
        $stmt = $this->execute($sql, $status);
    }
}