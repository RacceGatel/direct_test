<?php


class model_user_pass extends model
{
    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->Connect();
        $this->table = "user_pass";
    }

    public function insert($data){
        $qr = $this->conn->prepare('INSERT INTO user_pass(id, pass) VALUES (?, ?)');
        $qr->bindValue(1, $data[0]);
        $qr->bindValue(2, $data[1]);
        return $qr->execute();
    }

    private function generate_table() {
        $qr = $this->conn->prepare('CREATE TABLE user_pass(id INT NOT NULL,pass char(60),primary key (id))');
        try {
            $qr->execute();
        } catch (PDOException $ex) {
            return $ex;
        }
    }

    public function refresh_table() {
        $qr = $this->conn->prepare('DROP TABLE user_pass');
        try {
            $qr->execute();
            $this->generate_table();
        } catch (PDOException $ex) {
            return $ex;
        }
    }
}