<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: add_news.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date = $_POST['date'] ?? null;
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $image = $_FILES['image'] ?? null;

    if (!$date || !$title || !$content) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Svi podaci su obavezni.']);
        exit;
    }

    $image_url = null;
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
        $uploadDir = DIR . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '-' . basename($image['name']);
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
            $image_url = 'uploads/' . $fileName;
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Greška prilikom čuvanja slike.']);
            exit;
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Vijest uspešno dodata.', 'image_url' => $image_url]);  // Ispravljeno: 'image_url' umesto 'imageUrl'
} else {
    http_response_code(405);
    echo json_encode(['status' => 'Metoda nije dozvoljena.']);
}
?>
