<?php
session_start();
error_reporting(1);

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

define('__ROOT__', dirname(dirname(__FILE__)));
include(__ROOT__."/api/db_info.php");
include(__ROOT__."/api/jwt/jwt.php");

$response = array();

if (!$_POST["password"]) {
    http_response_code(400);
    $response[0] = array(
        'status_code'=> 400
    );
    echo json_encode($response); 
    exit;
} else {
    logOn();
}

function logOn() {
    $password = md5($_POST["password"]);

    mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);

    require(__ROOT__."/api/db_info.php");
    $admin_uuid = "98524e3e-8811-4f53-a732-d298cf715f5d";

    $sql = "SELECT * FROM `user` WHERE `uuid` = '$admin_uuid' AND `password` = '$password'";
    $result = $mysqli -> query($sql);
    $count = mysqli_num_rows($result);

    if($count == 1) {
        http_response_code(200);

        $payload = array(
            'uuid'=> $admin_uuid
        );
        
        $response[0] = array(
            'status_code'=> 200,
            'token'=> (new JWT)->generate($payload)
        );

        echo json_encode($response); 
    } else {
        http_response_code(403);
        $response[0] = array(
            'status_code'=> 403
        );

        echo json_encode($response); 
    }
}


?>