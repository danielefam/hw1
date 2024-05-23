<?php
    session_start();

    if (!isset($_SESSION['email'])) {        
        header('Location: ../hw1-login-signup.php');
        exit();
    }

    if(isset($_GET['preferiti-carrello']) && isset($_SESSION['email'])){
        $preferiti = array();
        $carrello = array();
        $transazioni = array();

        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo"); 
        
        $email = mysqli_real_escape_string($conn, $_SESSION['email']);

        $query = "SELECT prodotti.* FROM 
            preferiti JOIN prodotti on id_prodotto = id
            WHERE email_utente = '".$email."'";
        $prefres = mysqli_query($conn, $query);

        $query = "select prodotti.* , ifnull(prezzo_scontato , -1) as prezzo_scontato, carrello.quantita
            from prodotti left join insconto on id = insconto.id_prodotto
            join carrello on id = carrello.id_prodotto
            WHERE email_utente = '".$email."'";        
        $carrellores = mysqli_query($conn, $query);


        $query = "SELECT prodotti.*, acquistiPassati.quantita as quantita_acquistata, acquistiPassati.id as id_transazione FROM
        acquistiPassati JOIN prodotti on id_prodotto = prodotti.id
        WHERE email_utente = '".$email."' AND acquistiPassati.id NOT IN (SELECT * FROM acquistiNascosti)";        
        $transazionires = mysqli_query($conn, $query);        

        while($row = mysqli_fetch_assoc($prefres)){
            $preferiti[] = $row;
        }

        while($row = mysqli_fetch_assoc($carrellores)){
            $carrello[] = $row;
        }

        while($row = mysqli_fetch_assoc($transazionires)){
            $transazioni[] = $row;
        }

        $query = "SELECT spesa FROM spesaAttuale WHERE email_utente ='".$email."'";
        $res = mysqli_query($conn, $query);
        $saldo = 0;
        while($row = mysqli_fetch_row($res)){
            $saldo = $row[0];
        }

        $data = array (
            "preferiti" => $preferiti,
            "carrello" => $carrello,
            "transazioni" => $transazioni,
            "saldo" => $saldo
        );

        echo json_encode($data);

        mysqli_free_result($res);
        mysqli_close($conn);
    }
