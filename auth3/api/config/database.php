<?php

// Get MySQL database connection.

class Database{

	private $host = "localhost";
	private $db_name = "userauth";
	private $username = "root";
    private $password = "";
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
        try{
            //connecting with above credentials
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
        }catch(PDOException $exception){
        //Error connecting to the database sadge
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>