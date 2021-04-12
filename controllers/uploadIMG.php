<?php

require_once __DIR__.'/../services/ImageService.php';
require_once __DIR__.'/../models/Image.php';

//file upload path and other variables like filename
$targetDir = __DIR__.'/../uploads/uploadedIMG/';
$contentPageId = $_GET["contentId"];
$event = $_GET["event"];
$statusMsg = '';

if (!empty($_FILES["file"]["name"])) {

    // Create new instance of the image service
    $service = new ImageService();

    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg');

    // Count uploaded files in array
    var_dump($_FILES);
    $total = count($_FILES['file']['name']);

    // Loop through each filec
    for ( $i=0 ; $i < $total ; $i++ ) {

        // Create needed variables like filepath and filetype
        $fileName = htmlspecialchars(basename($_FILES["file"]["name"][$i]));
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

        if (in_array($fileType, $allowTypes)) {

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];

            // Make sure we have a file path
            if ($tmpFilePath != "") {

                // Make sure id is correctly set
                $images = $service->getAll();
                if (empty($images))
                    $id = 1;
                else
                    $id = array_pop($images)->getId() + 1;

                // Setup our new file path
                $newFilePath = $targetDir . $id . "-" . $_FILES['file']['name'][$i];

                // Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                    $statusMsg = "The file " . $fileName . " has been uploaded.";

                    // Create image to upload
                    $image = new Image();
                    $image->setId($id);
                    $image->setContentPage((int)$contentPageId);
                    $image->setName((string)$fileName);
                    // Upload using service
                    $service->addImage($image);
                } else {
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            } else {
                $statusMsg = 'Sorry, only PNG, JPG, JPEG files are allowed to be uploaded.';
            }
        }
    }
} else {
    $statusMsg = 'No image was found.';
}

// Display status message
echo $statusMsg;

// Back to event page
header("Location: /event/".$event."/edit");

?>

