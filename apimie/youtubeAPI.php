<?php
    
    $api_key = "AIzaSyCglc_QZb0QEZxfu2AK_N8lXxKCgdv7b8g";
    $playlist = "UU_dUqIKuzCvsE_bg_5AYMrQ";

    $curl = curl_init();  
    
    
    $val = http_build_query(
        array(
            "part" => "snippet",
            "maxResults" => 3,
            "playlistId"=>$playlist,
            "key"=> $api_key
        )
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,
        "https://youtube.googleapis.com/youtube/v3/playlistItems?".$val
    );

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);


    echo $result;

    curl_close($curl);
    
?>
