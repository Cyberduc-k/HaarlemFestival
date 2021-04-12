<?php

require_once("services/EditEmailKeyService.php");
require_once("services/UserService.php");
require_once("services/UserEditsService.php");
require_once("models/ResetKey.php");
require_once("models/User.php");

if (!isset($_SESSION)) session_start();

// Form has been filled in
if ($_GET) {
    if (!empty($_GET["editId"]) && !empty($_GET["key"])) {
        $userEditsService = new UserEditsService();

        if (!empty($_GET["type"])) {
            $editEmailKeyService = new EditEmailKeyService();

            // Get the EditEmailKey for this user from the database
            $editEmailKey = $editEmailKeyService->getById((int)htmlentities($_GET["editId"]));

            // If a key exists
            if (!is_null($editEmailKey)) {
                // If it is not expired and matches
                if ($editEmailKey->getExpDate()->getTimestamp() > time()) {
                    if ($editEmailKey->getOldKey() == (string) htmlentities($_GET["key"])) {
                        if ($editEmailKeyService->verifyKey($editEmailKey->getOldKey(), false))
                            echo "Verification succesfull";
                        else
                            echo "Verification failed";
                    } else if ($editEmailKey->getNewKey() == (string) htmlentities($_GET["key"])) {
                        if ($editEmailKeyService->verifyKey($editEmailKey->getNewKey(), true))
                            echo "Verification succesfull";
                        else
                            echo "Verification failed";
                    } else {
                        echo "Your key is invalid, did you copy the entire link?";
                    }

                    if (!empty($_SESSION["bothKeysOK"]) && (int)$_SESSION["bothKeysOK"] == 1) {
                        if ($userEditsService->performEdit($editEmailKey->getId())) {
                            echo " Your info has been updated. <a href='/'> Home </a>";
                            unset($_SESSION["bothKeysOK"]);
                        } else {
                            echo "Edit couldn't be executed";
                        }
                    }
                } else {
                    echo "Your link has expired, request a new one";
                }
            } else {
                echo "We cannot find the proper link for this user in our system, can you request a new one?";
            }
        } else {
            $editKeyService = new EditKeyService();

            // Get the EditKey for this user from the database
            $editKey = $editKeyService->getById((int)htmlentities($_GET["editId"]));

            // If a key exists
            if (!is_null($editKey)) {
                // If it is not expired and matches
                if ($editKey->getExpDate()->getTimestamp() > time()) {
                    if ($editKey->getKey() == (string) htmlentities($_GET["key"])) {
                        if ($userEditsService->performEdit($editKey->getId())) {
                            echo "Info has been updated. <a href='/'> Home </a>";
                        } else {
                            echo "Edit couldn't be executed";
                        }
                    } else {
                        echo "Your key is invalid, did you copy the entire link?";
                    }
                } else {
                    echo "Your link has expired, request a new one";
                }
            } else {
                echo "We cannot find the proper link for this user in our system, can you request a new one?";
            }
        }
    } else {
        echo "Your link is invalid, did you copy the entire link?";
    }
}

?>
