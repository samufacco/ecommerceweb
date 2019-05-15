<?php
    include 'connection.php';

    $nome = strip_tags($_POST['nome']);
    $totOrdine = strip_tags($_POST['totaleOrdine']);
    $indCons = strip_tags($_POST['indCons']);
    $indFatt = strip_tags($_POST['indFatt']);
    $comune = strip_tags($_POST['comune']);
    $cap = strip_tags($_POST['cap']);

    //aggiungo indirizzi alla tabella indirizzi
    $stmt = $connection->prepare("INSERT INTO indirizzi (nome) VALUES (?)"); 
    $stmt->bind_param("s",$indCons);
    $stmt->execute();
    $stmt->bind_param("s",$indFatt);
    $stmt->execute();
    //_______________________________________

    //prelevo id indirizzi
    $stmt = $connection->prepare("SELECT id FROM indirizzi WHERE nome=?"); 
    $stmt->bind_param("s",$indCons);
    $stmt->execute();
    $idCons = $stmt->get_result()->fetch_assoc();
    $stmt->bind_param("s",$indFatt);
    $stmt->execute();
    $idFatt = $stmt->get_result()->fetch_assoc();
    //_______________________________________

    //inserisco dati nello storico
    $stmt = $connection->prepare("INSERT INTO storico (nomeUtente,totaleOrdine,idIConsegna,idIFattura,comune,cap) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssiiss",$nome,$totOrdine,$idCons['id'],$idFatt['id'],$comune,$cap);
    $stmt->execute();

    //svuoto carrello
    $stmt = $connection->prepare("DELETE FROM carrello");
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?order=confirm");
?>