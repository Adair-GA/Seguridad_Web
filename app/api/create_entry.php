<?php
session_start();
include "../dbconn.php";

$token = $_REQUEST['token'];
if (hash_equals($token, $_SESSION['token'])){
    $nombre = $_REQUEST['name'];
    $fecha_nacimiento = $_REQUEST['dob'];
    $signosolar = $_REQUEST['signosolar'];
    $signolunar = $_REQUEST['signolunar'];
    $retrogrado = $_REQUEST['retrogrado'];

    $g_recaptcha_secret = "6LfX2RQpAAAAAMnwl8bOxvTaP-y-T0GZoBqzMpzu";
    $response = $_POST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$g_recaptcha_secret}&response={$response}");
    $data = json_decode($verify);

    if ($data->success == false) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        echo "ERROR";
        return;
    }

    $boolRetrogrado = 0;
    if ($retrogrado == "Si") {
        $boolRetrogrado = 1;
    }

    $query = "INSERT INTO horoscopos (nombre, fecha_nacimiento, signo_solar, signo_lunar, mercurio_retrogrado) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
    mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $fecha_nacimiento, $signosolar, $signolunar, $boolRetrogrado);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "OK";
    } else {
        http_response_code(400);
        echo "ERROR";
    }
}else{
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    echo "ERROR";
    exit;
} 

?>

