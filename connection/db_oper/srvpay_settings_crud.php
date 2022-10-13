<?php
if (is_file("basefile.php")) {
    $basePath = "./";
} else if (is_file("./../basefile.php")) {
    $basePath = "./../";
} else if (is_file("./../../basefile.php")) {
    $basePath = "./../../";
}
include $basePath . "include/page_req_prop.php";

$code = 1;
$msg = "Error";
$data = null;

if (count($_POST) > 0) {
    $operation = $_POST['Operation'];
    $url = $_POST['url'];
    $status = "1";
    
    switch ($operation) {
        case 'Insert':
        case 'Update':
            if ($operation == 'Insert') {
                $sql = "INSERT INTO app_config (redirection_url, status) VALUES ('$url', '$status')";
            } else {
                $id = $_POST['id'];
                $sql = "UPDATE app_config SET redirection_url = '$url', status = '$status' WHERE id ='$id'";
            }
            $statement = $connect->prepare($sql);
            if ($statement->execute()) {
                $code = 0;
                $msg = "Configuration " . $operation . " successfull.";
            } else {
                $code = 1;
                $msg = "Configuration " . $operation . " unsuccessfull.";
            }
            break;

        case 'ReadAll':
            $sql = "SELECT * FROM app_config";
            $statement = $connect->prepare($sql);
            if ($statement->execute()) {
                $result = $statement->fetchAll();
                $code = 0;
                $msg = "Configuration fetch successfull.";
                $data = $result;
            } else {
                $code = 1;
                $msg = "Configuration fetch unsuccessfull.";
            }
            break;

        case 'ReadThis':
            $sql = "SELECT * FROM app_config WHERE id = '$id'";
            $statement = $connect->prepare($sql);
            if ($statement->execute()) {
                $result = $statement->fetch();
                $code = 0;
                $msg = "Configuration fetch successfull.";
                $data = $result;
            } else {
                $code = 1;
                $msg = "Configuration fetch unsuccessfull.";
            }
            break;

        default:
            # code...
            break;
    }
}


$resObj = new \stdClass();
$resObj->code = $code;
$resObj->msg = $msg;
$resObj->data = $data;

$jsonResponse = json_encode($resObj);
echo $jsonResponse;
