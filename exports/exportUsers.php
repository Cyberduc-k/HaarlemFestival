<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Columns that can be exported
const VALID_COLUMNS = array("id", "firstname", "lastname", "password", "salt", "email", "register_date", "usertype");

if($_POST){
    require_once("../services/UserService.php");
    require_once("../validate.php");

    $userService = new UserService();
    $data = array();
    $args = array();

    //Clear errors
    if(isset($_SESSION["exportUsersError"]))
        unset($_SESSION["exportUsersError"]);

    //Make sure a column is selected and a format is set
    if(
        (!empty($_POST["id"]) || !empty($_POST["firstname"]) || !empty($_POST["lastname"]) || !empty($_POST["password"])
            || !empty($_POST["salt"]) || !empty($_POST["email"]) || !empty($_POST["register_date"]) || !empty($_POST["usertype"]))
        && !empty($_POST["format"])
    ){
        //Make sure there arent any malicious post variables
        foreach($_POST as $key => $value){
            if(in_array($key, VALID_COLUMNS))
                array_push($args,$key);
        }

        $data = $userService->getColumns($args);
    }
    else{
        $_SESSION["exportUsersError"] = "Select at least one column";
        header("Location: /export");
        exit;
    }

    //Only export if we got some data back
    if(!empty($data)){
        try{
            if((string)htmlentities($_POST["format"]) == "csv"){
                $delimiter = ",";
                $filename = "users_" . time() . ".csv";

                //create a file pointer, now a temp file is not necessary
                $f = fopen('php://memory', 'w');

                //set column headers
                $fields = $args;
                fputcsv($f, $fields, $delimiter);

                //Add all data to the csv
                foreach($data as $row){
                    $line = array();
                    foreach($args as $argument){
                        array_push($line, $row[$argument]);
                    }

                    fputcsv($f, $line, $delimiter);
                }

                //move back to beginning of file
                fseek($f, 0);

                //set headers to download file rather than to display it
                header('Content-Disposition: attachment; filename="' . $filename . '";');
                header("Content-Type: application/octet-stream");

                //output the file and close
                fpassthru($f);
                fclose($f);
            }
            else if ((string)htmlentities($_POST["format"]) == "excel"){
                require_once('../libs/PHPSpreadsheet/vendor/autoload.php');
                //Add the headers off selected columns to beginning of the array
                //So i can easily just add the entire array to the excel file
                array_unshift($data, $args);

                //Create an excel sheet
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                //Fill the sheet
                $sheet->fromArray($data);

                //Store the sheet as temp file
                $writer = new Xlsx($spreadsheet);
                $filename = "users_" . time() . ".xlsx";
                $writer->save($filename);

                //set headers to download file rather than to display it
                header('Content-Disposition: attachment; filename="' . $filename . '";');
                header("Content-Type: application/octet-stream");

                //output the file, close and clean up
                $f = fopen($filename, 'r');
                fpassthru($f);
                fclose($f);
                unlink($filename);
            }
        }
        catch (Exception $e){
            $_SESSION["exportUsersError"] = "An error occured, try again later";
            header("Location: /export");
            exit;
        }
    }
    else{
        $_SESSION["exportUsersError"] = "Nothing to export";
        header("Location: /export");
        exit;
    }
}
else{
    echo "This page is POST only";
}
