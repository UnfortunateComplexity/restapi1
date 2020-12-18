<?php

/* 'user' object
    *The User class manages the CRUD operations:
    __construct() —> Makes the database connection ready.
    getEmployees() — Get all records.
    getSingleEmployee() — Get single records.
    createEmployee() — Create record.
    updateEmployee() — Update record.
    deleteEmployee() — Fetch single record
*/

class User{ 
    // database connection and table name
    private $conn;

    private $table_name = "users";
 
    // object properties / columns
    public $id;
    public $username;
    public $password;
    public $usertype;
 
    // Database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function getUsers(){

        $sqlQuery = "SELECT id, username, usertype FROM " . $this->table_name . "";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }
 
// create new user record
function create(){ 
    // insert query
     $query_create = "INSERT INTO " . $this->table_name . "
            SET  username = :username, usertype = :usertype, password = :password";
    // prepare the insert query
    $stmt = $this->conn->prepare($query_create);
 
    // Clean up the inputs
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->usertype=htmlspecialchars(strip_tags($this->usertype));
    $this->password=htmlspecialchars(strip_tags($this->password));
 
    // bind the values
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':usertype', $this->usertype);

    // hash the password before saving to the database
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
 
    // execute the query, also check if query was successful
    if($stmt->execute()){ return true; }
 
    //else query execution failed
    return false;
}
 
// check if given username exists in the database
function usernameExists(){
 
    // query to check if username exists
    $query = "SELECT *
            FROM " . $this->table_name . "
            WHERE username = ?
            LIMIT 0,1";
 
    // prepare the query
    $stmt = $this->conn->prepare( $query );
 
    // clean the input username
    $this->username=htmlspecialchars(strip_tags($this->username));
 
    // bind it
    $stmt->bindParam(1, $this->username);
 
    // execute the query
    $stmt->execute();
 
    // get number of rows
    $num = $stmt->rowCount();
 
    // if username exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        // assign values to object properties
        $this->id = $row['id'];
        $this->username = $row['username'];
        $this->usertype = $row['usertype'];
        $this->password = $row['password'];
 
        // return true because the username exists in the database
        return true;
    }
 
    // false --> username is not in database.
    return false;
}
 
// update() method will be here
// update a user record
public function update(){
 
    // if password needs to be updated
    $password_set=!empty($this->password) ? ", password = :password" : "";
 
    // if no POSTed password, do not update the password
    $query = "UPDATE ".$this->table_name. "
            SET
                username = :username,
                usertype = :usertype
                {$password_set}
            WHERE id = :id";
 
    // prepare the query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->username=htmlspecialchars(strip_tags($this->username));
    $this->usertype=htmlspecialchars(strip_tags($this->usertype));
 
    // bind the values from the form
    $stmt->bindParam(':username', $this->username);
    $stmt->bindParam(':usertype', $this->usertype);
 
    // hash the password before saving to database
    if(!empty($this->password)){
        $this->password=htmlspecialchars(strip_tags($this->password));
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(':password', $password_hash);
    }
 
    // unique ID of record to be edited
    $stmt->bindParam(':id', $this->id);
 
    // execute the query
    if($stmt->execute()){
        return true;
    }
 
    return false;
}

function deleteUser(){
     // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
  
    // prepare query
    $stmt = $this->conn->prepare($query);
  
    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
  
    // bind id of record to delete
    $stmt->bindParam(1, $this->id);
  
    // execute query
    if($stmt->execute()){
        return true;
    }
  
    return false;

}

}