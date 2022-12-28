<?php
session_start();
error_reporting(1);
mysqli_report (MYSQLI_REPORT_OFF);

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

define('__ROOT__', dirname(dirname(__FILE__)));
include("./db_info.php");
include("./jwt/jwt.php");

$response = array();

if ($_GET["username"] && $_GET["password"]) {
    logOn();
} else {
    http_response_code(400);
    $response[0] = array(
        'status_code'=> 400
    );
    echo json_encode($response); 
    exit;
}

function logOn() {
    $username = $_GET["username"];
    $password = md5($_GET["password"]);
    // $password = $_GET["password"];

    require("./db_info.php");

    $sql = "SELECT * FROM `user` WHERE `username` = '$username' AND `password` = '$password';";
    $result = $mysqli -> query($sql);
    $count = mysqli_num_rows($result);

    $user_uuid = "";
    
    while($row = mysqli_fetch_assoc($result)) {
        $user_uuid = $row["uuid"];
    } 

    if($count == 1) {
        http_response_code(200);

        $payload = array(
            'uuid'=> $user_uuid
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