<?php
session_start();
include "../dbconn.php";
include "../encryption.php";
require_once "../recaptchalib.php";
$filesPath = '../openssl/';

$g_recaptcha_secret = "6LfX2RQpAAAAAMnwl8bOxvTaP-y-T0GZoBqzMpzu";
//password_hash() ?
function getSalt(int $n) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
 
    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }
 
    return $randomString;
}

$token = $_REQUEST['token'];
if (hash_equals($token, $_SESSION['token'])){
    // login.js línea 106, usamos método POST, tendremos que recibir body de alguna forma
    // se recibe el body a través de los $_REQUEST
    $nombre = $_REQUEST['name'];
    $apellido = $_REQUEST['surname'];
    $usuario = $_REQUEST['username'];
    $contrasena = $_REQUEST['password'];

    $g_recaptcha_secret = "6LfX2RQpAAAAAMnwl8bOxvTaP-y-T0GZoBqzMpzu";
    $response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$g_recaptcha_secret}&response={$response}");
    $data = json_decode($verify);
    
    if ($data->success == false) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        echo "Captcha ERROR";
        return;
    }

    $salt = getSalt(6);
    $contrasena .= $salt;
    $contrasena = hash('sha512', $contrasena);

    $email = $_REQUEST['email'];
    $telefono = $_REQUEST['phone'];
    $fecha_nacimiento = $_REQUEST['dob'];
    $dni = $_REQUEST['dni'];

    $query = "SELECT count(nombre) FROM usuarios WHERE usuario= ?";
    $query2 = "SELECT count(nombre) FROM usuarios WHERE dni= ?";
    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
    $stmt2 = mysqli_prepare($conn, $query2) or die (mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "s", $usuario);
    $dni=encryption\encrypt($dni, $filesPath);
    mysqli_stmt_bind_param($stmt2, "s", $dni);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_execute($stmt2);
    $result2 = mysqli_stmt_get_result($stmt2);
    $row = mysqli_fetch_array($result);
    $row2 = mysqli_fetch_array($result2);
    if ($row[0]!=0 and $row2[0]!=0){
        echo "Usuario y DNI repetido, por favor introduzca otro usuario y DNI";
    }
    elseif ($row[0]!=0){ 
        echo "Usuario repetido, por favor introduzca otro usuario";
    }
    elseif ($row2[0]!=0){ 
        echo "DNI repetido, por favor introduzca otro DNI";
    }
    else{
        $nombre = encryption\encrypt($nombre, $filesPath);
        $apellido = encryption\encrypt($apellido, $filesPath);
        $email = encryption\encrypt($email, $filesPath);
        $telefono = encryption\encrypt($telefono, $filesPath);
        $fecha_nacimiento = encryption\encrypt($fecha_nacimiento, $filesPath);
        //$dni = encryption\encrypt($dni, $filesPath);

        $query = "INSERT INTO usuarios (dni, nombre, apellidos, usuario, contraseña, sal, email, telefono, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "sssssssss", $dni, $nombre, $apellido, $usuario, $contrasena, $salt, $email, $telefono, $fecha_nacimiento);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) { // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
            echo "Usuario registrado correctamente";
        } else {
            echo "Error al registrar el usuario";
        }
    }   
}else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    echo "ERROR";
    exit;
} 

?>
