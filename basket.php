<?php
    include 'connection.php';
?>
<html> 
<head>
    <title>CARRELLO ASL-SHOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans"/>

    <style>
        * {
            font-family: 'Open Sans'; 
        }

        .container{
            background-color: rgb(200,200,200);
        }
    </style>
</head>
<body>

    <div class="container">
        <br>
        <form action="index.php">
            <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
        </form>
        <?php
            if(isset($_GET['order']) && $_GET['order'] == 'miss'){
                echo '<div class="col-11 text-center alert alert-danger ml-3">ERRORE! BISOGNA COMPILARE TUTTI I CAMPI E AVERE CONTENUTO NEL CARRELLO!</div>';
            }
        ?>
        <h1 class="text-center sticker bg-primary font-weight-bold rounded">CARRELLO</h1>
        <br>

        <div class="row">
            <div class="col-1"></div>
            <div class="col-4 p-2 btn btn-outline-dark" >
                <?php

                $stmt = $connection->prepare("SELECT * FROM basket INNER JOIN products ON products.id = basket.idP");
                $stmt->execute();
                $var = $stmt->get_result();
                $stmt->close();

                echo '<p class="text-center p-1"><strong>RIEPILOGO ORDINE</strong></p>';

                $tot=0;
                foreach($var as $row){  

                    $totCash = $row['nInB'] * $row['cash'];
                    $tot += $totCash;
                    echo '<div class="bg-warning text-center">
                            <p><strong>'.$row['nameP'].'</strong>&nbsp&nbsp&nbsp'.$row['nInB'].'pz. -> '.$totCash.'€</p>
                        </div>';

                }
                echo '<p class="text-center p-1"><strong>TOTALE: '.$tot.'€</strong></p>';
                ?>
            </div>
            
            <div class="col-1"></div>
            <div class="col-5 btn btn-outline-dark">
                <h3>Dati personali:</h3>
                <form action="orderConfirmed.php" method="post">
                    <label for="user">NOME: <input type="text" name="user" maxlength="30"></label><br>
                    <label for="city">CITTA': <input type="text" name="city" maxlength="30"></label><br>
                    <label for="adrCons">INDIRIZZO CONSEGNA: <input type="text" name="adrCons" maxlength="40"></label><br>
                    <label for="adrFatt">INDIRIZZO FATTURAZIONE: <input type="text" name="adrFatt" maxlength="40"></label><br>
                    <label for="cap">CAP: <input type="number" name="cap"  min="10000" max="99999"></label><br>
                    <input type="hidden" name="totCash" value="<?=$tot?>">
                    <input type="submit" class="btn btn-outline-success" value="Confirm">
                </form>
            </div>
        </div>

        <div class="row">
                <br><br>
                <div class="col-12 border border-dark mt-4 p-2 rounded" style="background-color: rgb(51,153,255);">
                    <h3 class="text-center">STORICO ORDINI</h3>

                    <?php
                        $stmt = $connection->prepare("SELECT * FROM historical INNER JOIN addresses ON historical.idAdrCons = addresses.idAdr ORDER BY id DESC");
                        $stmt->execute();
                        $var = $stmt->get_result();
                        $stmt->close();

                        if(!mysqli_num_rows($var)){
                            echo '<div class="text-center font-italic">Nessun ordine effettutato.</div>';
                        }
                        else{
                            foreach($var as $row){ 
                            
                                $stmt = $connection->prepare("SELECT nameAdr FROM addresses WHERE idAdr=?"); 
                                $stmt->bind_param("s",$row['idAdrCons']);
                                $stmt->execute();
                                $nomeICons = $stmt->get_result()->fetch_assoc();
                                $stmt->bind_param("s",$row['idAdrFatt']);
                                $stmt->execute();
                                $nomeIFatt = $stmt->get_result()->fetch_assoc();
        
                                echo '<div class="rounded p-2 pl-5  m-2" style="background-color: rgb(240,190,50);">
                                        <p><strong class="font-italic">NUMERO ORDINE</strong>: '.$row['id'].'</p>
                                        <p><strong class="font-italic">Nome</strong>: '.$row['user'].'</p>
                                        <p><strong class="font-italic">Città</strong>: '.$row['city'].'</p>
                                        <p><strong class="font-italic">Indirizzo consegna</strong>: '.$nomeICons['nameAdr'].'</p>
                                        <p><strong class="font-italic">Indirizzo fatturazione</strong>: '.$nomeIFatt['nameAdr'].'</p>
                                        <p><strong class="font-italic">CAP</strong>: '.$row['cap'].'</p>
                                        <p><strong class="font-italic">Totale ordine</strong>: '.$row['totCash'].'€</p>
                                    </div>';
                            }
                        }
                    ?>
                </div>
        </div>
    </div>
</body>
</html>