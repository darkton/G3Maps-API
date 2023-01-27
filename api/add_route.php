<?php
session_start();
error_reporting(1);
mysqli_report (MYSQLI_REPORT_OFF);

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

define('__ROOT__', dirname(dirname(__FILE__)));
include("./db_info.php");
include("./jwt/jwt.php");
include("./libs/get_headers.php");

if(getBearerToken()) {
    if((new JWT)->is_valid(getBearerToken())) {
        addRoute();
    } else {
        http_response_code(403);
        $response[0] = array(
            'status_code'=> 403
        );

        echo json_encode($response); 
    }
} else {
    http_response_code(401);
    $response[0] = array(
        'status_code'=> 401
    );

    echo json_encode($response); 
}

function addRoute() {
    $params_needed = ["olt", "number", "card", "pon"];
    $given_params = array_keys($_POST);
    $missing_params = array_diff($params_needed, $given_params);

    if(empty($missing_params)) {
        $olt = $_POST["olt"];
        $number = $_POST["number"];
        $card = $_POST["card"];
        $pon = $_POST["pon"];
         
        require("./db_info.php");

        $stmt = $mysqli->prepare("INSERT INTO `route` (`route_id`, `route_number`, `port_number`, `slot_number`, `olt_name`) VALUES (NULL, ?, ?, ?, ?);");
        $stmt -> bind_param("iiis", $olt, $number, $card, $pon);
        $stmt -> next_result();
        $stmt -> execute();
        $stmt -> close();
    } else {
        http_response_code(401);
        $response[0] = array(
            'status_code'=> 401
        );

        echo json_encode($response); 
    }
}
?>