<?php
/**
 * alter_db.php — Script seguro para actualizar el schema de la BD HouseGYM.
 * Aplica los cambios necesarios para soportar Rutina Global por Género, Semana y Día.
 * 
 * Ejecutar UNA VEZ desde el navegador: http://localhost/HouseGYM/alter_db.php
 */

require_once __DIR__ . '/database/conexion.php';

$pdo = db();
$results = [];

/**
 * Ejecuta un ALTER TABLE solo si la columna/índice aún no existe.
 */
function safeAlter(PDO $pdo, string $description, string $checkSql, string $alterSql, array &$results): void {
    try {
        $exists = $pdo->query($checkSql)->fetchColumn();
        if ($exists) {
            $results[] = ['status' => 'skip', 'msg' => "⏭ OMITIDO: $description (ya existe)"];
            return;
        }
        $pdo->exec($alterSql);
        $results[] = ['status' => 'ok', 'msg' => "✅ OK: $description"];
    } catch (Throwable $e) {
        $results[] = ['status' => 'error', 'msg' => "❌ ERROR en '$description': " . $e->getMessage()];
    }
}

function safeExec(PDO $pdo, string $description, string $sql, array &$results): void {
    try {
        $pdo->exec($sql);
        $results[] = ['status' => 'ok', 'msg' => "✅ OK: $description"];
    } catch (Throwable $e) {
        // Ignorar errores "ya existe" o "no existe" comunes en DROP/ADD INDEX
        if (strpos($e->getMessage(), 'Duplicate') !== false || strpos($e->getMessage(), "Can't DROP") !== false || strpos($e->getMessage(), "check that column/key exists") !== false) {
            $results[] = ['status' => 'skip', 'msg' => "⏭ OMITIDO: $description (ya en estado correcto)"];
        } else {
            $results[] = ['status' => 'error', 'msg' => "❌ ERROR en '$description': " . $e->getMessage()];
        }
    }
}

// ─────────────────────────────────────────────
// 1. rutina_global: agregar columna 'genero'
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "rutina_global.genero ENUM('Hombre','Mujer')",
    "SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'rutina_global' AND COLUMN_NAME = 'genero'",
    "ALTER TABLE rutina_global
     ADD COLUMN genero ENUM('Hombre','Mujer') NOT NULL DEFAULT 'Hombre' AFTER nombre",
    $results
);

// ─────────────────────────────────────────────
// 2. rutina_global: agregar columna 'semana'
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "rutina_global.semana TINYINT (1-4)",
    "SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'rutina_global' AND COLUMN_NAME = 'semana'",
    "ALTER TABLE rutina_global
     ADD COLUMN semana TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER genero",
    $results
);

// ─────────────────────────────────────────────
// 3. rutina_global: índice único (genero, semana)
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "rutina_global índice único (genero, semana)",
    "SELECT COUNT(*) FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'rutina_global' AND INDEX_NAME = 'uq_rg_genero_semana'",
    "ALTER TABLE rutina_global
     ADD UNIQUE KEY uq_rg_genero_semana (genero, semana)",
    $results
);

// ─────────────────────────────────────────────
// 4. rutina_global_detalle: agregar columna 'dia'
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "rutina_global_detalle.dia TINYINT (1-5)",
    "SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'rutina_global_detalle' AND COLUMN_NAME = 'dia'",
    "ALTER TABLE rutina_global_detalle
     ADD COLUMN dia TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER id_rutina_global",
    $results
);

// ─────────────────────────────────────────────
// 5. rutina_global_detalle: agregar columna 'orden'
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "rutina_global_detalle.orden TINYINT UNSIGNED",
    "SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'rutina_global_detalle' AND COLUMN_NAME = 'orden'",
    "ALTER TABLE rutina_global_detalle
     ADD COLUMN orden TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER dia",
    $results
);

