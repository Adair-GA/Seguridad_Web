<?php
    session_start();
    include '../dbconn.php';
    
    function encrypt(string $plaintext){

        //$key should have been previously generated in a cryptographically safe way, like openssl_random_pseudo_bytes
        $cipher = "aes-128-gcm";
        
        $key = file_get_contents('../openssl/key.txt');
        $ivlen = file_get_contents('../openssl/ivlen.txt');
        $iv = file_get_contents('../openssl/iv.txt');
        // Write the contents back to the file
    
        if (in_array($cipher, openssl_get_cipher_methods()))
        {
            //$ivlen = openssl_cipher_iv_length($cipher);
            //$iv = openssl_random_pseudo_bytes($ivlen);
            //file_put_contents('../ivlen.txt', $ivlen);
            //file_put_contents('../iv.txt', $iv);
            $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv, $tag);
            //echo $ciphertext."\n";
            //store $cipher, $iv, and $tag for decryption later
            
        }
    
        return $ciphertext;
    }
    
    function decrypt(string $ciphertext){
        
        $cipher = "aes-128-gcm";
        $key = file_get_contents('../openssl/key.txt');
        $iv = file_get_contents('../openssl/iv.txt');
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv);
        //echo $original_plaintext."\n";
    
        return $original_plaintext;
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
            $nombre = encrypt ($nombre);
            $add=" nombre = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $nombre);
        }
        if ($apellido!=""){
            $apellido = encrypt ($apellido);
            $add=" apellidos = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $apellido);
        }
        if ($telefono!=""){
            $telefono = encrypt ($telefono);
            $add=" telefono = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $telefono);
        }
        if ($email!=""){
            $email = encrypt ($email);
            $add=" email = ?";
            $query.=$add;
            $query.=",";
            $types.="s";
            array_push($params, $email);
        }
        if ($fecha_nacimiento!=""){
            $fecha_nacimiento = encrypt ($fecha_nacimiento);
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