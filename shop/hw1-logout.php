<?php

    // Avvia la sessione
    session_start();
    // Elimina la sessione
    session_destroy();
    // Vai alla login
    header("Location: hw1-shop.php");
    exit;



