<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

// get posted data
// make sure variables are set
if($_POST){
    if(
        !empty($_POST["firstname"]) &&
        !empty($_POST["lastname"]) &&
        !empty($_POST["password"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["apiMail"]) &&
        !empty($_POST["key"])
    ){
        require_once '../../services/ApiKeyService.php';
        require_once '../../models/ApiKey.php';

        $apiKeyService = new ApiKeyService();
        $key = new ApiKey();
        $key->setEmail((string)htmlspecialchars($_POST["apiMail"]));
        $key->setApiKey((string)htmlspecialchars($_POST["key"]));

        if ($apiKeyService->validate($key)){
            require_once("../../models/User.php");
            require_once("../../services/UserService.php");

            $user = new User();
            $userService = new UserService();

            // set user property values
            $user->setFirstname((string)htmlspecialchars($_POST["firstname"]));
            $user->setLastname((string)htmlspecialchars($_POST["lastname"]));
            $user->setPassword((string)htmlspecialchars($_POST["password"]));
            $user->setEmail((string)htmlspecialchars($_POST["email"]));
            $user->setUsertype((int)htmlspecialchars($_POST["usertype"]));

            // create the user
            if($userService->create($user)){
                // set response code - 201 created
                http_response_code(201);

                // tell the user
                echo json_encode(array("message" => "User was created."));
            }

            // if unable to create the user, tell the user
            else{
                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("message" => "Unable to create user. Perhaps the mail is taken already?"));
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
        echo json_encode(array("message" => "Unable to create user. Data is incomplete."));
        echo print_r($_POST);

    }
}
else{
    echo json_encode(array("message" => "Only post requests are allowed"));
}