<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['date'])) {
    $date = $_GET['date'];

    try {
        $query = "SELECT * FROM news WHERE DATE(created_at) = :date";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':date' => $date]);
        $news = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['status' => 'success', 'news' => $news]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Došlo je do greške prilikom učitavanja vijesti.']);
        error_log($e->getMessage());
    }
}
?>
