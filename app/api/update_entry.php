<?php
    include '../dbconn.php';
    
    // modifyEntry.js líneas 13-16, usamos método POST, tendremos que recibir body de alguna forma
    // y se hace con file_get_contents('php://input')
    $sql_string = file_get_contents('php://input'); // La instrucción SQL pasada como parámetro
    
    $result = mysqli_query($conn, $sql_string) or die("Error in Selecting " . mysqli_error($conn));
    if($result){ // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
        echo "success";
    }else{
        echo "fail";
    }
?>