<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:4200");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit;
}

header('Content-Type: application/json');

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $input = file_get_contents('php://input');
    $data = json_decode($input);


    if (!$data) {
        http_response_code(400); // Bad Request
        echo generateJsonResponse('error', 'Neispravan JSON format.');
        exit;
    }


    $email = filter_var(trim($data->email ?? ''), FILTER_SANITIZE_EMAIL);
    $username = filter_var(trim($data->username ?? ''), FILTER_SANITIZE_STRING);
    $password = trim($data->password ?? '');


    if (empty($email) || empty($username) || empty($password)) {
        http_response_code(400); // Bad Request
        echo generateJsonResponse('error', 'Svi podaci su obavezni.');
        exit;
    }


    if (strlen($username) < 3) {
        http_response_code(400); // Bad Request
        echo generateJsonResponse('error', 'Korisničko ime mora imati najmanje 3 karaktera.');
        exit;
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400); // Bad Request
        echo generateJsonResponse('error', 'Neispravan format e-mail adrese.');
        exit;
    }


    $query = "SELECT email FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        http_response_code(409); // Conflict
        echo generateJsonResponse('error', 'E-mail adresa je već u upotrebi.');
        exit;
    }


    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        http_response_code(400); // Bad Request
        echo generateJsonResponse('error', 'Lozinka mora imati najmanje 8 karaktera, jedno veliko slovo, jedno malo slovo, jednu cifru i jedan specijalni znak.');
        exit;
    }


    $hashed_password = password_hash($password, PASSWORD_BCRYPT);


    try {

        $query = "INSERT INTO users (username, email, password, role, created_at)
                  VALUES (:username, :email, :password, :role, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password,
            'role' => 'standard',
        ]);

        http_response_code(201);
        echo generateJsonResponse('success', 'Korisnik je uspešno registrovan.');
    } catch (PDOException $e) {
        http_response_code(500);
        echo generateJsonResponse('error', 'Došlo je do greške prilikom registracije.');
        error_log($e->getMessage());
    }
} else {
    http_response_code(405);
    echo generateJsonResponse('error', 'Metoda nije dozvoljena.');
}

function generateJsonResponse($status, $message) {
    return json_encode([
        'status' => $status,
        'message' => $message
    ]);
}
?>
