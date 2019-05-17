<?php
    include 'connection.php';
?>
<html>
<head>
    <title>GESTIONARIO ASL-SHOP</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans"/>

    <style>
        * {
            font-family: 'Open Sans'; 
        }

        .container {
            background-color: rgb(180,180,180);
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

    <div class="container pb-2"> 
        <br>
        <form action="index.php">
            <button type="submit" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</button>
        </form>
        <h1 class="text-center">BENVENUTO NEL GESTIONARIO DELL'ASL-SHOP!</h1>

        <?php
            if(isset($_GET['ris'])){
                if($_GET['ris'] == 'add'){
                    echo '<div class="col-12 text-center alert alert-success">PRODOTTO AGGIUNTO CON SUCCESSO!</div>';
                }
                else if($_GET['ris'] == 'edit'){
                    echo '<div class="col-12 text-center alert alert-warning">PRODOTTO MODIFICATO CON SUCCESSO!</div>';
                }
                else if($_GET['ris'] == 'delete'){
                    echo '<div class="col-12 text-center alert alert-danger">PRODOTTO ELIMINATO CON SUCCESSO!</div>';
                }
            }
            else{
                echo '<br>';
            }
        ?>

        <form action="folder.php" method="post">
        <div class="row pagination justify-content-center m-2">
            <button type ="submit" name="add" class="m-3 btn btn-primary">AGGIUNGI PRODOTTO</button>
            <button type ="submit" name="edit" class="m-3 btn btn-warning">MODIFICA PRODOTTO</button>
            <button type ="submit" name="delete" class="m-3 btn btn-danger">ELIMINA PRODOTTO</button>
        </div>  
        </form>

        <?php 
            //schermata aggiunta prodotto
            if(isset($_POST['add'])){

                echo '<div class="">
                        <form action="add.php" method="post" class="rounded border border-primary p-4">
                            <input type="hidden" name="where" value="toDatabase">
                            <label for="nameP"><strong>NOME</strong>: <input type="text" name="nameP" maxlength="30"></label><br>
                            <label for="descriptionP"><strong>DESCRIZIONE</strong>: <input type="text" name="descriptionP" maxlength="500"></label><br>
                            <label for="cash"><strong>PREZZO(€)</strong>: <input type="number" name="cash" min="0,01" max="9999999999,99"></label><br>
                            <label for="n"><strong>NUMERO DISPONIBILE</strong>: <input type="number" name="n" min="1" max="999999"></label><br>
                            <label for="rating"><strong>RATING</strong>: <input type="number" name="rating" min="1" max="5"></label><br>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-plus-circle"></i> Aggiungi</button>
                        </form>
                      </div>';
            }
            //schermata rimozione prodotto
            else if(isset($_POST['delete'])){

                $stmt = $connection->prepare("SELECT * FROM products ORDER BY id DESC");
                $stmt->execute();
                $var = $stmt->get_result();

                foreach($var as $row){  
                
                    echo '<div class="sticker">
                            <p><strong>'.$row['nameP'].'</strong></p>
                            <p class="font-italic scadenza">'.$row['descriptionP'].'</p>
                            <p>'.$row['cash'].'€</p>
                            <p>'.$row['n'].' pz. disponibili</p>
                            <p>Rating: '.$row['rating'].'</p>

                            <form action="remove.php" method="post">
                                    <input type="hidden" name="where" value="toDatabase">
                                    <input type="hidden" name="id" value="'.$row['id'].'">
                                    <button type="submit" onclick="alert("Confermi di cancellare il prodotto dal sito?");" class="btn btn-outline-danger border border-0">
                                            <i class="fa fa-archive"></i> Rimuovi           
                                    </button> 
                            </form>
                        </div>';
                }
            }
            //schermata modifica prodotto
            else if(isset($_POST['edit'])){

                $stmt = $connection->prepare("SELECT * FROM products ORDER BY id DESC");
                $stmt->execute();
                $var = $stmt->get_result();

                foreach($var as $row){  
            
                    echo '<div class="sticker">
                            <p><strong>'.$row['nameP'].'</strong></p>
                            <p class="font-italic scadenza">'.$row['descriptionP'].'</p>
                            <p>'.$row['cash'].'€</p>
                            <p>'.$row['n'].' pz. disponibili</p>
                            <p>Rating: '.$row['rating'].'</p>

                            <form action="folder.php?on=edit" method="post">
                                    <input type="hidden" name="id" value="'.$row['id'].'">
                                    <button type="submit" class="btn btn-warning border border-0">
                                            <i class="fa fa-pencil"></i> Modifica           
                                    </button> 
                            </form>
                        </div>';
                }
                $stmt->close();
            }
            //schermata modifica del prodotto selezionato
            else if(isset($_GET['on']) && $_GET['on'] == 'edit'){
                
                $id = strip_tags($_POST['id']);

                $stmt = $connection->prepare("SELECT * FROM products WHERE id=?");
                $stmt->bind_param("i",$id);
                $stmt->execute();
                $var = $stmt->get_result()->fetch_array();

                echo '<div class="">
                        <form action="editToDatabase.php" method="post" class="rounded border border-primary p-4">
                            <input type="hidden" name="id" value="'.$id.'">
                            <label for="nameP"><strong>NOME</strong>: <input value="'.$var['nameP'].'" type="text" name="nameP" maxlength="30"></label><br>
                            <label for="descriptionP"><strong>DESCRIZIONE</strong>: <input value="'.$var['descriptionP'].'" type="text" name="descriptionP" maxlength="500"></label><br>
                            <label for="cash"><strong>PREZZO(€)</strong>: <input value="'.$var['cash'].'" type="number" name="cash" min="0,01" max="9999999999,99"></label><br>
                            <label for="n"><strong>NUMERO DISPONIBILE</strong>: <input value="'.$var['n'].'" type="number" name="n" min="1" max="999999"></label><br>
                            <label for="rating"><strong>RATING</strong>: <input value="'.$var['rating'].'" type="number" name="rating" min="1" max="5"></label><br>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> Conferma</button>
                        </form>
                      </div>';
            }
        ?>
    </div>
</body>
</html>