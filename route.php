<?php
include("./api/jwt/jwt.php");

if (!isset($_COOKIE['__token'])) {
    header('Location: /login.php');
} else {
    if ((new JWT)->is_valid($_COOKIE['__token'])) {
        // CONTINUE
    } else {
        header('Location: /login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>

<body id="body-pd">
    <header class="header" id="header" >
        <div class="header_toggle"> <i class='bi bi-list' id="header-toggle"></i> </div>
    </header>
    <div class="l-navbar" id="nav-bar" >
        <nav class="nav">
            <div> 
                <a href="#" class="nav_logo"> 
                    <img class="nav_logo-icon" width="20px" src="/assets/logo.png" />
                    <span class="nav_logo-name">G3 Telecom</span>
                </a>
                <div class="nav_list"> 
                    <a href="#" class="nav_link active"> 
                        <i class='bi bi-building-fill nav_icon'></i>
                        <span class="nav_name">POP</span>
                    </a> 
                    <a href="#" class="nav_link"> 
                        <i class='bi bi-hdd-rack-fill nav_icon'></i> 
                        <span class="nav_name">OLT</span> 
                    </a> 
                    <a href="#" class="nav_link"> 
                        <i class='bi bi-diagram-3-fill nav_icon'></i> 
                        <span class="nav_name">Rotas</span> 
                    </a> 
            
                    
            </div> <a onclick="deleteUserCookie()" href="#" class="nav_link"> <i class='bi bi-box-arrow-right nav_icon'></i> <span
                    class="nav_name">Sair</span> </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100" style="padding-top: 16px;" >
        <h4>Main Components</h4>
    </div>
    <!--Container Main end-->
    <script src="/script/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script>
        function deleteUserCookie() {
            document.cookie = '__token=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.location.reload();
        }
    </script>
</body>

</html>