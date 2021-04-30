<?php
include_once _config.'Database.php';

class Model {
    protected $conn;
    protected $table;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->Connect();
        $this->table = $this->get_table_name();
    }

    private function get_table_name() {
        $name = explode('_', get_class($this));
        return strtolower($name[1]);
    }


    public function get_all(){
        $qr = $this->conn->query('SELECT * FROM '.$this->table);
        return $qr->fetchAll();
    }

    public function get_row_by_id($id) {
        $qr = $this->conn->prepare('SELECT * FROM '.$this->table.' WHERE id=?');
        $qr->bindValue(1, $id);
        $qr->execute();
        return  $qr->fetchObject();
    }

    public function insert($data){

    }

    private function generate_table() {

    }

    public function refresh_table() {

    }
}