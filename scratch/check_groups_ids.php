<?php
require_once 'app/Core/Database.php';
use App\Core\Database;

$pdo = Database::getConnection();
$stmt = $pdo->query("SELECT id_grupo, nombre FROM grupo_muscular");
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($groups as $g) {
    echo "ID: {$g['id_grupo']} - Name: {$g['nombre']}\n";
}
