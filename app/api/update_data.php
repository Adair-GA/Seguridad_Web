<?php
    session_start();
    include '../dbconn.php';
    
    function encrypt(string $plaintext){

        //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
        $cipher = "aes-256-cbc";
        
        $key = file_get_contents('../openssl/key.txt');
        $ivlen = file_get_contents('../openssl/ivlen.txt');
        $iv = file_get_contents('../openssl/iv.txt');
        $iv = base64_decode($iv);
        // Write the contents back to the file
        $output = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }
    
    function decrypt(string $ciphertext){
        
        $cipher = "aes-256-cbc";
        $key = file_get_contents('../openssl/key.txt');
        $iv = file_get_contents('../openssl/iv.txt');
        $iv = base64_decode($iv);
    
        $output = openssl_decrypt(base64_decode($ciphertext), $cipher, $key, 0, $iv);
    
        return $output;
    }
    


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
            $nombre = encrypt($nombre);
            $add=" nombre = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $nombre);
        }
        if ($apellido!=""){
            $apellido = encrypt($apellido);
            $add=" apellidos = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $apellido);
        }
        if ($telefono!=""){
            $telefono = encrypt($telefono);
            $add=" telefono = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $telefono);
        }
        if ($email!=""){
            $email = encrypt($email);
            $add=" email = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $email);
        }
        if ($fecha_nacimiento!=""){
            $fecha_nacimiento = encrypt($fecha_nacimiento);
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
            array_push($params, encrypt($dni));
            $query.=";";

            $stmt = mysqli_prepare($conn, $query) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            $result = mysqli_stmt_execute($stmt);

            if(mysqli_stmt_affected_rows($stmt) > 0){ // Si hay resultado, es decir, si se ha podido actualizar, todo correcto
                echo "success";
            } else {
                echo "fail";
            }
        }
    }
?>