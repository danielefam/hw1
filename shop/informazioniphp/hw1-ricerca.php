<?php
    if(isset($_POST["catalogoProdotto"])){
        $nelCatalogo = array();
        $nelCatalogoInSaldo = array();

        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");

        $ricerca_intera = $_POST["catalogoProdotto"];

        $ricerca_divisa = explode(" ", $ricerca_intera);

        foreach($ricerca_divisa as $prodotto){
            $prodotto = mysqli_real_escape_string($conn, $prodotto);
            // $prodotto = mysqli_real_escape_string($conn, $_POST["catalogoProdotto"]);
            
            mysqli_query($conn,"call optag('".$prodotto."')");
            $res = mysqli_query($conn, "SELECT * FROM tagID");

            while ($row = mysqli_fetch_assoc($res)) {
                
                if(in_array($row, $nelCatalogo))
                    continue;

                $nelCatalogo[] = $row;
                if($row['prezzo_scontato'])
                    $nelCatalogoInSaldo[] = $row;
            }

        }
    
        mysqli_free_result($res);
        mysqli_close($conn);

        $data = array(
            "prodotti" => $nelCatalogo,
            "prodotti_in_saldo" => $nelCatalogoInSaldo
        );
        
        echo json_encode($data);
    }
?>