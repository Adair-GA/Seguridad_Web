<?php
session_start();
include "../dbconn.php";

function decrypt(string $ciphertext){
    
    $cipher = "aes-256-cbc";
    $key = file_get_contents('../openssl/key.pem');
    $iv = file_get_contents('../openssl/iv.txt');
    $iv = base64_decode($iv);

    $output = openssl_decrypt(base64_decode($ciphertext), $cipher, $key, 0, $iv);

    return $output;
}

$token = $_REQUEST['token'];
if ($token==$_SESSION['token']){
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
    exit;
}

?>