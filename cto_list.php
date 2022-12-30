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
        getCTOs();
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
function getCTOs() {
    if(isset($_POST['olt']) && isset($_POST['route'])){
        // SE O POP E A ROTA FOREM DEFINIDAS
        require("./db_info.php");

        $olt = $_POST["olt"];
        $route = $_POST["route"];

        $sql = "SELECT * FROM cto WHERE `olt_name` = '$olt' AND `route_number` = '$route';";
        $result = $mysqli -> query($sql);
    
        $ind_cto = array();
    
        if (mysqli_num_rows($result) > 0) {
            // Exibe os resultados
            while ($row = mysqli_fetch_assoc($result)) {
                $ind_cto[] = array(
                    'cto_id'=> $row['cto_id'],
                    'cto_number'=> $row['cto_number'],
                    'olt_name'=> $row['olt_name'],
                    'route_number'=> $row['route_number'],
                    'latitude'=> $row['latitude'],
                    'longitude'=> $row['longitude'],
                    'signal'=> $row['signal'],
                    'image_url'=> $row['image_url'],
                    'user_id'=> $row['user_user_id'],
                    'user_first_name'=> $row['user_first_name'],
                    'user_last_name'=> $row['user_last_name'],
                    'timestamp'=> $row['timestamp']
                );
            }

            echo json_encode($ind_cto); 
        } else {
            http_response_code(404);
            $response[0] = array(
                'status_code'=> 404
            );
    
            echo json_encode($response); 
        }
    }

    if(isset($_POST['olt']) && !isset($_POST['route'])){
        // SE APENAS O POP FOR DEFINIDO
        http_response_code(200);
        $response[0] = array(
            'status_code'=> 200
        );

        echo json_encode($response); 
    }

    if(!isset($_POST['olt']) && !isset($_POST['route'])){
        // SE NADA FOR DEFINIDO
        http_response_code(400);
        $response[0] = array(
            'status_code'=> 400
        );

        echo json_encode($response); 
    }
}
?>