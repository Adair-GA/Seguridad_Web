<?php
    session_start();
    include '../dbconn.php';
    
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
        $types = "";
        $params = array();

        if ($nombre!=""){
            $add=" nombre = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $nombre);
        }
        if ($apellido!=""){
            $add=" apellidos = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $apellido);
        }
        if ($telefono!=""){
            $add=" telefono = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $telefono);
        }
        if ($email!=""){
            $add=" email = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $email);
        }
        if ($fecha_nacimiento!=""){
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
            array_push($params, $dni);
            $query.=";";

            $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            $result = mysqli_stmt_execute($stmt);

            if($result){ // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
                echo "success";
            } else {
                echo "fail";
            }
        }
    }
?>