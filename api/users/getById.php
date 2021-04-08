<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

if(!$_POST){
    //The email and key to validate and the name and the user id to retreive
    if(isset($_GET["id"]) && isset($_GET["email"]) && isset($_GET["key"])) {
        require_once '../../services/ApiKeyService.php';
        require_once '../../models/ApiKey.php';

        //Validate key
        $apiKeyService = new ApiKeyService();
        $key = new ApiKey();
        $key->setEmail((string)htmlspecialchars($_GET["email"]));
        $key->setApiKey((string)htmlspecialchars($_GET["key"]));

        if ($apiKeyService->validate($key)){
            require_once '../../services/UserService.php';

            //Get the user by id from db
            $userService = new UserService();
            $user = $userService->getByIdAsArray((int)htmlspecialchars($_GET["id"]));

            // check if more than 0 records found
            if ($user != null) {
                // set response code - 200 OK
                http_response_code(200);

                // show user data in json format
                echo json_encode($user);
            } else {
                // set response code - 404 Not found
                http_response_code(404);

                // tell the user no user found
                echo json_encode(array("message" => "No user found."));
            }
        }
        else{
            // set response code - 404 forbidden
            http_response_code(403);

            // tell the user
            echo json_encode(array("message" => "Forbidden. Invalid credentials."));
        }
    }
    // tell the user data is incomplete
    else{
        // set response code - 400 bad request
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to get user. Data is incomplete."));
    }
}
else{
    echo json_encode(array("message" => "Only get requests are allowed"));
}