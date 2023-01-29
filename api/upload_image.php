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
        uploadImg();
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

function uploadImg() {
    if (isset($_FILES['file']) && $_FILES['arquivo']['error'] == 0) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_filename = md5(uniqid()).".".$ext;
        
        if($ext == "png" OR $ext == "jpg" OR $ext == "jpeg") {

            move_uploaded_file($file_tmp, "./img/$new_filename");
            $image_url = 'http://'.$_SERVER['SERVER_NAME']."/api/img/".$new_filename;

            http_response_code(201);
            $response[0] = array(
                'status_code'=> 201,
                'message'=> 'Image created successfully.',
                'image_url'=> $image_url
            );

            echo stripslashes(json_encode($response)); 
        } else {
            http_response_code(400);
            $response[0] = array(
                'status_code'=> 400,
                'message'=> 'Only PNG and JPG files is allowed.'
            );

            echo stripslashes(json_encode($response)); 
        }     
    } else {
        http_response_code(400);
        $response[0] = array(
            'status_code'=> 400
        );

        echo json_encode($response); 
    }
}
?>