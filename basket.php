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

        body{
            background-color: rgb(154,221,242);
        }

    </style>

</head>
<body>

    <div class="container">
        <br>
        <h1 class="text-center sticker bg-primary font-weight-bold">CARRELLO</h1>
        <br>

        <div class="row">
            <div class="col-1"></div>
            <div class="col-4 p-2 btn btn-outline-dark" >
                <?php

                $stmt = $connection->prepare("SELECT * FROM carrello INNER JOIN prodotti ON prodotti.id = carrello.idProdotto");
                $stmt->execute();
                $var = $stmt->get_result();
                $stmt->close();

                echo '<p class="text-center p-1"><strong>RIEPILOGO ORDINE</strong></p>';

                $tot=0;
                foreach($var as $riga){  

                    $totCash = $riga['nC'] * $riga['prezzo'];
                    $tot += $totCash;
                    echo '<div class="bg-warning text-center">
                            <p><strong>'.$riga['nome'].'</strong>&nbsp&nbsp&nbsp'.$riga['nC'].'pz. -> '.$totCash.'€</p>
                        </div>';

                }
                echo '<p class="text-center p-1"><strong>TOTALE: '.$tot.'€</strong></p>';
                ?>
            </div>
            
            <div class="col-1"></div>
            <div class="col-5 btn btn-outline-dark">
                
                <h3>Dati personali:</h3>

                <form action="orderConfirmed.php" method="post">

                    <label for="nome">NOME: <input type="text" name="nome" ></label><br>
                    <label for="comune">CITTA': <input type="text" name="comune" ></label><br>
                    <label for="indCons">INDIRIZZO CONSEGNA: <input type="text" name="indCons" ></label><br>
                    <label for="indFatt">INDIRIZZO FATTURAZIONE: <input type="text" name="indFatt" ></label><br>
                    <label for="cap">CAP: <input type="text" name="cap" ></label><br>
                    <input type="hidden" name="totaleOrdine" value="<?=$tot?>">

                    <input type="submit" class="btn btn-outline-success" value="Confirm">
                </form>
                 
            </div>
        </div>

        <div class="row">
                <div class="col-12">

                    <?php

                    $stmt = $connection->prepare("SELECT * FROM storico INNER JOIN indirizzi ON storico.idIConsegna = indirizzi.id AND storico.idIFattura = indirizzi.id");
                    $stmt->execute();
                    $var = $stmt->get_result();
                    $stmt->close();

                    foreach($var as $riga){ 
                        
                        echo '<div class="bg-danger">
                                <p>Nome: '.$riga['nomeUtente'].'</p>
                                <p>Città: '.$riga['comune'].'</p>
                                <p>CAP: '.$riga['cap'].'</p>
                                <p>Totale ordine: '.$riga['totaleOrdine'].'</p>

                            </div>';

                    }
                    ?>

                </div>
        </div>       

    </div>

</body>
</html>