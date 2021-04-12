<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//Columns that can be exported
const VALID_COLUMNS = array("id", "userId", "userAddress", "userPhone", "tax", "date", "dueDate");

if($_POST){
    require_once("../services/InvoiceService.php");
    require_once("../validate.php");

    $invoiceService = new InvoiceService();
    $data = array();
    $args = array();

    //Clear errors
    if(isset($_SESSION["exportTicketsError"]))
        unset($_SESSION["exportTicketsError"]);

    if(
        (!empty($_POST["id"]) || !empty($_POST["userId"]) || !empty($_POST["userAddress"]) || !empty($_POST["userPhone"])
            || !empty($_POST["tax"]) || !empty($_POST["date"]) || !empty($_POST["dueDate"])) && !empty($_POST["format"])
    ){
        //Make sure there arent any malicious post variables
        foreach($_POST as $key => $value){
            if(in_array($key, VALID_COLUMNS))
                $args[htmlentities((string)$key)] = htmlentities((string)$value);
        }

        $data = $invoiceService->getColumns($args);
    }
    else{
        $_SESSION["exportInvoicesError"] = "Select at least one column";
        header("Location: /export");
        exit;
    }

    if(!empty($data)){
        try{
            if((string)htmlentities($_POST["format"]) == "csv"){
                $delimiter = ",";
                $filename = "invoices_" . time() . ".csv";

                //create a file pointer, now a temp file is not necessary
                $f = fopen('php://memory', 'w');

                //set column headers
                $fields = array_keys($args);
                fputcsv($f, $fields, $delimiter);

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
                //Add the headers off selected columns to array
                array_unshift($data, array_keys($args));

                //Create an excel sheet
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                //Fill the sheet
                $sheet->fromArray($data);

                //Store the sheet as temp file
                $writer = new Xlsx($spreadsheet);
                $filename = "invoices_" . time() . ".xlsx";
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
            $_SESSION["exportInvoicesError"] = "An error occured, try again later";
            header("Location: /export");
            exit;
        }
    }
    else{
        $_SESSION["exportInvoicesError"] = "Nothing to export";
        header("Location: /export");
        exit;
    }
}
else{
    echo "This page is POST only";
}
