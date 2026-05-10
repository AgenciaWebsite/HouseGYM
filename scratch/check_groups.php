<?php
require_once 'app/Core/Database.php';
use App\Core\Database;

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT DISTINCT nombre FROM grupo_muscular");
$groups = $stmt->fetchAll(PDO::FETCH_COLUMN);
print_r($groups);
