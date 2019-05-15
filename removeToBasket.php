<?php
    
    include 'connection.php';

    $id = strip_tags($_POST['id']);
    $nSel = strip_tags($_POST['nSel']);
    $nDisp = strip_tags($_POST['nDisp']);

    $stmt = $connection->prepare("DELETE FROM carrello WHERE ? = idProdotto"); 
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();

    $nDisp += $nSel;

    $stmt = $connection->prepare("UPDATE prodotti SET n=? WHERE id=?");
    $stmt->bind_param("ii",$nDisp,$id);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php");
    
?>