<?php
    
    include 'connection.php';

    if($_POST['nSel'] == "")
        header("Location: index.php?error=noQuant");
    else{

        $id = strip_tags($_POST['id']);
        $n = strip_tags($_POST['n']);
    
        $stmt = $connection->prepare("INSERT INTO carrello (idProdotto, n) VALUES (?,?)"); 
        $stmt->bind_param("ii",$id,$n);
    
        $stmt->execute();
    
        $stmt->close();
    
        header("Location: index.php");
    }
    
?>