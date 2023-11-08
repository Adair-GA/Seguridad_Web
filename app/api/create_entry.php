<?php
session_start();
include "../dbconn.php";

$token = $_REQUEST['token'];
if ($token==$_SESSION['token']){
    $nombre = $_REQUEST['name'];
    $fecha_nacimiento = $_REQUEST['dob'];
    $signosolar = $_REQUEST['signosolar'];
    $signolunar = $_REQUEST['signolunar'];
    $retrogrado = $_REQUEST['retrogrado'];


    $boolRetrogrado = 0;
    if ($retrogrado == "Si") {
        $boolRetrogrado = 1;
    }

    $query = "INSERT INTO horoscopos (nombre, fecha_nacimiento, signo_solar, signo_lunar, mercurio_retrogrado) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $fecha_nacimiento, $signosolar, $signolunar, $boolRetrogrado);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) { // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
        echo "Horoscopo registrado correctamente";
    } else {
        echo "Error al registrar el usuario";
    }

}else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    exit;
}

?>

