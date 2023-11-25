<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: X-Requested-With,Origin,Content-Type,Cookie,Accept');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $email = trim($data["email"] ?? '');
        $password = trim($data["password"] ?? '');

        $pdo = new PDO('mysql:host=localhost;dbname=todo', 'root', '');
        $stmt = $pdo->prepare('SELECT `userId`, `password` FROM user WHERE email = ?');
        $stmt->execute([$email]);

        if($stmt->rowCount() == 0) {
            http_response_code(401);
            exit;
        }

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $user = $data[0];

        if ($password == $user['password']) {
            $user_id = $user['userId'];
            $tokenData = [
                'user_id' => $user_id,
                'exp' => time() + 3600,
            ];

            $jsonData = json_encode($tokenData);

            $signature = hash_hmac('sha256', $jsonData, 'mein_geheimes_geheimnis');

            $token = base64_encode($jsonData) . '.' . base64_encode($signature);

            http_response_code(200);
            echo json_encode(['token' => $token, 'user_id' => $user_id]);
        } else {
            http_response_code(401);
        }
    }
?>