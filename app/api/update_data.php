<?php
    session_start();
    include '../dbconn.php';
    
    $request = file_get_contents('php://input'); // La instrucción SQL pasada como parámetro
    $token = substr($request,(strlen($_SESSION['token']))*(-1));
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
    }

    
    
?>