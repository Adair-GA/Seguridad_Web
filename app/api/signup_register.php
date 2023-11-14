<?php
session_start();
include "../dbconn.php";

function encrypt(string $plaintext){

    //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
    $cipher = "aes-256-cbc";
    
    $key = file_get_contents('../openssl/key.txt');
    $ivlen = file_get_contents('../openssl/ivlen.txt');
    $iv = file_get_contents('../openssl/iv.txt');
    $iv = base64_decode($iv);
    // Write the contents back to the file
    $output = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
    $output = base64_encode($output);
    return $output;
}

function decrypt(string $ciphertext){
    
    $cipher = "aes-256-cbc";
    $key = file_get_contents('../openssl/key.txt');
    $iv = file_get_contents('../openssl/iv.txt');
    $iv = base64_decode($iv);

    $output = openssl_decrypt(base64_decode($ciphertext), $cipher, $key, 0, $iv);

    return $output;
}

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
if ($token==$_SESSION['token']){
    // login.js línea 106, usamos método POST, tendremos que recibir body de alguna forma
    // se recibe el body a través de los $_REQUEST
    $nombre = $_REQUEST['name'];
    $apellido = $_REQUEST['surname'];
    $usuario = $_REQUEST['username'];
    $contrasena = $_REQUEST['password'];

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
        $nombre = encrypt($nombre);
        $apellido = encrypt($apellido);
        $email = encrypt($email);
        $telefono = encrypt($telefono);
        $fecha_nacimiento = encrypt($fecha_nacimiento);
        $dni = encrypt($dni);

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
    exit;
}

?>
