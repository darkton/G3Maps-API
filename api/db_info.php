<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$database ='harkinco_db';

$mysqli = "";

try {
    $mysqli = new mysqli($db_host, $db_user, $db_password, $database);
  	$mysqli -> query("SET NAMES 'utf8'");
    $mysqli -> query('SET character_set_connection=utf8');
    $mysqli -> query('SET character_set_client=utf8');
    $mysqli -> query('SET character_set_results=utf8');
} catch(Exception $e) {
    http_response_code(500);
    $response[0] = array(
        'status_code'=> '500 Internal Server Error'
    );

    echo json_encode($response); 

    die();
}  
?>