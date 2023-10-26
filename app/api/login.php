<?php
include "../dbconn.php";
$usuario = $_REQUEST['email'];
$contrasena = $_REQUEST['password'];

// Obtenemos la sal del usuario, ¿si hay más de un usuario con el mismo nombre?
$query = "SELECT sal FROM usuarios WHERE (usuario = '$usuario' OR email = '$usuario')";
$result = mysqli_query($conn, $query) or die (mysqli_error($conn));
$row = mysqli_fetch_array($result);
$contrasena .= $row[0]; // Concatenamos la contraseña introducida con la sal
$contrasena = hash('sha512', $contrasena); // Obtenemos el hash correspondiente

$query = "SELECT email, usuario, dni FROM usuarios WHERE (usuario = '$usuario' OR email = '$usuario') AND contraseña = '$contrasena'";
$result = mysqli_query($conn, $query) or die (mysqli_error($conn));
$row = mysqli_fetch_array($result);
if ($row[0]) {
    session_start();
    $_SESSION['email'] = $row[0];
    $_SESSION['usuario'] = $row[1];
    $_SESSION['dni'] = $row[2];
    echo "Login correcto";
} else {
    echo "Login incorrecto";
}
?>