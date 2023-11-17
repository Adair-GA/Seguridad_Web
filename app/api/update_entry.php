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


    $token = substr($request,(strlen($_SESSION['token']))*(-1));
    $sql = "UPDATE `horoscopos` SET nombre = ?, fecha_nacimiento = ?, signo_solar = ?, signo_lunar = ?, mercurio_retrogrado = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,"ssssii", $nombre, $fecha_nacimiento, $signo_solar, $signo_lunar, $retrogrado, $id);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "OK";
    } else {
        echo "ERROR";
    }

    // if ($token==$_SESSION['token']){
    //     // modifyEntry.js líneas 13-16, usamos método POST, tendremos que recibir body de alguna forma
    //     // y se hace con file_get_contents('php://input')
    //     $sql_string = substr($request,0,strlen($request)-strlen($_SESSION['token']));
        
    // }else{
    //     header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    //     exit;
    // }
    

?>