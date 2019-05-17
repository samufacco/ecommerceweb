<?php
    include 'connection.php';
?>
<html> 
<head>
    <title>ASL-SHOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans"/>

    <style>
        * {
            font-family: 'Open Sans'; 
        }

        body {
            background-color : rgb(216,192,34);
        }

        .sticker {

            margin: 10px 0px 10px 0px;
            padding: 15px;
            border-radius: 5px;
            background-color: rgb(200, 200, 171);
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }
    </style>
</head>
<body>

    <div class="container">
        <br>
        <h1 class="text-center">Welcome to ASL-SHOP</h1>
        <br>
        
        <div class="row">
            <form action="gestionario.php">
                <button type="submit" class="btn btn-primary">VAI AL GESTIONALE</button>
            </form>
            <?php
            //ordine confermato
            if(isset($_GET['order']) && $_GET['order'] == 'confirm')
                echo '<div class="col-8 text-center alert alert-success ml-3">ORDINE CONFERMATO!</div>';
            ?>
        </div>

        <div class="row">
            <!--ZONA DEDICATA AI PRODOTTI-->
            <div class="col-8 sticker bg-light">
                <label for="prodotti">PRODOTTI:</label>

                <?php
                    $stmt = $connection->prepare("SELECT * FROM prodotti ORDER BY id DESC");
                    $stmt->execute();
                    $var = $stmt->get_result();

                    if(isset($_GET['error'])){

                        if($_GET['error'] == 'noQuant')
                            echo '<script> alert("Inserire quantità!");</script>';
                    }

                    foreach($var as $row){  
                        
                        echo '<div class="sticker">
                                <p><strong>'.$row['nome'].'</strong></p>
                                <p class="font-italic scadenza">'.$row['descrizione'].'</p>
                                <p>'.$row['prezzo'].'€</p>
                                <p>'.$row['n'].' pz. disponibili</p>

                                <form action="add.php" method="post">
                                    <input class="d-inline" type="number" name="nSel" min="1" max="'.$row['n'].'">
                                    <div class="col-2 align-self-end d-inline">
                                        <input type="hidden" name="where" value="toBasket">
                                        <input type="hidden" name="id" value="'.$row['id'].'">
                                        <input type="hidden" name="nDisp" value="'.$row['n'].'">
                                        <button type="submit" class="btn btn-outline-primary border border-0">
                                                <i class="fa fa-plus-circle"></i> Aggiungi al carrello           
                                        </button> 
                                    </div>
                                </form>
                              </div>';

                    }
                    $stmt->close();
                ?>

            </div>

            <!--ZONA DEDICATA Al CARRELLO-->
            <div class="col-3 sticker bg-warning">

                <?php
                $stmt = $connection->prepare("SELECT * FROM carrello INNER JOIN prodotti ON prodotti.id = carrello.idProdotto");
                
                $stmt->execute();
                $var = $stmt->get_result();
                $stmt->close();
                
                echo '<form action="basket.php" method="post">
                        <label for="carrello">
                        <button class="btn btn-success" type="submit">Vai al carrello</button>
                        </label>
                      </form>';

                foreach($var as $row){  

                    $totCash = $row['nC'] * $row['prezzo'];

                    echo '<div class="sticker">
                            <p><strong>'.$row['nome'].'</strong></p>
                            <p>'.$row['nC'].'pz. -> '.$totCash.'€</p>

                            <form action="remove.php" method="post">
                                <input type="hidden" name="where" value="toBasket">
                                <input type="hidden" name="id" value="'.$row['id'].'">
                                <input type="hidden" name="nDisp" value="'.$row['n'].'">
                                <input type="hidden" name="nSel" value="'.$row['nC'].'">
                                <input type="submit" class="btn btn-outline-danger border border-0" value="X"> 
                            </form>
                            </div>';

                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>