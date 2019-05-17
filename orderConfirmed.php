<?php
    include 'connection.php';

    //se tutti i campi sono stati compilati
    if($_POST['user']!=NULL && $_POST['totCash']!=0 && $_POST['adrCons']!=NULL && 
        $_POST['adrFatt']!=NULL && $_POST['city']!=NULL && $_POST['cap']!=NULL){

        $user = strip_tags($_POST['user']);
        $totCash = strip_tags($_POST['totCash']);
        $adrCons = strip_tags($_POST['adrCons']);
        $adrFatt = strip_tags($_POST['adrFatt']);
        $city = strip_tags($_POST['city']);
        $cap = strip_tags($_POST['cap']);

        //aggiungo indirizzi alla tabella indirizzi
        $stmt = $connection->prepare("INSERT INTO addresses (nameAdr) VALUES (?)"); 
        $stmt->bind_param("s",$adrCons);
        $stmt->execute();
        $stmt->bind_param("s",$adrFatt);
        $stmt->execute();
        //_______________________________________

        //prelevo id indirizzi
        $stmt = $connection->prepare("SELECT idAdr FROM addresses WHERE nameAdr=?"); 
        $stmt->bind_param("s",$adrCons);
        $stmt->execute();
        $idCons = $stmt->get_result()->fetch_assoc();
        $stmt->bind_param("s",$adrFatt);
        $stmt->execute();
        $idFatt = $stmt->get_result()->fetch_assoc();
        //_______________________________________

        //inserisco dati nello storico
        $stmt = $connection->prepare("INSERT INTO historical (user,totCash,idAdrCons,idAdrFatt,city,cap) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssiiss",$user,$totCash,$idCons['idAdr'],$idFatt['idAdr'],$city,$cap);
        $stmt->execute();

        //svuoto carrello
        $stmt = $connection->prepare("DELETE FROM basket");
        $stmt->execute();
        $stmt->close();

        header("Location: index.php?order=confirm");
    }
    else{
        header("Location: basket.php?order=miss");
    }