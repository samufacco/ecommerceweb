<?php
    include 'connection.php';

    $id = strip_tags($_POST['id']);

    if(isset($_POST['where'])){

        //rimozione prodotto dal database da folder.php
        if($_POST['where'] == 'toDatabase'){

            $stmt = $connection->prepare("DELETE FROM products WHERE id=?"); 
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $stmt->close();

            header("Location: folder.php?ris=delete");
        }
        //rimozione prodotto dal carrello da index.php
        else if($_POST['where'] == 'toBasket'){

            $nSel = strip_tags($_POST['nSel']);
            $nDisp = strip_tags($_POST['nDisp']);

            $stmt = $connection->prepare("DELETE FROM basket WHERE ? = idP"); 
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $stmt->close();

            $nDisp += $nSel;

            $stmt = $connection->prepare("UPDATE products SET n=? WHERE id=?");
            $stmt->bind_param("ii",$nDisp,$id);
            $stmt->execute();
            $stmt->close();

            header("Location: index.php");
        }
    }