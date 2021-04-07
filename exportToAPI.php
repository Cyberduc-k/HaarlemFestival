<?php
//Export all users in the db to an external api
if($_POST){
    require_once("services/UserService.php");
    require_once("validate.php");

    //Clear errors
    unset($_SESSION["exportAPIError"]);

    //Get all users
    $userService = new UserService();
    $users = $userService->getAllAsArray();

    //If there are users, send them to api
    if(!empty($users)){
        $url = 'https://bramsierhuis.nl/PHP/api/users/create.php';
        $data = json_encode($users);

        // set request options
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($data) . "\r\n",
                'method'  => 'POST',
                'content' => $data
            )
        );

        //Make the call
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        //Show response
        echo "Response: <br><br><br>";
        echo print_r($result);
    }
    else{
        $_SESSION["exportAPIError"] = "No users found to export to API";
        header("Location: viewUsers.php");
        exit;
    }
}
else{
    echo "Only post requests are allowed";
}