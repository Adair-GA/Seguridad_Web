<?php
    session_start(); //Accedemos a la sesión iniciada
    session_destroy(); //Cerramos la sesión PHP
    header("Location: index.php");
    /* 
     envía el encabezado al navegador, sino que también devuelve el código de status (302) REDIRECT
     al navegador a no ser que el código de status 201 o 3xx ya haya sido enviado.

     Redirección del navegador

     Asegurándonos de que el código interior no será ejecutado cuando se realiza la redirección.

     https://www.php.net/manual/es/function.header.php
    */
?>