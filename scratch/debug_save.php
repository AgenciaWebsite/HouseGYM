<?php
require_once 'app/Core/Database.php';
require_once 'app/Models/AdminModel.php';
use App\Models\AdminModel;

try {
    $model = new AdminModel();
    $dias = [
        ['dia' => 1, 'ejercicios' => [['id_ejercicio' => 1, 'reps' => 10, 'series' => 3]]]
    ];
    $model->saveRutinaGlobal('Hombre', 1, $dias);
    echo "OK";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
