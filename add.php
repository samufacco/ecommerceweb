<?php
    
include 'connection.php';

if(isset($_POST['where'])){

    //aggiungo prodotto al database da gestionale.php
    if($_POST['where'] == 'toDatabase'){
        
        $nome = strip_tags($_POST['nome']);
        $descrizione = strip_tags($_POST['descrizione']);
        $prezzo = strip_tags($_POST['prezzo']);
        $n = strip_tags($_POST['n']);
        $rating = strip_tags($_POST['rating']);
    
        $stmt = $connection->prepare("INSERT INTO prodotti (nome,descrizione,prezzo,n,rating) VALUES (?,?,?,?,?)"); 
        $stmt->bind_param("ssiii",$nome,$descrizione,$prezzo,$n,$rating);
        $stmt->execute();
    
        $stmt->close();
    
        header("Location: gestionario.php?ris=add");
    }
    //aggiungo prodotto al carrello da index.php
    else if($_POST['where'] == 'toBasket'){

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
    }
}

    
    
?>