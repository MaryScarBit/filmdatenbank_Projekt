<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: X-Requested-With,Origin,Content-Type,Cookie,Accept');

    include '../LogIn/IsAuthorized.php';

    $pdo = new PDO('mysql:host=localhost;dbname=todo', 'root', '');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $value = trim($data["text"] ?? '');
    
        if(strlen($value) == 0) {
            echo json_encode(["ok" => false]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO todo (text, userId) VALUES (?, ?)");
            $ok = $stmt->execute([$value, $user_id]);
            if ($ok) {
                echo json_encode(["ok" => true]);
            } else {
                echo json_encode(["ok" => false, "error" => $stmt->errorInfo()]);
            }
        }
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body, true);
        $value = trim($data["text"] ?? '');
        $id = intval($_GET['id'] ?? 0);

        if(strlen($value) == 0) {
            echo json_encode(["ok" => false]);
        } else {
            $stmt = $pdo->prepare("UPDATE todo SET text = ? WHERE id = ?");
            $ok = $stmt->execute([$value, $id]);
            if ($ok) {
                echo json_encode(["ok" => true]);
            } else {
                echo json_encode(["ok" => false, "error" => $stmt->errorInfo()]);
            }
        }
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->prepare("SELECT * FROM todo where userId = ? ORDER BY id");
        $stmt->execute([$user_id]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($data);
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        $id = intval($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM todo WHERE id = ?");
        $ok = $stmt->execute([$id]);
        echo json_encode(["ok" => $ok]);
    }
    else{
        header('HTTP/1.1 204 No Content');
        die;
    }

    function debug_to_console($data) {
        $output = $data;
        if (is_array($output))
            $output = implode(',', $output);
    
        echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
    }
?>