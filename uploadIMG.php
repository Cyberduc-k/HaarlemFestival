<?php
require_once("models/Image.php");
require_once("services/ImageService.php");

// NOG NIET UITGEBREID GESTEST, KAN NOG NIET HELEMAAL WERKEN!

//file upload path and other variables like filename
$targetDir = "uploads/uploadedIMG/";
$fileName = htmlspecialchars(basename($_FILES["file"]["name"]));
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
$contentPageId = $_GET["contentId"];
$statusMsg = '';

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {

    // Create new instance of the image service
    $service = new ImageService();

    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg');

    if(in_array($fileType, $allowTypes)){
        // upload file to server
        // Count # of uploaded files in array
        $total = count($_FILES['file']["name"]);

        // Loop through each file
        for( $i=0 ; $i < $total ; $i++ ) {

            // Get the temp file path
            $tmpFilePath = $_FILES['file']['tmp_name'][$i];

            // Make sure we have a file path
            if ($tmpFilePath != ""){
                // Setup our new file path
                $newFilePath = $targetDir . $_FILES['file']['name'][$i];

                // Upload the file into the temp dir
                if(move_uploaded_file($tmpFilePath, $newFilePath)) {

                    $statusMsg = "The file ".$fileName. " has been uploaded.";

                    // Upload image data to database
                    $images = $service->getAll();
                    $id = end($images)->getId() + 1;
                    // Create image to upload
                    $image = new Image();
                    $image->setId($id);
                    $image->setContentPage($contentPageId);
                    // Upload using service
                    $service->addImage($image);
                }
                else{
                    $statusMsg = "Sorry, there was an error uploading your file.";
                }
            }
        }
    }
    else{
        $statusMsg = 'Sorry, only JPG, JPEG files are allowed to be uploaded.';
    }
}
else{
    $statusMsg = 'No image was found.';
}

//display status message
echo $statusMsg;
?>

<!--

GEBRUIK DIT OM IMAGES TE UPLOADEN(nog niet naar de database)!!!

                ----------
-->
