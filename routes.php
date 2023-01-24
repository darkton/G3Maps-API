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
    <div class="toast-container top-0 start-50 translate-middle-x" style="margin-bottom: 40px;">
        <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
            <div class="d-flex">
                    <div id="toastMessage" class="toast-body">
                        ...
                    </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <div id="addModal" class="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar OLT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" id="addPOPForm" enctype="multipart/form-data" action="#" method="post" novalidate>
                    <select id="popList" class="form-select" aria-label="POP">
                        <option selected>Selecionar POP</option>
                    </select>
                    <input style="margin-top: 8px;" id="popNameInput" name="olt_name" type="text" class="form-control" placeholder="Nome da OLT" required>
                    <div class="invalid-feedback">
                        Por favor, digite o nome da OLT.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button id="btnAddPOP" type="submit" class="btn btn-primary">Adicionar</button>
            </div>
            </div>
        </div>
    </div>
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
                    <a href="/" class="nav_link"> 
                        <i class='bi bi-building-fill nav_icon'></i>
                        <span class="nav_name">POP</span>
                    </a> 
                    <a href="/olt.php" class="nav_link"> 
                        <i class='bi bi-hdd-rack-fill nav_icon'></i> 
                        <span class="nav_name">OLT</span> 
                    </a> 
                    <a href="#" class="nav_link active" active> 
                        <i class='bi bi-cursor-fill'></i> 
                        <span class="nav_name">Rotas</span> 
                    </a>  
            </div> 
            <a onclick="deleteUserCookie()" href="#" class="nav_link"> <i class='bi bi-box-arrow-right nav_icon'></i> <span
                    class="nav_name">Sair</span> </a>
        </nav>
    </div>
    <!--Container Main start-->
    <div class="height-100" style="padding-top: 32px;">
        
        <h4>Gerenciar OLT</h4>
        <button class="btn btn-primary g3-btn-add" onclick="searchPOPList()" data-bs-toggle="modal" data-bs-target="#addModal"><i class='bi bi-plus' style="margin-right: 8px;"></i>Adicionar</button>
        <div class="card" style="width: 100%; margin-top: 8px;">
            <ul id="list" class="list-group list-group-flush">

            </ul>
        </div>
    </div>
    <!--Container Main end-->
    <script src="/script/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <script>
        const list = document.getElementById("list");
        const popList = document.getElementById("popList");
        var addPOPButton = document.getElementById('btnAddPOP');

        addPOPButton.onclick = async (e) => {
            e.preventDefault();

            var value = popList.value;
            var textPop = popList.options[popList.selectedIndex].text;

            const url = "./api/add_olt.php";

            try {
                const form = document.getElementById("addPOPForm");
                const formData = new FormData(form);
                formData.append("pop_name", textPop);

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' +  getCookie("__token")
                    },
                    body: formData
                }).then(function (response) { 
                    form.reset();
                    if(!response.ok) {
                        displayToast("Um erro inesperado aconteceu. Tente novamente mais tarde.");
                    } else {
                        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('addModal'));  
                        modal.hide();

                        location.reload();
                    }

                    return response.text(); 
                });

                console.log(response);

                const obj = JSON.parse(response);
                for(i in obj){
                    console.log(response);
                }
            
            } catch (error) {
                displayToast("Erro no servidor. Contate o administrador.");
            }
        }

        function deleteUserCookie() {
            document.cookie = '__token=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.location.reload();
        }

        window.addEventListener("load", () => searchOLT(), false);

        async function searchOLT() {
            const url = "./api/route_list.php";

            const formData = new FormData();
            formData.append("olt", "CODÓ");

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' +  getCookie("__token")
                    },
                    body: formData
                }).then(function (response) { return response.text(); });

                let resStatusCode;
                let resToken;

                console.log(response);

                const obj = JSON.parse(response);
                for(i in obj){
                    var z = document.createElement('li'); // is a node
                    z.innerHTML = '<div style="display: flex;"><div style="display: flex; width: 100%; align-items: center;"><p class="g3-minus-margin"><b>ROTA ' + obj[i]["route_number"] + '</b><br><small>CARD ' + obj[i]["slot_number"] + ' / PON ' + obj[i]["port_number"] + '</small></p></div><button class="btn btn-light"><i class="bi bi-pencil-square"></i></button><button onclick="deletePOP(this);" style="margin-left: 8px;" class="btn btn-danger" data-popid="'+ obj[i]["id_olt"] +'"><i class="bi bi-trash3-fill"></i></button></div>';
                    z.classList.add("list-group-item");

                    list.appendChild(z);
                }
            
                if(resStatusCode == 200) {
                } else {
                    // displayToast("Erro no servidor. Contate o administrador.");
                }
            } catch (error) {
                console.log("Error: " + error);
                // displayToast("Erro no servidor. Contate o administrador.");
            }
        }

        function getCookie(c_name) {
            if (document.cookie.length > 0) {
                c_start = document.cookie.indexOf(c_name + "=");
                if (c_start != -1) {
                    c_start = c_start + c_name.length + 1;
                    c_end = document.cookie.indexOf(";", c_start);
                    if (c_end == -1) {
                        c_end = document.cookie.length;
                    }
                    return unescape(document.cookie.substring(c_start, c_end));
                }
            }
            return "";
        }

        const toastLive = document.getElementById('liveToast')

        function displayToast(text) {
            document.getElementById('toastMessage').innerHTML = text;
            const toast = new bootstrap.Toast(toastLive)
            toast.show()
        }

        function deletePOP(pop) {
            var popId = pop.getAttribute("data-popid");

            if (confirm('Você deseja mesmo excluir a OLT? Esta ação não poderá ser revertida.')) {
                
            }
        }

        async function searchPOPList() {
            document.getElementById("popList").innerHTML = "";

            const url = "./api/pop_list.php";

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' +  getCookie("__token")
                    }
                }).then(function (response) { return response.text(); });

                let resStatusCode;
                let resToken;

                console.log(response);

                const obj = JSON.parse(response);
                for(i in obj){
                    
                    var z = document.createElement('option'); // is a node
                    z.innerHTML = obj[i]["pop_name"];
                    
                    popList.appendChild(z);
                }
            
                if(resStatusCode == 200) {
                } else {
                    // displayToast("Erro no servidor. Contate o administrador.");
                }
            } catch (error) {
                console.log("Error: " + error);
                // displayToast("Erro no servidor. Contate o administrador.");
            }
        }
    </script>
</body>

</html>