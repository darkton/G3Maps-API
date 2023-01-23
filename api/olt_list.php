<?php
session_start();
error_reporting(1);
mysqli_report (MYSQLI_REPORT_ERROR);

header('Access-Control-Allow-Origin: *');
// header('Content-type: application/json');

define('__ROOT__', dirname(dirname(__FILE__)));
include("./db_info.php");
include("./jwt/jwt.php");
include("./libs/get_headers.php");

$response = array();

if(getBearerToken()) {
    if((new JWT)->is_valid(getBearerToken())) {
        getOLTs();
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

function getOLTs() {
    require("./db_info.php");
  
    $sql = "SELECT * FROM `olt` WHERE 1 ORDER BY `name_pop` ASC;";
    $result = $mysqli -> query($sql);

    $ind_olt = array();

    if (mysqli_num_rows($result) > 0) {
        // Exibe os resultados
        while ($row = mysqli_fetch_assoc($result)) {
            $ind_olt[] = array(
                'olt_id'=> $row['id_olt'],
                'olt_name'=> $row['name_olt'],
                'pop'=> $row['name_pop']
            );
        }

        echo json_encode($ind_olt); 
    } else {
        http_response_code(500);
        $response[0] = array(
            'status_code'=> 500
        );

        echo json_encode($response); 
    }
}
?>