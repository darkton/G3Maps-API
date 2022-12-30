function redirectMethod(){
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
    case 'PUT':
        
        break;
    case 'POST':
        
        break;
    case 'GET':
        getCTOs();
        break;
    case 'HEAD':
        
        break;
    case 'DELETE':
        
        break;
    case 'OPTIONS':
        
        break;
    default:
        
        break;
    }
}