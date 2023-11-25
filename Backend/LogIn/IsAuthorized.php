<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: X-Requested-With,Origin,Content-Type,Cookie,Accept');

    $receivedToken = $_GET['token'];
    $user_id = $_GET['user_id'];

    $tokenParts = explode('.', $receivedToken);

    $jsonData = base64_decode($tokenParts[0]);
    $receivedSignature = base64_decode($tokenParts[1]);

    $expectedSignature = hash_hmac('sha256', $jsonData, 'mein_geheimes_geheimnis');

    if ($receivedSignature === $expectedSignature) {

        $decodedData = json_decode($jsonData, true);

        if ($decodedData['exp'] >= time() && $decodedData['user_id'] == $user_id) {
            http_response_code(200);
        } else {
            http_response_code(401);
        }
    } else {
        http_response_code(401);
    }
?>