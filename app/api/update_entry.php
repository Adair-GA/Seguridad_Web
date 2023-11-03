<?php
    session_start();
    include '../dbconn.php';
    
    $request = file_get_contents('php://input'); // La instrucción SQL pasada como parámetro
    $token = substr($request,(strlen($_SESSION['token']))*(-1));
    if ($token==$_SESSION['token']){
        // modifyEntry.js líneas 13-16, usamos método POST, tendremos que recibir body de alguna forma
        // y se hace con file_get_contents('php://input')
        $sql_string = substr($request,0,strlen($request)-strlen($_SESSION['token']));

        $result = mysqli_query($conn, $sql_string) or die("Error in Selecting " . mysqli_error($conn));
        if($result){ // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
            echo "success";
        }else{
            echo "fail";
        }
    }else{
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    }
    

?>