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
    $params_needed = ["cto", "olt", "route",
                "lat", "long", "signal", "image_url"];
    $given_params = array_keys($_POST);
    $missing_params = array_diff($params_needed, $given_params);

    if(empty($missing_params)) {
        $cto = $_POST["cto"];
        $olt = $_POST["olt"];
        $route = $_POST["route"];
        $lat = $_POST["lat"];
        $long = $_POST["long"];
        $signal = $_POST["signal"];
        $image_url = "...";
        $user_id = "...";

        $user_first_name = "NA";
        $user_last_name = "NA";

        require("./db_info.php");

        

        $sql_add = "INSERT INTO `cto` (`cto_id`, `cto_number`, `olt_name`, `route_number`, `latitude`, `longitude`, `signal`, `image_url`, `user_user_id`, `user_first_name`, `user_last_name`, `timestamp`) VALUES (NULL, '$cto', '$olt', '$route, '$lat', '$long', '$signal', '$image_url', '$user_id', '$user_first_name', '$user_last_name', CURRENT_TIMESTAMP);";
        $result_add = $mysqli -> query($sql_add);
    } else {
        http_response_code(401);
        $response[0] = array(
            'status_code'=> 401
        );

        echo json_encode($response); 
    }
}
?>