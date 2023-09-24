<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <style>
        .wrong_input{
            display: none; 
            color: red; 
            opacity: 70%; 
            font-size: small;
        }
    </style>
</head>
<body>
    <div class="container-sm">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-5">Euskor&oacute;scopo<?php if(isset($_SESSION['email'])){echo ": Modificar datos del usuario $_SESSION[usuario]";}?></span>
        
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
            <?php if(isset($_SESSION['email'])){
                echo '<li class="nav-item"><a href="logout.php" class="btn btn-danger">Logout</a></li>';
                }?>
        </ul>
    
        </a>
        </header>
    </div>
    <div class="container" style="max-width: 50%;">
        <div class="container login">
            <h1>Login</h1>
            <form name="logInForm" id="logInForm">
                <label for="InputEmail" class="form-label">Email address or username</label>
                <input class="form-control mb-3" id="InputEmail" describedby="emailHelp">
                <label for="InputPassword" class="form-label">Password</label>
                <input type="password" class="form-control mb-3" id="InputPassword">
                <button type="submit" class="btn btn-primary" id="LogInButton">Login</button>
            </form>
        </div>
        <hr>
        <div class = "container__signup">
            <h1>Sign-up</h1>
            <form name="signUp" id="signUpForm">
                    <label for="NameSignup" class="form-label">Nombre</label>
                    <input type="text" class="form-control mb-3" id="NameSignup" name="name" onkeyup="live_checkName()">
                    <p class="wrong_input" id="wrong_name">Solo caracteres alfabeticos</p>
                    <label for="ApellidosSignup" class="form-label">Apellidos</label>
                    <input type="text" class="form-control mb-3" id="ApellidosSignup" name="surname" onkeyup="live_checkSurname()">
                    <p class="wrong_input" id="wrong_surname">Solo caracteres alfabeticos</p>
                    <label for="UsernameSignup" class="form-label">Usuario</label>
                    <input class="form-control mb-3" id="UsernameSignup" name="username">
                    <label for="InputPasswordSignup" class="form-label">Contraseña</label>
                    <input type="password" class="form-control mb-3" id="InputPasswordSignup" name="password">
                    <label for="InputEmailSignup" class="form-label">Direccion de correo</label>
                    <input type="email" class="form-control mb-3" id="InputEmailSignup" name="email">
                    <label for="PhoneSignup" class="form-label">Telefono</label>
                    <input type="tel" class="form-control mb-3" placeholder="9 Digitos" id="PhoneSignup" name="phone" required minlength="9" maxlength="9">
                    <label for="DOBSignup" class="form-label">Fecha de nacimiento:</label>
                    <input class="form-control mb-3" id="DOBSignup" placeholder="aaaa-mm-dd" name="dob">
                    <label for="DNISignup" class="form-label">DNI</label>
                    <input type="text" class="form-control mb-3" id="DNISignup" placeholder="12345678-Z" name="dni">
                    <button type="submit" id="SignUpButton" class="btn btn-primary">Registro</button>
            </form>
            <br>
        </div>
        </div>
</body>
</html>