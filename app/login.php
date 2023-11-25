<?php session_start();
$_SESSION['token'] = bin2hex(random_bytes(24));
if(isset($_SESSION['dni'])){ //Para poder hacer login o registrarse no se puede tener iniciada la sesión
    header("Location: /index.php"); //Si está iniciado, redirección a index
    exit;
}
?> <!--Permite empezar o reanudar una sesión (login) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content=" default-src 'self' cdn.jsdelivr.net www.google.com www.gstatic.com">
    <title>Web Login</title> <!--Título de la pestaña--->  

    <!--Importar estilo CSS, antes se empleaba DataTable de JQuery, actualmente una tabla propia-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css/forms.css">

    <!--Importar código JavaScript-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script type="text/javascript" src="js/login.js"></script>

    <!--Se usa una plantilla de Bootstrap para el estilo CSS del sistema-->
    <!--También se implementa CSS propio para un propósitos muy determinados y específicos-->
</head>
<body>
    <div class="container-sm">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center me-md-auto link-body-emphasis text-decoration-none">
        <span class="fs-5">Euskor&oacute;scopo<?php if(isset($_SESSION['email'])){echo ": Bienvenido, $_SESSION[usuario]";}?></span>
        
        <ul class="nav nav-pills">
            <li class="nav-item"><a href="/" class="nav-link">Home</a></li>
            <?php if(isset($_SESSION['email'])){
                echo '<li class="nav-item"><a href="createEntry.php" class="nav-link">Crear entrada</a></li>';
                echo '<li class="nav-item"><a href="modifyUserData.php" class="nav-link">Modificar datos</a></li>';
                echo '<li class="nav-item"><a href="logout.php" class="btn btn-danger">Logout</a></li>';
                }?>
        </ul>
    
        </a>
        </header>
    </div>
    <div class="container">
        <div class="container__login">
            <h1>Login</h1>
            <form name="logInForm" id="logInForm">
                <label for="InputEmail" class="form-label">Email address or username</label>
                <input class="form-control mb-3" id="InputEmail" describedby="emailHelp">
                <label for="InputPassword" class="form-label">Password</label>
                <input type="password" class="form-control mb-3" id="InputPassword">

                <div class="g-recaptcha" data-sitekey="6LfX2RQpAAAAAHFYBbSuD-Dq9Gkm5GHTcx6QLv-q"></div><br>
                <button type="submit" class="btn btn-primary" id="LogInButton">Login</button>
                
                <input type="hidden" id="InputToken" name="token" value="<?php echo $_SESSION['token']?>">
                
                
            </form>
        </div>
        <hr>
        <div class = "container__signup">
            <h1>Sign-up</h1>
            <form name="signUp" id="signUpForm">
                    <label for="NameSignup" class="form-label">Nombre</label>
                    <input type="text" class="form-control mb-3" id="NameSignup" name="name">
                    <p class="wrong_input" id="wrong_name">Solo caracteres alfabeticos</p>
                    <label for="ApellidosSignup" class="form-label">Apellidos</label>
                    <input type="text" class="form-control mb-3" id="ApellidosSignup" name="surname">
                    <p class="wrong_input" id="wrong_surname">Solo caracteres alfabeticos</p>
                    <label for="UsernameSignup" class="form-label">Usuario</label>
                    <input class="form-control mb-3" id="UsernameSignup" name="username">
                    <label for="InputPasswordSignup" class="form-label">Contraseña</label>
                    <input type="password" class="form-control mb-3" id="InputPasswordSignup" name="password">
                    <p class="wrong_input" id="wrong_password">Contraseña débil. <br> Debe contener al menos una minúscula, una mayúscula, un número y un caracter especial. <br>La longitud mínima es de 6 caracteres. </p>
                    <label for="InputEmailSignup" class="form-label">Direccion de correo</label>
                    <input type="email" class="form-control mb-3" id="InputEmailSignup" name="email">
                    <p class="wrong_input" id="wrong_email">El formato del email no es correcto</p>
                    <label for="PhoneSignup" class="form-label">Telefono</label>
                    <input type="tel" class="form-control mb-3" placeholder="9 Digitos" id="PhoneSignup" name="phone" maxlength="9">
                    <p class="wrong_input" id="wrong_tel">El formato del numero de telefono no es correcto</p>
                    <label for="DOBSignup" class="form-label">Fecha de nacimiento:</label>
                    <input type="text" class="form-control mb-3" id="DOBSignup" placeholder="aaaa-mm-dd" name="dob"> 
                    <p class="wrong_input" id="wrong_date">El formato de la fecha no es correcto</p>
                    <label for="DNISignup" class="form-label">DNI (no podrá modificarse)</label>
                    <input type="text" class="form-control mb-3" id="DNISignup" placeholder="12345678-Z" name="dni">
                    <p class="wrong_input" id="wrong_dni">El DNI no es correcto</p>

                    <div class="g-recaptcha" data-sitekey="6LfX2RQpAAAAAHFYBbSuD-Dq9Gkm5GHTcx6QLv-q"></div><br>
                    <button type="submit" id="SignUpButton" class="btn btn-primary">Registro</button>
            
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
            </form>
            <br>
        </div>
        </div>
</body>
</html>