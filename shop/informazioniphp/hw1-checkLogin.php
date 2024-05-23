<?php
    session_start();
    
    $data = array();

    $data[] = isset($_SESSION['email']);

    $data = array(
        "loggato" => $data 
    );

    echo json_encode($data);
    exit;    
?>