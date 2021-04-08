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
        !empty($_POST["name"]) &&
        !empty($_POST["colour"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["key"])
    ){
        require_once '../../services/ApiKeyService.php';
        require_once '../../models/ApiKey.php';

        $apiKeyService = new ApiKeyService();
        $key = new ApiKey();
        $key->setEmail((string)htmlspecialchars($_POST["email"]));
        $key->setApiKey((string)htmlspecialchars($_POST["key"]));

        if ($apiKeyService->validate($key)){
            require_once("../../models/Event.php");
            require_once("../../services/EventService.php");

            $event = new Event();
            $eventService = new UserService();

            // set order property values
            $event->setName((string)htmlspecialchars($_POST["name"]));
            $event->setColour((string)htmlspecialchars($_POST["colour"]));

            // create the event
            if($eventService->addEvent($event)){
                // set response code - 201 created
                http_response_code(201);

                // tell the user
                echo json_encode(array("message" => "Event was created."));
            }

            // if unable to create the event, tell the user
            else{
                // set response code - 503 service unavailable
                http_response_code(503);

                // tell the user
                echo json_encode(array("message" => "Unable to create event."));
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
        echo json_encode(array("message" => "Unable to create event. Data is incomplete."));
    }
}
else{
    echo json_encode(array("message" => "Only post requests are allowed"));
}