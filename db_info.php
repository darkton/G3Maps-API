<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$database ='termos_g3';

mysqli_report(MYSQLI_REPORT_ALL);

$mysqli = "";

try {
    $mysqli = new mysqli($db_host, $db_user, $db_password, $database);
} catch(Exception $e) {
    http_response_code(500);
    $response[0] = array(
        'status_code'=> '500 Internal Server Error'
    );

    echo json_encode($response); 

    die();
}  
?>