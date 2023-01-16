<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Infra G3 Telecom</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/main.css">

</head>

<body class="mb-2 bg-body text-dark" style="margin: 0; height: 100vh;">
    <nav class="navbar bg-light" style="position: absolute; top: 0; left: 0; width: 100%;">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Entrar</span>
        </div>
    </nav>
    <script src="/script/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3"
        crossorigin="anonymous"></script>
    <div>
        <div class="toast-container bottom-0 start-50 translate-middle-x" style="margin-bottom: 40px;">
            <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                <div class="d-flex">
                    <div id="toastMessage" class="toast-body">
                    ...
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>
        <div class="centralized-div position-absolute top-50 start-50 translate-middle card">
            <div class="card-body">
                <p style="margin-bottom: 8px;">Insira as informações de entrada.</p>
                <form class="needs-validation" id="loginForm" enctype="multipart/form-data" action="#" method="post" novalidate>
                    <input id="loginUserInput" name="username" type="text" class="form-control" placeholder="Usuário" required>
                    <div class="invalid-feedback">
                        Por favor, digite o usuário.
                    </div>
                    <input style="margin-top: 6px;" id="loginPasswordInput" name="password" type="password" class="form-control" placeholder="Senha" required>
                    <div class="invalid-feedback">
                        Por favor, digite a senha.
                    </div>
                    <button id="loginSubmitButton" type="submit" class="btn btn-primary">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
<script>
    
var loginForm = document.getElementById('loginForm');
const toastLive = document.getElementById('liveToast')

loginForm.onsubmit = async (e) => {
    e.preventDefault();
    const form = e.currentTarget;
    const url = "./api/auth_signin.php";

    loginSubmitButton.disabled = true;

    displayToast("Carregando...");

    try {
        const formData = new FormData(form);
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        }).then(function (response) { return response.text(); });

        let resStatusCode;
        let resToken;

        const obj = JSON.parse(response);
        for(i in obj){
            resStatusCode = obj[i]["status_code"];
            resToken = obj[i]["token"];
        }

        loginSubmitButton.disabled = false;
      
        if(resStatusCode == 200) {

            createCookie("__token", resToken);
            window.location.href = '/index.php';
        } else if(resStatusCode == 403) {
            displayToast("Senha incorreta, verifique novamente.");
        } else {
            displayToast("Erro no servidor. Contate o administrador.");
        }
    } catch (error) {
        loginSubmitButton.disabled = false;
        displayToast("Erro no servidor. Contate o administrador.");
    }
}

function displayToast(text) {
    document.getElementById('toastMessage').innerHTML = text;
    const toast = new bootstrap.Toast(toastLive)
    toast.show()
}

var loginPasswordInput = document.getElementById('loginPasswordInput');
var loginSubmitButton = document.getElementById('loginSubmitButton');
loginSubmitButton.disabled = true;

loginPasswordInput.onkeyup = function () {
    if (loginPasswordInput.value.length >= 1) {
        loginSubmitButton.disabled = false;
    } else {
        loginSubmitButton.disabled = true;
    }
};

function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
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

(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
   
</script>
</html>

<?php
?>