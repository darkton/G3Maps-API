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
        addOLT();
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
function addOLT() {
    $params_needed = ["olt_name", "pop_name"];
    $given_params = array_keys($_POST);
    $missing_params = array_diff($params_needed, $given_params);

    if(empty($missing_params)) {
        $olt = $_POST["olt_name"];
        $pop = $_POST["pop_name"];

        require("./db_info.php");

        $stmt = $mysqli->prepare("INSERT INTO `olt` (`id_olt`, `name_olt`, `name_pop`) VALUES (NULL, ?, ?);");
        $stmt -> bind_param("ss", $olt, $pop);
        $stmt -> next_result();
        $stmt -> execute();
        $stmt -> close();

        http_response_code(200);
        $response[0] = array(
            'status_code'=> 200
        );

        echo json_encode($response); 
    } else {
        http_response_code(401);
        $response[0] = array(
            'status_code'=> 401
        );

        echo json_encode($response); 
    }
}
?>