<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");

if(!$_POST){
    if(isset($_GET["key"]) && isset($_GET["email"])){
        include_once '../../services/UserService.php';
        include_once '../../services/ApiKeyService.php';
        include_once '../../models/ApiKey.php';

        $apiKeyService = new ApiKeyService();
        $key = new ApiKey();
        $key->setEmail((string)htmlspecialchars($_GET["email"]));
        $key->setApiKey((string)htmlspecialchars($_GET["key"]));

        if ($apiKeyService->validate($key)){
            $userService = new UserService();
            $users = $userService->getAllAsArray();

            // check if more than 0 records found
            if($users != null){
                // set response code - 200 OK
                http_response_code(200);

                // show users data in json format
                echo json_encode($users);
            }
            else{
                // set response code - 404 Not found
                http_response_code(404);

                // tell the user no users found
                echo json_encode(array("message" => "No users found."));
            }
        }
        else{
            // set response code - 404 forbidden
            http_response_code(403);

            // tell the user
            echo json_encode(array("message" => "Forbidden. Invalid credentials."));
        }
    }
    else{
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to get users. Data is incomplete."));
    }
}
else{
    echo json_encode(array("message" => "Only get requests are allowed"));
}