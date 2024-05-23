<?php
  
    // Verifica l'esistenza di dati POST
    if(isset($_POST["email_reg"]) && isset($_POST["password_reg"]) && isset($_POST["nome_reg"]) && isset($_POST["cognome_reg"]) && isset($_POST['chiave']))
    {
        $errore_reg = array();
        $errore_reg[] = "salta";
        
        $conn = mysqli_connect("localhost", "root", "", "pistacchissimo");
    
        if (!filter_var($_POST["email_reg"], FILTER_VALIDATE_EMAIL)) {
            $errore_reg[] = "Email non valida";
        } 

        $email_reg = mysqli_real_escape_string($conn, strtolower($_POST["email_reg"]));
        

        $password_reg = mysqli_real_escape_string($conn, $_POST["password_reg"]);

        if (strlen($password_reg) < 8 ||
         !preg_match('/[a-z]/', $password_reg) ||
         !preg_match('/[A-Z]/', $password_reg) ||
         !preg_match('/[0-9]/', $password_reg)) {
            $errore_reg[] = "Assicurati che la password abbia almeno 8 caratteri con almeno una lettera minuscola, maiuscola e un numero";
        } 

        $nome_reg = mysqli_real_escape_string($conn, $_POST["nome_reg"]);
        if(!preg_match('/[a-zA-Z]/', $nome_reg)){
            $errore_reg[] = "Nome non valido";
        }

        $cognome_reg = mysqli_real_escape_string($conn, $_POST["cognome_reg"]);
        if(!preg_match('/[a-zA-Z]/', $cognome_reg)){
            $errore_reg[] = "Cognome non valido";
        }

        $password_reg = password_hash($password_reg, PASSWORD_BCRYPT);

        // Cerca se già presente
        $query = "SELECT * FROM user WHERE
                 email = '".$email_reg."'";
        $res = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($res) > 0) {
            $errore_reg[] = "Email gia in uso";
        }

        if(count($errore_reg) == 1) {            
            $query = "INSERT INTO user values('".$email_reg."','".$password_reg."','".$nome_reg."','".$cognome_reg."')";
            mysqli_query($conn, $query);
            // header("Location: ../hw1-shop.php");
            // exit;
        }    
        $errore_reg = array(
            "errori" => $errore_reg
        );
        echo json_encode($errore_reg);        
           
    }