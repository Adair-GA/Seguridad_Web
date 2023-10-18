<?php
include "../dbconn.php";
$usuario = $_REQUEST['email'];
$contrasena = $_REQUEST['password'];

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