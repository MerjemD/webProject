<?php

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
    $title = $_POST['title'] ?? null;
    $content = $_POST['content'] ?? null;
    $selectedDate = $_POST['date'] ?? null;
    $image_url = $_POST['image_url'] ?? null;

    // Validacija unosa
    if (!$title || !$content || !$selectedDate || !$image_url) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Naslov, sadržaj, datum i URL slike su obavezni.']);
        exit;
    }

    try {
        $query = "INSERT INTO news (title, content, created_at, image_url) VALUES (:title, :content, :created_at, :image_url)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':title' => $title,
            ':content' => $content,
            ':created_at' => $selectedDate, // Unos odabranog datuma
            ':image_url' => $image_url
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Vijest je uspješno dodana.',
            'image_url' => $image_url
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Greška pri unosu u bazu.']);
        error_log("PDO Error: " . $e->getMessage());
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Nedozvoljena metoda.']);
}
?>
