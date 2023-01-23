<?php
session_start();
error_reporting(1);
mysqli_report (MYSQLI_REPORT_OFF);

header('Access-Control-Allow-Origin: *');
// header('Content-type: application/json');

define('__ROOT__', dirname(dirname(__FILE__)));
include("./db_info.php");
include("./jwt/jwt.php");
include("./libs/get_headers.php");

if(getBearerToken()) {
    if((new JWT)->is_valid(getBearerToken())) {
        addPOP();
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

function addPOP() {
    $params_needed = ["pop_id"];
    $given_params = array_keys($_POST);
    $missing_params = array_diff($params_needed, $given_params);

    if(empty($missing_params)) {
        $id_pop = $_POST["pop_id"];

        require("./db_info.php");

        $stmt = $mysqli -> prepare("DELETE FROM pop WHERE id_pop = ?");
        $stmt -> bindParam("i", $id_pop);
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