// ─────────────────────────────────────────────
// 6. rutina_global_detalle: eliminar unique viejo (id_rutina_global, id_ejercicio)
// ─────────────────────────────────────────────
safeExec(
    $pdo,
    "Eliminar índice único antiguo uq_rg_ejercicio",
    "ALTER TABLE rutina_global_detalle DROP INDEX uq_rg_ejercicio",
    $results
);

// ─────────────────────────────────────────────
// 7. rutina_global_detalle: nuevo unique (id_rutina_global, dia, id_ejercicio)
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "rutina_global_detalle índice único (id_rutina_global, dia, id_ejercicio)",
    "SELECT COUNT(*) FROM information_schema.STATISTICS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'rutina_global_detalle' AND INDEX_NAME = 'uq_rg_dia_ejercicio'",
    "ALTER TABLE rutina_global_detalle
     ADD UNIQUE KEY uq_rg_dia_ejercicio (id_rutina_global, dia, id_ejercicio)",
    $results
);

// ─────────────────────────────────────────────
// 8. ejercicios: columna imagen_url (alias de foto_url para el frontend)
//    El modelo usa 'imagen_url' pero la tabla tiene 'foto_url'
//    → Agregar columna virtual / alias si no existe
// ─────────────────────────────────────────────
safeAlter(
    $pdo,
    "ejercicios.imagen_url columna real (renombrar foto_url)",
    "SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ejercicios' AND COLUMN_NAME = 'imagen_url'",
    "ALTER TABLE ejercicios
     ADD COLUMN imagen_url VARCHAR(255) GENERATED ALWAYS AS (foto_url) VIRTUAL",
    $results
);

// ─────────────────────────────────────────────
// 9. ejercicios: columna grupo_muscular (alias JOIN)
//    El modelo hace SELECT sin JOIN pero pide grupo_muscular
//    → Agregar columna generada como placeholder (se llenará desde la vista)
// ─────────────────────────────────────────────
// Verificar si la query del modelo puede usar la vista v_ejercicios en su lugar.
// Por ahora agregar columna virtual de grupo_muscular desde id_grupo no es posible sin subquery.
// Alternativa: la query en el model usa una subconsulta o JOIN.
// → Resolvemos esto en el model directamente.

$results[] = ['status' => 'info', 'msg' => "ℹ️  INFO: grupo_muscular en ejercicios se resuelve vía JOIN en el modelo (no requiere ALTER)"];

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>HouseGYM — Migración BD</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { background: #0e0e0e; color: #e0e0e0; font-family: 'Segoe UI', sans-serif; padding: 40px; }
  h1 { font-size: 22px; font-weight: 700; margin-bottom: 6px; color: #fff; }
  p { font-size: 13px; color: #666; margin-bottom: 28px; }
  .result { padding: 11px 16px; border-radius: 8px; margin-bottom: 8px; font-size: 13.5px; font-family: monospace; }
  .ok    { background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.25); color: #4ade80; }
  .skip  { background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.2); color: #fbbf24; }
  .error { background: rgba(229,26,44,0.1); border: 1px solid rgba(229,26,44,0.3); color: #f87171; }
  .info  { background: rgba(99,102,241,0.1); border: 1px solid rgba(99,102,241,0.25); color: #a5b4fc; }
  .footer { margin-top: 28px; font-size: 12px; color: #444; }
  .footer a { color: #e51a2c; text-decoration: none; }
</style>
</head>
<body>
  <h1>🏋 HouseGYM — Migración de Base de Datos</h1>
  <p>Aplicando cambios de schema para Rutina Global por Género / Semana / Día</p>

  <?php foreach ($results as $r): ?>
    <div class="result <?= htmlspecialchars($r['status']) ?>">
      <?= htmlspecialchars($r['msg']) ?>
    </div>
  <?php endforeach; ?>

  <div class="footer">
    Migración completada · <a href="index.php?route=admin_rutina_global">Ir a Rutina Global →</a>
  </div>
</body>
</html>
