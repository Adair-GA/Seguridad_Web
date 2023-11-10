<?php
    include "../dbconn.php";
    $id = $_POST['id'];
    $sql = "DELETE FROM horoscopos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if($stmt->affected_rows > 0){
        echo "success";
    }else{
        echo "failed";
    }
?>