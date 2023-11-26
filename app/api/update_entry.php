<?php
    session_start();
    include '../dbconn.php';
    
    $request = file_get_contents('php://input'); // La instrucción SQL pasada como parámetro
    $nombre = $_REQUEST['name'];
    $fecha_nacimiento = $_REQUEST['dob'];
    $signo_solar = $_REQUEST['signosolar'];
    $signo_lunar = $_REQUEST['signolunar'];
    $retrogrado = $_REQUEST['retrogrado'] == 'Si' ? 1 : 0;
    $id = $_REQUEST['id'];
    $token = $_REQUEST['token'];

        
    $g_recaptcha_secret = "6LfX2RQpAAAAAMnwl8bOxvTaP-y-T0GZoBqzMpzu";
    $response = $_REQUEST['g-recaptcha-response'];
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$g_recaptcha_secret}&response={$response}");
    $data = json_decode($verify);

    if ($data->success == false) {
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        echo "ERROR";
        return;
    }

    if (hash_equals($token, $_SESSION['token'])){
        $sql = "UPDATE `horoscopos` SET nombre = ?, fecha_nacimiento = ?, signo_solar = ?, signo_lunar = ?, mercurio_retrogrado = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt,"ssssii", $nombre, $fecha_nacimiento, $signo_solar, $signo_lunar, $retrogrado, $id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "OK";
        } else {
            echo "SINCAMBIOS";
        }
    }else{
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        echo "ERROR";
        exit;
    }  

?>