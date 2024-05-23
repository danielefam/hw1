<?php
    session_start();    

    if(isset($_GET["id_prodotto"]) && isset($_GET["azione"]) && isset($_SESSION['email'])){     
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo"); 
        $verifica = array();
        $verifica[] = "entrato";

        $id = mysqli_real_escape_string($conn, $_GET["id_prodotto"]);
        $daEliminare = mysqli_real_escape_string($conn, $_GET["azione"]);
        $email = mysqli_real_escape_string($conn, $_SESSION["email"]);

            

        if($daEliminare == "true"){
            $query = "DELETE from preferiti where id_prodotto = '".$id."' AND email_utente = '".$email."'";
            $verifica[] = "rimosso dai preferiti";
        }else{
            $query = "INSERT INTO preferiti values('".$email."','".$id."')";
            $verifica[] = "aggiunto ai preferiti";
        }
        
        mysqli_query($conn, $query);
    
        mysqli_free_result($res);
        mysqli_close($conn);

        $verifica = array(
            "prova" => $verifica
        );
    }
?>

<?php
    if(isset($_GET['ottieni_preferiti']) && isset($_SESSION['email'])){
        $id_array = array();
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo"); 
        
        $email = mysqli_real_escape_string($conn, $_SESSION['email']);

        $query = "SELECT * FROM preferiti WHERE email_utente = '".$email."'";

        $res = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($res)){
            $id_array[] = $row['id_prodotto'];
        }

        $id_array = array (
            "id_array" => $id_array
        );

        echo json_encode($id_array);
    }
?>
