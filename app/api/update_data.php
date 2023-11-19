<?php
    session_start();
    include '../dbconn.php';
    include '../encryption.php';
    
    function getSalt() {
        include '../dbconn.php';
        $userForSalt = $_SESSION['usuario'];
        $query = "SELECT sal FROM usuarios WHERE usuario = ?";
        $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "s", $userForSalt);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_array($result);
     
        return $row[0];
    }

    $token = $_REQUEST['token'];
    if (hash_equals($token, $_SESSION['token'])){
        
        $nombre = $_REQUEST['name'];
        $apellido = $_REQUEST['surname'];
        $usuario = $_REQUEST['username'];
        $contrasena = $_REQUEST['password'];
        $email = $_REQUEST['email'];
        $telefono = $_REQUEST['phone'];
        $fecha_nacimiento = $_REQUEST['dob'];
        $dni = $_REQUEST['dniPlace'];

        $filesPath= '../openssl/';

        $query = "UPDATE `usuarios` SET";
        $types = "";
        $params = array();

        if ($nombre!=""){
            $nombre = encryption\encrypt($nombre, $filesPath);
            $add=" nombre = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $nombre);
        }
        if ($apellido!=""){
            $apellido = encryption\encrypt($apellido, $filesPath);
            $add=" apellidos = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $apellido);
        }
        if ($telefono!=""){
            $telefono = encryption\encrypt($telefono, $filesPath);
            $add=" telefono = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $telefono);
        }
        if ($email!=""){
            $email = encryption\encrypt($email, $filesPath);
            $add=" email = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $email);
        }
        if ($fecha_nacimiento!=""){
            $fecha_nacimiento = encryption\encrypt($fecha_nacimiento, $filesPath);
            $add=" fecha_nacimiento = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $fecha_nacimiento);
        }
        if ($usuario!=""){
            $add=" usuario = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $usuario);
        }
        if ($contrasena!=""){
            $salt = getSalt();
            $contrasena .= $salt;
            $contrasena = hash('sha512', $contrasena);

            $add=" contraseña = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $contrasena);
        }

        if ($query == "UPDATE `usuarios` SET "){
            echo "No se ha modificado ningún campo";
        } else {
            $query = substr($query,0,-1);  
            $query.=" WHERE dni = ?";
            $types.="s";
            array_push($params, encryption\encrypt($dni, $filesPath));
            $query.=";";

            $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            $result = mysqli_stmt_execute($stmt);

            if($result && mysqli_stmt_affected_rows($stmt) > 0){ // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
                echo "success";
            } else {
                echo "fail";
            }
        }
    }else{
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        echo "ERROR";
        exit;
    } 
?>