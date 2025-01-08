<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Neispravan JSON format.']);
        exit;
    }

    $email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $username = trim($data['username'] ?? '');
    $password = trim($data['password'] ?? '');

    if (empty($email) && empty($username)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Email ili korisničko ime je obavezno.']);
        exit;
    }
    if (empty($password)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Lozinka je obavezna.']);
        exit;
    }

    try {
        if (!empty($email)) {
            $query = "SELECT password, role FROM users WHERE email = :email";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['email' => $email]);
        } else {
            $query = "SELECT password, role FROM users WHERE username = :username";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['username' => $username]);
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            http_response_code(200);
            echo json_encode(['status' => 'success', 'role' => $user['role']]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Pogrešan email, korisničko ime ili lozinka.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Greška prilikom obrade zahteva.']);
        error_log($e->getMessage());
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metoda nije dozvoljena.']);
}
?>
