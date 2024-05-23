<!-- da aggiornare -->
<?php
    session_start();
    if(!isset($_SESSION['email']) || $_SESSION['email'] != "admin@gmail.com"){
        header('Location: ../hw1-shop.php');
        exit;
    }

    if(isset($_POST['nome']) && isset($_POST['descrizione']) && isset($_POST['srcimg']) &&
        isset($_POST['prezzo']) && isset($_POST['quantita']))
    {
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
        $nome = mysqli_escape_string($conn, $_POST['nome']);
        $descrizione = mysqli_escape_string($conn, $_POST['descrizione']);
        $srcimg = mysqli_escape_string($conn, $_POST['srcimg']);
        $prezzo = mysqli_escape_string($conn, $_POST['prezzo']);
        $quantita = mysqli_escape_string($conn, $_POST['quantita']);

        $values = $nome."','".$descrizione."','".$srcimg."','".$prezzo."','".$quantita;

        $query = "INSERT INTO prodotti(nome,descrizione,img_src,prezzo,quantita)
            values ('".$values."')";
        mysqli_query($conn, $query);

        $query = "SELECT id from prodotti where nome = '".$nome."'";
        $res = mysqli_query($conn, $query);

        // suppongo che restituisca un solo prodotto
        $row = mysqli_fetch_row($res);
        $id = $row[0];
        

        $scontato = $_POST['scontato'];

        if($scontato == "y"){
            $prezzo = mysqli_escape_string($conn, $_POST['scontoprezzo']);

            $query = "INSERT INTO inSconto
                    values ('".$id."','".$prezzo."')";
            mysqli_query($conn, $query);
        }


        mysqli_free_result($res);
        mysqli_close($conn);
    }

?>

<html>
    <head>
        <script src='admin.js' defer></script>
        <link rel='stylesheet' href='admin.css'>
    </head>
    <body>
        <h3>Aggiungi evento</h3>
        <main>
            <form name = "aggiungi-prodotto">
                <p>
                    <label>Nome <input type='text' name='nome'></label>
                </p>

                <p>
                    <label>Descrizione <input type='text' name='descrizione'></label>
                </p>
                
                <p>
                    <label>Source img <input type='text' name='srcimg'></label>
                </p>

                <p>
                    <label>Prezzo <input type='text' name='prezzo'></label>
                </p>

                <p>
                    <label>Quantità <input type='text' name='quantita'></label>
                </p>

                <br>
                <p>
                    <input type = 'radio' name='scontato' value='n' id="non-scon">Non scontato
                </p>

                <p>
                    <input type = 'radio' name='scontato' value='y' id="scon">Scontato
                </p>

                <p>
                    <label id="scontoprezzo" class = "hidden">Prezzo Scont <input type='text' name='scontoprezzo'></label>
                </p>
                

                <p>
                <label>&nbsp;<input type='submit'></label>
                </p>
            </form>
        </main> 
    </body>
</html>




