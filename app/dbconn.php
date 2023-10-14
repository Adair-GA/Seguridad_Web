<?php 
  /*Crear la conexión con la base de datos. 
  Este fichero nos ahorrará código, ya que para abrir una conexión será suficiente con incluir
  el fichero*/
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }
?>
