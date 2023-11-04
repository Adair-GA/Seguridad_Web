<?php
    session_start();
    include '../dbconn.php';
    
    $request = file_get_contents('php://input'); // La instrucción SQL pasada como parámetro
    // Obtenemos la sal del usuario, ¿si hay más de un usuario con el mismo nombre?
    $usuario = $_SESSION['usuario'];
    $query = "SELECT sal FROM usuarios WHERE (usuario = '$usuario' OR email = '$usuario')";
    $result = mysqli_query($conn, $query) or die (mysqli_error($conn));
    $row = mysqli_fetch_array($result);
    $request .= $row[0]; // Concatenamos la contraseña introducida con la sal
    $request = hash('sha512', $request); // Obtenemos el hash correspondiente

    echo $request;

    /*$token = substr($request,(strlen($_SESSION['token']))*(-1));
    if ($token==$_SESSION['token']){
        $sql_string = substr($request,0,strlen($request)-strlen($_SESSION['token']));
        $result = mysqli_query($conn, $sql_string) or die("Error in Selecting " . mysqli_error($conn));
        if($result){
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }*/

    
    
?>