<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    
    // files needed to connect to database
    include_once 'config/database.php';
    include_once 'objects/user.php';                //user object

    $database = new Database();
    $db = $database->getConnection();

    $user = new User($db);

    $stmt = $user->getUsers();
    $userCount = $stmt->rowCount();

    echo json_encode($userCount);

    if($userCount > 0){        
        $userArr = array();
        $userArr["body"] = array();
        $userArr["userCount"] = $userCount;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = array(
                "id" => $id,
                "username" => $username,
                "usertype" => $usertype
            );

            array_push($userArr["body"], $e);
        }
        echo json_encode($userArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>