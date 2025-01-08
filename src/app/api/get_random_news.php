 <?php
 header("Access-Control-Allow-Origin: http://localhost:4200");
 header("Access-Control-Allow-Methods: GET, OPTIONS");
 header("Access-Control-Allow-Headers: Content-Type, Authorization");
 header("Access-Control-Allow-Credentials: true");
 header('Content-Type: application/json');


 include 'db_connection.php';


 if ($_SERVER['REQUEST_METHOD'] === 'GET') {
     try {

         $query = "SELECT * FROM news ORDER BY RAND() LIMIT 10";
         $stmt = $pdo->prepare($query);
         $stmt->execute();


         $news = $stmt->fetchAll(PDO::FETCH_ASSOC);


         $response = ['status' => 'success', 'news' => $news];


         if (json_last_error() === JSON_ERROR_NONE) {
             echo json_encode($response);
         } else {

             http_response_code(500);
             echo json_encode(['status' => 'error', 'message' => 'Greška prilikom enkodiranja podataka u JSON format.']);
         }

     } catch (PDOException $e) {

         http_response_code(500); // Internal Server Error
         echo json_encode(['status' => 'error', 'message' => 'Došlo je do greške prilikom učitavanja vijesti.']);
         error_log("PDO Error: " . $e->getMessage());
     }
 } else {

     http_response_code(405);
     echo json_encode(['status' => 'error', 'message' => 'Nedozvoljena metoda.']);
 }
 ?>
