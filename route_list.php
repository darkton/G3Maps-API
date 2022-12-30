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
        getRoutes();
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

function getRoutes() {
    if(isset($_POST['olt'])){
        // SE O POP E A ROTA FOREM DEFINIDAS
        require("./db_info.php");

        $olt = $_POST["olt"];

        $sql = "SELECT * FROM route WHERE `olt_name` = '$olt';";
        $result = $mysqli -> query($sql);
    
        $route_array = array();
    
        if (mysqli_num_rows($result) > 0) {
            // Exibe os resultados
            while ($row = mysqli_fetch_assoc($result)) {
                $route_array[] = array(
                    'route_id'=> $row['route_id'],
                    'route_number'=> $row['route_number'],
                    'port_number'=> $row['port_number'],
                    'slot_number'=> $row['slot_number'],
                    'olt_name'=> $row['olt_name']
                );
            }

            echo json_encode($route_array); 
        } else {
            http_response_code(404);
            $response[0] = array(
                'status_code'=> 404
            );
    
            echo json_encode($response); 
        }
    } else {
        if(!isset($_POST['olt']) && !isset($_POST['route'])){
            // SE NADA FOR DEFINIDO
            http_response_code(400);
            $response[0] = array(
                'status_code'=> 400
            );
    
            echo json_encode($response); 
        }
    }
}
?>