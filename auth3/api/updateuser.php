<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/auth3/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/user.php';				//user object
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate product object
$user = new User($db);

// get POSTed data
$data = json_decode(file_get_contents("php://input"));
 
// set product property values
$user->username = $data->username;
$user->password = $data->password;
$user->usertype = $data->usertype;			
  
// update the user
if($user->updateUser()){
  
    // set response code - 200 ok
    http_response_code(200);
  
    // tell the user
    echo json_encode(array("message" => "User was updated."));
}
  
// if unable to update the user, tell the user
else{
  
    // set response code - 503 service unavailable
    http_response_code(503);
  
    // tell the user
    echo json_encode(array("message" => "Unable to update user."));
}
?>