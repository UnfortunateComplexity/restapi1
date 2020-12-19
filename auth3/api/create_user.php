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
// Username --> Unique Key index in the database, prevents duplicates... can be better error handled

if((!empty($user->username)) && (!empty($user->password)) && (!empty($user->usertype)) )
	//as long as the REQUIRED fields are not empty, do:
{	

	$user->usertype=htmlspecialchars(strip_tags($user->usertype));
	$possibleTypes = array("admin","student","professor");

	if ( in_array(strtolower($user->usertype), $possibleTypes)){
		// usertype is possible

		$user->createUser();								// satisifies all conditions to CREATE
		// set response code
		http_response_code(200);

		// display success message
		echo json_encode(array("message" => "User with username: '".$user->username."' was created on the database."));
}

	// Yeah, I know its cringe, idk how to 'better' it
elseif (!in_array(strtolower($user->usertype), $possibleTypes)){
    	http_response_code(400);
		echo json_encode(array("message" => "Failed to create user. Usertype should be between: admin, student or professor."));
	}
}
 
else{ 
	// Simply unable to create user since atleast one of the required fields is empty.
    
    // set response code
    http_response_code(400);
 
    // display error message
    echo json_encode(array("message" => "Unable to create user. Make sure the username, password and usertype fields are all defined and not empty."));
}
?>