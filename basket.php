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
        <h1 class="text-center sticker bg-primary font-weight-bold rounded">CARRELLO</h1>
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
                <br><br>
                <div class="col-12 border border-dark mt-4 p-2 rounded" style="background-color: rgb(51,153,255);">
                    <h3 class="text-center">STORICO ORDINI</h3>

                    <?php

                    $stmt = $connection->prepare("SELECT * FROM storico INNER JOIN indirizzi ON storico.idIConsegna = indirizzi.idIndirizzo ORDER BY id DESC");
                    $stmt->execute();
                    $var = $stmt->get_result();
                    $stmt->close();

                    if(!mysqli_num_rows($var)){
                        echo '<div class="text-center font-italic">Nessun ordine effettutato.</div>';
                    }
                    else{
                        foreach($var as $riga){ 
                        
                            $stmt = $connection->prepare("SELECT nomeIndirizzo FROM indirizzi WHERE idIndirizzo=?"); 
                            $stmt->bind_param("s",$riga['idIConsegna']);
                            $stmt->execute();
                            $nomeICons = $stmt->get_result()->fetch_assoc();
                            $stmt->bind_param("s",$riga['idIFattura']);
                            $stmt->execute();
                            $nomeIFatt = $stmt->get_result()->fetch_assoc();
    
                            echo '<div class="rounded p-2 pl-5  m-2" style="background-color: rgb(240,190,50);">
                                    <p><strong class="font-italic">NUMERO ORDINE</strong>: '.$riga['id'].'</p>
                                    <p><strong class="font-italic">Nome</strong>: '.$riga['nomeUtente'].'</p>
                                    <p><strong class="font-italic">Città</strong>: '.$riga['comune'].'</p>
                                    <p><strong class="font-italic">Indirizzo consegna</strong>: '.$nomeICons['nomeIndirizzo'].'</p>
                                    <p><strong class="font-italic">Indirizzo fatturazione</strong>: '.$nomeIFatt['nomeIndirizzo'].'</p>
                                    <p><strong class="font-italic">CAP</strong>: '.$riga['cap'].'</p>
                                    <p><strong class="font-italic">Totale ordine</strong>: '.$riga['totaleOrdine'].'€</p>
                                </div>';
                        }
                    }
                    ?>

                </div>
        </div>       

    </div>

</body>
</html>