<?php

class Database {

    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'songbook';

    public function connect() {
        $con = new mysqli($this->host, $this->username, $this->password, $this->database);

        if($con->connect_error) {
            die("Connect failed: " . $con->connect_error);
        }

        return $con;
    }

}

?>