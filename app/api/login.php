<?php
include "../dbconn.php";
session_start();

/*$recaptchaSecretKey = "6LeBqxQpAAAAADp0d29iwHGbbCKGibXwBrb5cwv9";
$response = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$recaptchaSecretKey}&response={$response}");
$data = json_decode($verify);*/

$token = $_REQUEST['token'];
if (/*$data && */hash_equals($token, $_SESSION['token'])){
    $usuario = $_REQUEST['email'];
    $contrasena = $_REQUEST['password'];

    // Obtenemos la sal del usuario, ¿si hay más de un usuario con el mismo nombre?
    $query = "SELECT sal FROM usuarios WHERE (usuario = ? OR email = ?)";
    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "ss", $usuario, $usuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    $contrasena .= $row[0]; // Concatenamos la contraseña introducida con la sal
    $contrasena = hash('sha512', $contrasena); // Obtenemos el hash correspondiente

    $query = "SELECT email, usuario, dni FROM usuarios WHERE (usuario = ? OR email = ?) AND contraseña = ?";
    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "sss", $usuario, $usuario, $contrasena);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    if ($row[0]) {
        $_SESSION['email'] = $row[0];
        $_SESSION['usuario'] = $row[1];
        $_SESSION['dni'] = $row[2];
        $_SESSION['token'] = bin2hex(random_bytes(24));
        echo "Login correcto";
    } else {
        echo "Login incorrecto";
    }
}else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    echo "ERROR";
    exit;
} 

?>