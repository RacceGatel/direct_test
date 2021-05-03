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

        if ($qr->execute()) {
            http_response_code(200);
        } else {
            http_response_code(400);
            exit;
        }

        http_response_code(200);
    }

    private function generate_table() {
        $qr = $this->conn->prepare('CREATE TABLE user_pass(id INT NOT NULL,pass char(60),primary key (id))');
        if ($qr->execute()) {
            http_response_code(200);
        } else {
            http_response_code(400);
            exit;
        }
    }

    public function refresh_table() {
        $qr = $this->conn->prepare('DROP TABLE user_pass');

        if ($qr->execute()) {
            $this->generate_table();
        } else {
            http_response_code(400);
            exit;
        }
    }
}