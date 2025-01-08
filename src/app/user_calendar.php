<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Korisnički Kalendar</title>
</head>
<body>
    <h1>Korisnički Kalendar</h1>
    <p>Dobrodošli na korisnički kalendar!</p>
    <a href="logout.php">Odjava</a>
</body>
</html>
