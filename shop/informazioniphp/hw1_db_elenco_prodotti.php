<?php
    $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
    
    $foto = array();
    $foto_in_saldo = array();

    $res = mysqli_query($conn,"SELECT * FROM prodotti order by id");
    $res_in_saldo = mysqli_query($conn,"SELECT prodotti.*, prezzo_scontato FROM inSconto JOIN prodotti ON id = id_prodotto");

    while ($row = mysqli_fetch_assoc($res)) {
        $foto[] = $row;
    }

    while ($row = mysqli_fetch_assoc($res_in_saldo)) {
        $foto_in_saldo[] = $row;
    }

    mysqli_free_result($res);
    mysqli_close($conn);

    $data = array(
        "prodotti"=> $foto,
        "prodotti_in_saldo"=> $foto_in_saldo
    );
    
    echo json_encode($data);
