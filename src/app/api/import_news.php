<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Preuzimanje podataka iz POST i FILES
    $title = htmlspecialchars(trim($_POST['title'] ?? ''));
    $content = htmlspecialchars(trim($_POST['content'] ?? ''));
    $date = htmlspecialchars(trim($_POST['date'] ?? ''));
    $image_url = '';

    // Obrada slike ako postoji
    if (!empty($_FILES['image']['tmp_name'])) {
        $uploadDir = 'uploads/';
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $uploadDir . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
            $image_url = $targetFilePath;
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Greška prilikom učitavanja slike.']);
            exit;
        }
    }

    // Validacija obaveznih polja
    if (empty($title) || empty($content) || empty($date)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Naslov, sadržaj i datum su obavezni.']);
        exit;
    }

    // SQL upit za unos vijesti
    try {
        $query = "INSERT INTO news (title, content, image_url, created_at) VALUES (:title, :content, :image_url, :created_at)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $title,
            'content' => $content,
            'image_url' => $image_url,
            'created_at' => $date,
        ]);

        http_response_code(201);
        echo json_encode(['status' => 'success', 'message' => 'Vijest je uspešno unesena.', 'image_url' => $image_url]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Došlo je do greške prilikom unosa vijesti.']);
        error_log($e->getMessage());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $date = $_GET['date'] ?? '';

    if ($date) {
        $query = "SELECT * FROM news WHERE DATE(created_at) = :date";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['date' => $date]);
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($news);
    } else {
        $query = "SELECT * FROM news ORDER BY RAND() LIMIT 10";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($news);
    }
}
?>
