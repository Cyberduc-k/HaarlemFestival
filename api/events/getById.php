<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");

if(!$_POST){
    if(isset($_GET["id"]) && isset($_GET["email"]) && isset($_GET["key"])) {
        require_once '../../services/ApiKeyService.php';
        require_once '../../models/ApiKey.php';

        $apiKeyService = new ApiKeyService();
        $key = new ApiKey();
        $key->setEmail((string)htmlspecialchars($_GET["email"]));
        $key->setApiKey((string)htmlspecialchars($_GET["key"]));

        if ($apiKeyService->validate($key)){
            require_once '../../services/EventService.php';

            $eventService = new UserService();
            $event = $eventService->getByIdAsArray((int)htmlspecialchars($_GET["id"]));

            // check if more than 0 records found
            if ($event != null) {
                // set response code - 200 OK
                http_response_code(200);

                // show event data in json format
                echo json_encode($event);
            } else {
                // set response code - 404 Not found
                http_response_code(404);

                // tell the user no event found
                echo json_encode(array("message" => "No event found."));
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
        echo json_encode(array("message" => "Unable to get event. Data is incomplete."));
    }
}
else{
    echo json_encode(array("message" => "Only get requests are allowed"));
}