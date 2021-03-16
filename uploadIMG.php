<?php
$statusMsg = '';

//file upload path
$targetDir = "uploads/uploadedIMG/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])) {

    //allow certain file formats
    $allowTypes = array('jpg','png','jpeg');

    if(in_array($fileType, $allowTypes)){
        //upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            $statusMsg = "The file ".$fileName. " has been uploaded.";
        }
        else{
            $statusMsg = "Sorry, there was an error uploading your file.";
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

<form action="uploadIMG.php" method="post" enctype="multipart/form-data">
    Select File to Upload:
    <input type="file" name="file">
    <input type="submit" name="submit" value="Upload" required>
</form>
-->