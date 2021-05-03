<?php


class model_Users extends Model
{
    public function insert($data)
    {
        $qr = $this->conn->prepare('INSERT INTO users(name, email,phone) VALUES (?, ?, ?)');
        $qr->bindParam(1, $data[0], PDO::PARAM_STR);
        $qr->bindParam(2, $data[1], PDO::PARAM_STR);
        $qr->bindParam(3, $data[2], PDO::PARAM_STR);
        if ($qr->execute()) {
            http_response_code(200);
        } else {
            http_response_code(400);
            exit;
        }
    }


    public function get_id_by_name($name)
    {
        $qr = $this->conn->prepare('SELECT id FROM ' . $this->table . ' WHERE name=?');
        $qr->bindValue(1, $name);
        if ($qr->execute()) {
            http_response_code(200);
        } else {
            http_response_code(400);
            exit;
        }
        return $qr->fetchObject();
    }

    private function generate_table()
    {
        $qr = $this->conn->prepare('CREATE TABLE users(id INT NOT NULL AUTO_INCREMENT,name varchar(100) not null,
                                                email varchar(100) not null, phone varchar(25) not null, primary key (id), unique(name))');
        if ($qr->execute()) {
            http_response_code(200);
        } else {
            http_response_code(400);
            exit;
        }
    }

    public function refresh_table()
    {
        $qr = $this->conn->prepare('DROP TABLE users');

        if ($qr->execute()) {
            $this->generate_table();
        } else {
            http_response_code(400);
            exit;
        }
    }
}