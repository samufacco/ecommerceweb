<?php
    include 'connection.php';

    if(isset($_POST['where'])){

        //aggiungo prodotto al database da folder.php
        if($_POST['where'] == 'toDatabase'){
            
            $nameP = strip_tags($_POST['nameP']);
            $descriptionP = strip_tags($_POST['descriptionP']);
            $cash = strip_tags($_POST['cash']);
            $n = strip_tags($_POST['n']);
            $rating = strip_tags($_POST['rating']);
        
            $stmt = $connection->prepare("INSERT INTO products (nameP,descriptionP,cash,n,rating) VALUES (?,?,?,?,?)"); 
            $stmt->bind_param("ssiii",$nameP,$descriptionP,$cash,$n,$rating);
            $stmt->execute();
            $stmt->close();
        
            header("Location: folder.php?ris=add");
        }
        //aggiungo prodotto al carrello da index.php
        else if($_POST['where'] == 'toBasket'){

            if($_POST['nSel'] == "")
                header("Location: index.php?error=noQuant");
            else{

                $id = strip_tags($_POST['id']);
                $nSel = strip_tags($_POST['nSel']);
                $nDisp = strip_tags($_POST['nDisp']);

                //controllo se c'è già
                $stmt = $connection->prepare("SELECT nInB FROM basket WHERE idP=?"); 
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $var = $stmt->get_result()->fetch_assoc();
                
                if($var['nC'] == NULL){
                    //non è nel carrello quindi
                    //aggiungo in una nuova riga
                    $stmt = $connection->prepare("INSERT INTO basket (idP, nInB) VALUES (?,?)"); 
                    $stmt->bind_param("ii",$id,$nSel);
                    $stmt->execute();
                    
                }
                else{
                    //oppure aggiorno la riga del carrello
                    $somma = $var['nInB'] + $nSel;
                    $stmt = $connection->prepare("UPDATE basket SET nInB=? WHERE idP=?");
                    $stmt->bind_param("ii",$somma,$id);
                    $stmt->execute();
                }
                
                //aggiorno tabella prodotti
                $nDisp -= $nSel;
                $stmt = $connection->prepare("UPDATE products SET n=? WHERE id=?");
                $stmt->bind_param("ii",$nDisp,$id);
                $stmt->execute();

                $stmt->close();
        
                header("Location: index.php");
            }
        }
    }