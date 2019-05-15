<?php
    
    include 'connection.php';

    if($_POST['nSel'] == "")
        header("Location: index.php?error=noQuant");
    else{

        $id = strip_tags($_POST['id']);
        $nSel = strip_tags($_POST['nSel']);
        $nDisp = strip_tags($_POST['nDisp']);

        //aggiungo in una nuova riga
        $stmt = $connection->prepare("INSERT INTO carrello (idProdotto, nC) VALUES (?,?)"); 
        $stmt->bind_param("ii",$id,$nSel);
        $stmt->execute();
        
    
        $nDisp -= $nSel;

        $stmt = $connection->prepare("UPDATE prodotti SET n=? WHERE id=?");
        $stmt->bind_param("ii",$nDisp,$id);
        $stmt->execute();
        $stmt->close();

        header("Location: index.php");
    }
    
?>