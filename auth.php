<?php
include 'cors_config.php';
include 'db_connection.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($input['action'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Akcija nije specificirana.']);
        exit;
    }

    $action = $input['action'];
    $email = filter_var(trim($input['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $username = filter_var(trim($input['username'] ?? ''), FILTER_SANITIZE_STRING);
    $password = trim($input['password'] ?? '');

    if ($action === 'register') {

        if (empty($email) || empty($username) || empty($password)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Svi podaci su obavezni.']);
            exit;
        }


        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $role = isset($input['adminCode']) && $input['adminCode'] === 'admin-secret' ? 'admin' : 'user';

        $query = "INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $hashed_password, 'role' => $role]);

        echo json_encode(['status' => 'success', 'message' => 'Registracija uspješna.', 'role' => $role]);
    } elseif ($action === 'login') {

        if (empty($email) && empty($username)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Email ili korisničko ime je obavezno.']);
            exit;
        }

        $query = "SELECT password, role FROM users WHERE email = :email OR username = :username";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $email, 'username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            echo json_encode(['status' => 'success', 'role' => $user['role']]);
        } else {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Pogrešni podaci.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Nepoznata akcija.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Metoda nije dozvoljena.']);
}
?>
