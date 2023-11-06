<?php
    session_start();
    include '../dbconn.php';
    
    function getSalt() {
        include '../dbconn.php';
        $userForSalt = $_SESSION['usuario'];
        $query = "SELECT sal FROM usuarios WHERE usuario = '$userForSalt'";
        $result = mysqli_query($conn, $query) or die (mysqli_error($conn));
        $row = mysqli_fetch_array($result);
     
        return $row[0];
    }

    $token = $_REQUEST['token'];
    if ($token==$_SESSION['token']){
        
        $nombre = $_REQUEST['name'];
        $apellido = $_REQUEST['surname'];
        $usuario = $_REQUEST['username'];
        $contrasena = $_REQUEST['password'];
        $email = $_REQUEST['email'];
        $telefono = $_REQUEST['phone'];
        $fecha_nacimiento = $_REQUEST['dob'];
        $dni = $_REQUEST['dniPlace'];

        $query = "UPDATE `usuarios` SET";
        if ($nombre!=""){
            $add=" nombre = '" . $nombre;
            $query.=$add;
            $query.="',";
        }
        if ($apellido!=""){
            $add=" apellidos = '" . $apellido;
            $query.=$add;
            $query.="',";
        }
        if ($telefono!=""){
            $add=" telefono = '" . $telefono;
            $query.=$add;
            $query.="',";
        }
        if ($email!=""){
            $add=" email = '" . $email;
            $query.=$add;
            $query.="',";
        }
        if ($fecha_nacimiento!=""){
            $add=" fecha_nacimiento = '" . $fecha_nacimiento;
            $query.=$add;
            $query.="',";
        }
        if ($usuario!=""){
            $add=" usuario = '" . $usuario;
            $query.=$add;
            $query.="',";
        }
        if ($contrasena!=""){
            $salt = getSalt();
            $contrasena .= $salt;
            $contrasena = hash('sha512', $contrasena);
            
            $add=" contraseña = '" . $contrasena;
            $query.=$add;
            $query.="',";
        }

        if ($query == "UPDATE `usuarios` SET "){
            echo "No se ha modificado ningún campo";
        }else{
            $query = substr($query,0,-1);  
            $query.=" WHERE dni ='";
            $query.=$dni;
            $query.="';";
            $result = mysqli_query($conn, $query) or die("Error in Selecting " . mysqli_error($conn));
        if($result){ // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
            echo "success";
        }else{
            echo "fail";
        }
        }
    }
?>