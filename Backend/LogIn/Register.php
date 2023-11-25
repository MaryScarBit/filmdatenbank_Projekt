<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: X-Requested-With,Origin,Content-Type,Cookie,Accept');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $email = trim($data["email"] ?? '');
        $password = trim($data["password"] ?? '');

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['error' => true, 'errorMessage' => 'Bitte eine gültige E-Mail-Adresse eingeben']);
            exit;
        }     

        $pdo = new PDO('mysql:host=localhost;dbname=todo', 'root', '');
        $stmt = $pdo->prepare('SELECT `userId`, `password` FROM user WHERE email = ?');
        $stmt->execute([$email]);

        if($stmt->rowCount() > 0) {
            echo json_encode(['error' => true, 'errorMessage' => 'E-Mail bereits vergeben']);
            exit;
        }

        $stmt = $pdo->prepare('INSERT INTO user (`email`, `password`) VALUES(?, ?)');
        $stmt->execute([$email, $password]);
        echo json_encode(['error' => false]);
    }
?>