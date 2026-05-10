<?php
require_once 'app/Core/Database.php';
use App\Core\Database;

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT e.nombre, g.nombre as grupo FROM ejercicios e LEFT JOIN grupo_muscular g ON e.id_grupo = g.id_grupo");
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
print_r($results);
