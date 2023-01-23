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
        addCTO();
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

function addCTO() {
    $params_needed = ["cto", "olt", "card", "pon",
                "lat", "long", "signal", "image_url"];
    $given_params = array_keys($_POST);
    $missing_params = array_diff($params_needed, $given_params);

    if(empty($missing_params)) {
        $cto = $_POST["cto"];
        $olt = $_POST["olt"];
        $card = $_POST["card"];
        $pon = $_POST["pon"];
        $lat = $_POST["lat"];
        $long = $_POST["long"];
        $signal = $_POST["signal"];
        $image_url = "...";
        $user_id = "...";
         
        require("./db_info.php");

        $stmt = $mysqli->prepare("INSERT INTO `cto` (`cto_id`, `cto_number`, `olt_name`, `card`, `pon`, `latitude`, `longitude`, `signal`, `image_url`, `user_user_id`, `timestamp`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP);");
        $stmt -> bind_param("isiidddss", $cto, $olt, $card, $pon, $lat, $long, $signal, $image_url, $user_id);
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