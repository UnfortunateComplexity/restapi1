<?php
// required headers
header("Access-Control-Allow-Origin: http://localhost/auth3/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// database connection will be here

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
$user->usertype = $data->usertype;			//optional since usertype is null by default (choices should be 'student','admin' or 'professor')

// create the user
if((!empty($user->username)) && (!empty($user->password)) && (!empty($user->usertype)) && ($user->create()))
{	

	$user->usertype=htmlspecialchars(strip_tags($user->usertype));
	$possibleTypes = array("admin","student","professor");

	if ( in_array(strtolower($user->usertype), $possibleTypes)){
		//echo json_encode(array("message" => "Usertype possible."));
		
		// set response code
		http_response_code(200);

		// display message: user was created
		echo json_encode(array("message" => "User with username: '".$user->username."' was created on the database."));
}

	// WHY DOES IT STILL PRINT DESPITE usertype BEING POSSIBLE??!
		echo json_encode(array("message" => "Usertype should be between: admin, student or professor."));
}
 
// message if unable to create user
else{ 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>