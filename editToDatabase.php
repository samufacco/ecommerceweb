<?php
    
    include 'connection.php';

    $id = strip_tags($_POST['id']);

    $nome = strip_tags($_POST['nome']);
    $descrizione = strip_tags($_POST['descrizione']);
    $prezzo = strip_tags($_POST['prezzo']);
    $n = strip_tags($_POST['n']);
    $rating = strip_tags($_POST['rating']);

    $stmt = $connection->prepare("UPDATE prodotti SET nome=?,descrizione=?,prezzo=?,n=?,rating=? WHERE id=?");
    $stmt->bind_param("ssiiii",$nome,$descrizione,$prezzo,$n,$rating,$id);
    $stmt->execute();

    $stmt->close();

    header("Location: gestionario.php?ris=edit");
