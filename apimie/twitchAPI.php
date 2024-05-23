<?php
    if(isset($_POST["cerca_gioco"])){
        $client_id = "mlu5adq2xr05aa8yta1dd5fs3m11tf";
        $client_secret = "hijb6vk57pg6btye6fp16h36mx82np";

        // token
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://id.twitch.tv/oauth2/token");
        curl_setopt($curl, CURLOPT_POST, 1);
        $body = http_build_query(array("client_id" => $client_id,
                                    "client_secret" => $client_secret,
                                    "grant_type" => "client_credentials"));
        // $body = "client_id=" . $client_id . "&client_secret=" . $client_secret . "&grant_type=client_credentials";

        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);

        // $headers = array("Authorization: Basic ".base64_encode($client_id .":".$client_secret));
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        
        
        $token = json_decode($result)->access_token;
        $val = http_build_query(array("name" => $_POST["cerca_gioco"]));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.twitch.tv/helix/games?".$val);

        $headers = array("Authorization: Bearer ".$token, "Client-Id: ".$client_id);

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);

        print_r($result);

        curl_close($curl);
    }
?>
