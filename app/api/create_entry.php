<?php
include "../dbconn.php";

$nombre = $_REQUEST['name'];
$fecha_nacimiento = $_REQUEST['dob'];
$signosolar = $_REQUEST['signosolar'];
$signolunar = $_REQUEST['signolunar'];
$retrogrado = $_REQUEST['retrogrado'];

$boolRetrogrado = 0;
if ($retrogrado == "Si") {
    $boolRetrogrado = 1;
}

$query = "INSERT INTO horoscopos (nombre, fecha_nacimiento, signo_solar, signo_lunar, mercurio_retrogrado) VALUES ('$nombre', '$fecha_nacimiento', '$signosolar', '$signolunar', '$boolRetrogrado')";
$result = mysqli_query($conn, $query) or die (mysqli_error($conn));
if ($result) { // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
    echo "Horoscopo registrado correctamente";
} else {
    echo "Error al registrar el usuario";
}
?>