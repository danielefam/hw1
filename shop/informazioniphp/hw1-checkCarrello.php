<?php
    
    session_start();
    if(!isset($_SESSION['email'])){
        header("Location: ../hw1-login-signup.php");
        exit;
    }
    
    if(isset($_GET['id']) && isset($_GET['quantita'])){
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");

        $email = mysqli_escape_string($conn, $_SESSION['email']);
        $id = mysqli_escape_string($conn, $_GET['id']);
        $quantita = mysqli_escape_string($conn, $_GET['quantita']);
        $risposta = array();
        
        $query = "SELECT quantita FROM prodotti where id='".$id."'";
        $res = mysqli_query($conn, $query);
        

        while ($row = mysqli_fetch_assoc($res)) {
            
            if(($quantita_disponibile = $row['quantita']) < $quantita || $quantita <= 0){
                $risposta[] = "false";
            }else{
                $risposta[] = "true";
                $nuova_quantita = $quantita_disponibile - $quantita;
                $query = "UPDATE prodotti set quantita = '".$nuova_quantita."' where id = '".$id."'";
                mysqli_query($conn, $query);

                $query = "SELECT * FROM carrello where id_prodotto='".$id."' AND email_utente='".$_SESSION['email']."'";
                $isPresente = mysqli_query($conn, $query);

                if(mysqli_num_rows($isPresente) > 0){
                    $row = mysqli_fetch_assoc($isPresente);
                    $quantita = $row['quantita'] + $quantita;
                    $query = "UPDATE carrello set quantita = '".$quantita."' where id_prodotto='".$id."' AND email_utente='".$_SESSION['email']."'";
                    mysqli_query($conn, $query);
                }else{
                    $query = "INSERT INTO carrello values('".$_SESSION['email']."','".$id."','".$quantita."')";
                    mysqli_query($conn, $query);                    
                }               
                
            }

            echo json_encode($risposta);

        }        
    }
?>

<?php
    if(isset($_GET['id']) && isset($_GET['elimina-carrello'])){

        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
        $email = mysqli_escape_string($conn, $_SESSION['email']);
        $id = mysqli_escape_string($conn, $_GET['id']);
        
        $query = "SELECT quantita FROM prodotti where id='".$id."'";
        $res = mysqli_query($conn, $query);
        

        while ($row = mysqli_fetch_assoc($res)) {
            $quantita_disponibile = $row['quantita'];

            $query = "SELECT * FROM carrello where id_prodotto='".$id."' AND email_utente='".$_SESSION['email']."'";
            $isPresente = mysqli_query($conn, $query);
            $row = mysqli_fetch_assoc($isPresente);
            $quantita_disponibile = $quantita_disponibile + $row['quantita'];

            $query = "UPDATE prodotti set quantita = '".$quantita_disponibile."' where id='".$id."'";
            mysqli_query($conn, $query);

            $query = "DELETE from carrello where id_prodotto = '".$id."' AND email_utente = '".$email."'";
            mysqli_query($conn, $query);
            
        }
        
        mysqli_free_result($res);
        mysqli_close($conn);
    }
?>

<?php
    if(isset($_GET['id-tran']) && isset($_GET['nascondi-transazione'])){

        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
        $email = mysqli_escape_string($conn, $_SESSION['email']);
        $id = mysqli_escape_string($conn, $_GET['id-tran']);
        
        $query = "INSERT INTO acquistiNascosti VALUES('".$id."')";
        $res = mysqli_query($conn, $query);
        
        mysqli_free_result($res);
        mysqli_close($conn);
    }
?>

                        