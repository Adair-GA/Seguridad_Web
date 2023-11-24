<?php

use function encryption\encrypt;

include "../dbconn.php";
include "../encryption.php";
require_once "../recaptchalib.php";
$filesPath = '../openssl/';

session_start();

$g_recaptcha_secret = "6LfX2RQpAAAAAMnwl8bOxvTaP-y-T0GZoBqzMpzu";
$response = $_POST['g-recaptcha-response'];
$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$g_recaptcha_secret}&response={$response}");
$data = json_decode($verify);

if ($data->success == false) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    echo "ERROR";
    return;
}

$token = $_REQUEST['token'];
if (/*$data && */hash_equals($token, $_SESSION['token'])){
    $usuario = $_REQUEST['email'];
    $contrasena = $_REQUEST['password'];
    $email = encrypt($_REQUEST['email'], $filesPath);

    // Obtenemos la sal del usuario, ¿si hay más de un usuario con el mismo nombre?
    $query = "SELECT sal FROM usuarios WHERE (usuario = ? OR email = ?)";
    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "ss", $usuario, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result);
    if ($row == null || $row == false){
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        $echo = "Error: Usuario incorrecto";
        $mss = "login attempt, invalid username";
        $user = $usuario;
    }else{
        $contrasena .= $row[0]; // Concatenamos la contraseña introducida con la sal
        $contrasena = hash('sha512', $contrasena); // Obtenemos el hash correspondiente
        
        $query = "SELECT email, usuario, dni FROM usuarios WHERE (usuario = ? OR email = ?) AND contraseña = ?";
        $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "sss", $usuario, $email, $contrasena);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);
        if ($row == null || $row == false){
            header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
            $echo = "Error: Contraseña incorrecta";
            $mss = "login attempt, invalid password";
            $user = $usuario;
        }else{
            if ($row[0]) {
                $_SESSION['email'] = $row[0];
                $_SESSION['usuario'] = $row[1];
                $_SESSION['dni'] = $row[2];
                $_SESSION['token'] = bin2hex(random_bytes(24));
                $echo = "Login correcto";
                $mss = "login";
                $user = $row[1];
            } else {
                $echo = "Login incorrecto";
                $mss = "login attempt";
                $user = $usuario;
            }
        }        
    }
    

}else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    $echo = "ERROR";
    $mss = "login attempt, invalid token";
    $user = $_REQUEST['email'];
} 
$log = fopen("../logs/log.txt", "a");
$today = date("Y-m-d H:i:s"); 
fwrite($log, "[$today] $mss: " . $user . " " . $_SERVER['REMOTE_ADDR'] . "\n");
fclose($log);
echo $echo;
    

?>