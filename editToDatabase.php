<?php
    include 'connection.php';

    $id = strip_tags($_POST['id']);

    $nameP = strip_tags($_POST['nameP']);
    $descriptionP = strip_tags($_POST['descriptionP']);
    $cash = strip_tags($_POST['cash']);
    $n = strip_tags($_POST['n']);
    $rating = strip_tags($_POST['rating']);

    $stmt = $connection->prepare("UPDATE products SET nameP=?,descriptionP=?,cash=?,n=?,rating=? WHERE id=?");
    $stmt->bind_param("ssiiii",$nameP,$descriptionP,$cash,$n,$rating,$id);
    $stmt->execute();

    $stmt->close();

    header("Location: folder.php?ris=edit");
