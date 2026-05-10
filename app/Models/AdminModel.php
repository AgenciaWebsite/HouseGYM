<?php
declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Core\Database;

/**
 * Clase AdminModel
 * 
 * Gestiona todas las operaciones de base de datos relacionadas con el panel de administración,
 * estadísticas del dashboard, y gestión de usuarios.
 */
class AdminModel
{
    private PDO $pdo;

    /**
     * Constructor de AdminModel
     */
    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Obtiene estadísticas generales del sistema para el dashboard.
     *
     * @return array Estadísticas con el total de usuarios, rutinas, máquinas, dietas, etc.
     */
    public function getDashboardStats(): array
    {
        return [
            'usuarios' => $this->scalarCount('SELECT COUNT(*) FROM usuarios'),
            'rutinas_personalizadas' => $this->scalarCount('SELECT COUNT(*) FROM rutina_personalizada'),
            'maquinas' => $this->scalarCount('SELECT COUNT(*) FROM maquinas'),
            'dietas' => $this->scalarCount('SELECT COUNT(id_dieta) FROM rutina_personalizada'),
            'ejercicios' => $this->scalarCount('SELECT COUNT(*) FROM ejercicios'),
            'rutinas_globales' => $this->scalarCount('SELECT COUNT(*) FROM rutina_global'),
        ];
    }

    /**
     * Obtiene los últimos usuarios registrados.
     *
     * @param int $limit Límite de resultados.
     * @return array Lista de usuarios.
     */
    public function getRecentUsers(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_usuario, nombre, cedula, activo, plan_personalizado
             FROM usuarios
             ORDER BY id_usuario DESC
             LIMIT ?'
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtiene todos los usuarios para generar la gráfica del dashboard.
     *
     * @return array Lista de usuarios.
     */
    public function getAllUsersForChart(): array
    {
        return $this->pdo->query(
            'SELECT id_usuario, cedula
             FROM usuarios
             ORDER BY id_usuario ASC'
        )->fetchAll();
    }

    /**
     * Obtiene todos los usuarios con sus detalles (para la gestión de usuarios).
     *
     * @return array Lista de usuarios.
     */
    public function getAllUsers(): array
    {
        return $this->pdo->query(
            'SELECT u.id_usuario, u.nombre, u.cedula, u.contrasena, u.activo, u.plan_personalizado,
                    (SELECT COUNT(*) FROM rutina_personalizada rp WHERE rp.id_usuario = u.id_usuario AND rp.id_dieta IS NOT NULL AND rp.activa = 1) as dieta
             FROM usuarios u
             ORDER BY u.id_usuario DESC'
        )->fetchAll();
    }

    /**
     * Realiza una búsqueda global (usuarios, máquinas, ejercicios).
     *
     * @param string $q Término de búsqueda.
     * @return array Resultados de la búsqueda unificados.
     */
    public function searchGlobal(string $q): array
    {
        $like = '%' . $q . '%';
        $results = [];

        // Buscar usuarios
        $stmt = $this->pdo->prepare(
            "SELECT 'usuario' AS tipo, id_usuario AS id, nombre AS titulo,
                    CONCAT('CC ', cedula, IF(activo = 1, ' · Activo', ' · Inactivo')) AS detalle
             FROM usuarios
             WHERE nombre LIKE ? OR CAST(cedula AS CHAR) LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like, $like]);
        $results = array_merge($results, $stmt->fetchAll());

        // Buscar máquinas
        $stmt = $this->pdo->prepare(
            "SELECT 'maquina' AS tipo, id_maquina AS id, nombre AS titulo,
                    CONCAT('Piso ', piso, ' · Maquina') AS detalle
             FROM maquinas
             WHERE nombre LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like]);
        $results = array_merge($results, $stmt->fetchAll());

        // Buscar ejercicios
        $stmt = $this->pdo->prepare(
            "SELECT 'ejercicio' AS tipo, id_ejercicio AS id, nombre AS titulo,
                    'Ejercicio' AS detalle
             FROM ejercicios
             WHERE nombre LIKE ?
             LIMIT 5"
        );
        $stmt->execute([$like]);
        $results = array_merge($results, $stmt->fetchAll());

        return array_slice($results, 0, 10);
    }

    /**
     * Agrega un nuevo usuario al sistema con sus planes (rutina y dieta).
     *
     * @param string $nombre Nombre del usuario.
     * @param string $cedula Cédula del usuario.
     * @param string $password Contraseña (será hasheada).
     * @param int $plan_personalizado Si tiene plan personalizado (1 o 0).
     * @param int|null $id_dieta El ID de la dieta seleccionada (null si no tiene dieta).
     * @return int El ID del usuario insertado.
     */
    public function addUser(string $nombre, string $cedula, string $password, int $plan_personalizado, ?int $id_dieta, string $genero = 'Hombre'): int
    {
        $this->pdo->beginTransaction();

        try {
            // Insertar usuario
            $stmt = $this->pdo->prepare(
                'INSERT INTO usuarios (nombre, cedula, contrasena, activo, plan_personalizado, genero)
                 VALUES (?, ?, ?, 1, ?, ?)'
            );
            $stmt->execute([$nombre, $cedula, $password, $plan_personalizado, $genero]);
            $userId = (int) $this->pdo->lastInsertId();

            // Insertar rutina si tiene plan o dieta
            if ($plan_personalizado || $id_dieta) {
                $stmt = $this->pdo->prepare(
                    'INSERT INTO rutina_personalizada (id_usuario, nombre, activa, id_dieta)
                     VALUES (?, ?, ?, ?)'
                );
                $stmt->execute([$userId, 'Rutina personalizada', $plan_personalizado, $id_dieta]);
            }

            $this->pdo->commit();
            return $userId;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Actualiza un usuario.
     */
    public function updateUser(int $id, string $nombre, string $cedula, string $password, int $plan_personalizado, int $id_dieta): void
    {
        $this->pdo->beginTransaction();

        try {
            // Actualizar datos básicos del usuario
            if ($password !== '') {
                $stmt = $this->pdo->prepare('UPDATE usuarios SET nombre = ?, cedula = ?, contrasena = ?, plan_personalizado = ? WHERE id_usuario = ?');
                $stmt->execute([$nombre, $cedula, $password, $plan_personalizado, $id]);
            } else {
                $stmt = $this->pdo->prepare('UPDATE usuarios SET nombre = ?, cedula = ?, plan_personalizado = ? WHERE id_usuario = ?');
                $stmt->execute([$nombre, $cedula, $plan_personalizado, $id]);
            }

            // Gestionar rutina/dieta de forma no destructiva
            // Primero verificamos si ya tiene una rutina creada
            $stmt = $this->pdo->prepare('SELECT id_rutina_pers, id_dieta FROM rutina_personalizada WHERE id_usuario = ? LIMIT 1');
            $stmt->execute([$id]);
            $rutinaExistente = $stmt->fetch();

            if ($plan_personalizado || $id_dieta) {
                if (!$rutinaExistente) {
                    // Si no existe, la creamos (comportamiento igual al de agregar usuario)
                    $real_id_dieta = $id_dieta > 0 ? 1 : null; 
                    $stmt = $this->pdo->prepare(
                        'INSERT INTO rutina_personalizada (id_usuario, nombre, activa, id_dieta)
                         VALUES (?, ?, ?, ?)'
                    );
                    $stmt->execute([$id, 'Rutina personalizada', $plan_personalizado, $real_id_dieta]);
                } else {
                    // Si ya existe, solo actualizamos los flags, así no perdemos los ejercicios (detalle)
                    $nuevo_id_dieta = $rutinaExistente['id_dieta'];
                    
                    if ($id_dieta == 0) {
                        $nuevo_id_dieta = null;
                    } elseif ($nuevo_id_dieta === null && $id_dieta > 0) {
                        // Si no tenía dieta y se activó, asignamos la ID 1 por defecto si existe
                        $nuevo_id_dieta = 1; 
                    }

                    $stmt = $this->pdo->prepare(
                        'UPDATE rutina_personalizada SET activa = ?, id_dieta = ? WHERE id_rutina_pers = ?'
                    );
                    $stmt->execute([$plan_personalizado, $nuevo_id_dieta, $rutinaExistente['id_rutina_pers']]);
                }
            } else {
                // Si ambos están desactivados, desactivamos la rutina pero conservamos el registro
                // para no borrar el histórico de ejercicios del usuario accidentalmente.
                if ($rutinaExistente) {
                    $stmt = $this->pdo->prepare('UPDATE rutina_personalizada SET activa = 0, id_dieta = NULL WHERE id_rutina_pers = ?');
                    $stmt->execute([$rutinaExistente['id_rutina_pers']]);
                }
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Elimina un usuario por su ID.
     *
     * @param int $id El ID del usuario.
     * @return bool True si se eliminó correctamente.
     */
    public function deleteUser(int $id): bool
    {
        $this->pdo->beginTransaction();
        try {
            // 1. Eliminar detalles de la rutina personalizada (si existen)
            $stmt = $this->pdo->prepare('DELETE FROM rutina_personalizada_detalle WHERE id_rutina_pers IN (SELECT id_rutina_pers FROM rutina_personalizada WHERE id_usuario = ?)');
            $stmt->execute([$id]);

            // 2. Eliminar la cabecera de la rutina personalizada
            $stmt = $this->pdo->prepare('DELETE FROM rutina_personalizada WHERE id_usuario = ?');
            $stmt->execute([$id]);

            // 3. Eliminar el usuario
            $stmt = $this->pdo->prepare('DELETE FROM usuarios WHERE id_usuario = ?');
            $success = $stmt->execute([$id]);

            $this->pdo->commit();
            return $success;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene el total de un conteo usando una consulta scalar.
     *
     * @param string $sql Consulta SQL que retorna un solo valor.
     * @return int El resultado numérico de la consulta.
     */
    private function scalarCount(string $sql): int
    {
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    /**
     * Obtiene todos los ejercicios del catálogo.
     */
    public function getAllEjercicios(): array
    {
        return $this->pdo->query(
            'SELECT e.id_ejercicio, e.nombre, e.descripcion, e.foto_url AS imagen_url,
                    e.id_grupo, e.id_maquina, g.nombre AS grupo_muscular, m.nombre AS maquina
             FROM ejercicios e
             LEFT JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             LEFT JOIN maquinas m ON m.id_maquina = e.id_maquina
             ORDER BY e.nombre ASC'
        )->fetchAll();
    }

    /**
     * Agrega un nuevo ejercicio.
     */
    public function addEjercicio(string $nombre, ?int $id_grupo, ?int $id_maquina, string $descripcion, ?string $fotoBase64): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO ejercicios (nombre, id_grupo, id_maquina, descripcion, foto_url)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$nombre, $id_grupo, $id_maquina, $descripcion, $fotoBase64]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza un ejercicio existente.
     */
    public function updateEjercicio(int $id, string $nombre, ?int $id_grupo, ?int $id_maquina, string $descripcion, ?string $fotoBase64): void
    {
        if ($fotoBase64 !== null) {
            $stmt = $this->pdo->prepare(
                'UPDATE ejercicios SET nombre = ?, id_grupo = ?, id_maquina = ?, descripcion = ?, foto_url = ? WHERE id_ejercicio = ?'
            );
            $stmt->execute([$nombre, $id_grupo, $id_maquina, $descripcion, $fotoBase64, $id]);
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE ejercicios SET nombre = ?, id_grupo = ?, id_maquina = ?, descripcion = ? WHERE id_ejercicio = ?'
            );
            $stmt->execute([$nombre, $id_grupo, $id_maquina, $descripcion, $id]);
        }
    }

    /**
     * Elimina un ejercicio.
     */
    public function deleteEjercicio(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM ejercicios WHERE id_ejercicio = ?');
        return $stmt->execute([$id]);
    }

    /* ═══════════════════════════════════════════════════════════════
       RUTINA GLOBAL — Métodos por Género / Semana / Día
    ═══════════════════════════════════════════════════════════════ */

    /**
     * Obtiene o crea el ID de la rutina global para un género y semana dados.
     *
     * @param string $genero 'Hombre' o 'Mujer'
     * @param int    $semana 1–4
     * @return int   ID de rutina_global
     */
    private function getOrCreateRutinaGlobalId(string $genero, int $semana): int
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_rutina_global FROM rutina_global
             WHERE genero = ? AND semana = ? LIMIT 1'
        );
        $stmt->execute([$genero, $semana]);
        $id = $stmt->fetchColumn();

        if ($id) {
            return (int) $id;
        }

        // Crear nueva rutina global para este género/semana
        $nombre = "Rutina Global {$genero} — Semana {$semana}";
        $stmt = $this->pdo->prepare(
            'INSERT INTO rutina_global (nombre, genero, semana, activa) VALUES (?, ?, ?, 1)'
        );
        $stmt->execute([$nombre, $genero, $semana]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Obtiene la rutina global de un género y semana, agrupada por días.
     *
     * @param string $genero 'Hombre' o 'Mujer'
     * @param int    $semana 1–4
     * @return array [ ['dia'=>1, 'ejercicios'=>[...]], ... ]
     */
    public function getRutinaGlobal(string $genero, int $semana): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id_rutina_global FROM rutina_global
             WHERE genero = ? AND semana = ? LIMIT 1'
        );
        $stmt->execute([$genero, $semana]);
        $rutinaId = $stmt->fetchColumn();

        if (!$rutinaId) {
            return []; // sin datos aún
        }

        $stmt = $this->pdo->prepare(
            'SELECT rgd.dia, rgd.id_ejercicio, rgd.series, rgd.repeticiones AS reps, rgd.orden,
                    e.nombre, e.foto_url AS imagen_url, g.nombre AS grupo_muscular, m.nombre AS maquina
             FROM rutina_global_detalle rgd
             JOIN ejercicios e ON e.id_ejercicio = rgd.id_ejercicio
             JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             LEFT JOIN maquinas m ON m.id_maquina = e.id_maquina
             WHERE rgd.id_rutina_global = ?
             ORDER BY rgd.dia ASC, rgd.orden ASC, rgd.id ASC'
        );
        $stmt->execute([$rutinaId]);
        $rows = $stmt->fetchAll();

        // Agrupar en formato: { dia: N, ejercicios: [...] }
        $diasMap = [];
        foreach ($rows as $row) {
            $dia = (int) $row['dia'];
            if (!isset($diasMap[$dia])) {
                $diasMap[$dia] = ['dia' => $dia, 'ejercicios' => []];
            }
            $diasMap[$dia]['ejercicios'][] = [
                'id_ejercicio'  => $row['id_ejercicio'],
                'nombre'        => $row['nombre'],
                'imagen_url'    => $row['imagen_url'],
                'grupo_muscular'=> $row['grupo_muscular'],
                'maquina'       => $row['maquina'],
                'reps'          => $row['reps'],
                'series'        => $row['series'],
            ];
        }

        // Ordenar por número de día y devolver como array indexado
        ksort($diasMap);
        return array_values($diasMap);
    }

    /**
     * Guarda/reemplaza la rutina global completa para un género y semana.
     *
     * @param string $genero 'Hombre' o 'Mujer'
     * @param int    $semana 1–4
     * @param array  $dias   [ ['dia'=>N, 'ejercicios'=>[...]], ... ]
     */
    public function saveRutinaGlobal(string $genero, int $semana, array $dias): void
    {
        $this->pdo->beginTransaction();
        try {
            $rutinaId = $this->getOrCreateRutinaGlobalId($genero, $semana);

            // Borrar todos los detalles anteriores de esta rutina
            $stmt = $this->pdo->prepare(
                'DELETE FROM rutina_global_detalle WHERE id_rutina_global = ?'
            );
            $stmt->execute([$rutinaId]);

            // Insertar los nuevos detalles
            if (!empty($dias)) {
                $stmtIns = $this->pdo->prepare(
                    'INSERT INTO rutina_global_detalle
                     (id_rutina_global, dia, orden, id_ejercicio, series, repeticiones)
                     VALUES (?, ?, ?, ?, ?, ?)'
                );
                foreach ($dias as $diaObj) {
                    $diaNum = (int) ($diaObj['dia'] ?? 0);
                    if ($diaNum < 1 || $diaNum > 5) continue;
                    $orden = 0;
                    foreach ($diaObj['ejercicios'] ?? [] as $ej) {
                        $idEj   = (int) $ej['id_ejercicio'];
                        $series = max(1, (int) ($ej['series'] ?? 3));
                        $reps   = max(1, (int) ($ej['reps'] ?? 12));
                        $stmtIns->execute([$rutinaId, $diaNum, $orden, $idEj, $series, $reps]);
                        $orden++;
                    }
                }
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Retorna estadísticas rápidas de cuántas rutinas globales están configuradas.
     *
     * @return array ['hombre'=>int[], 'mujer'=>int[]]
     */
    public function getRutinasGlobalesStats(): array
    {
        $rows = $this->pdo->query(
            "SELECT rg.genero, rg.semana, COUNT(rgd.id) AS total_ejercicios
             FROM rutina_global rg
             LEFT JOIN rutina_global_detalle rgd ON rgd.id_rutina_global = rg.id_rutina_global
             GROUP BY rg.genero, rg.semana
             ORDER BY rg.genero, rg.semana"
        )->fetchAll();

        $stats = ['Hombre' => [], 'Mujer' => []];
        foreach ($rows as $r) {
            $stats[$r['genero']][(int)$r['semana']] = (int)$r['total_ejercicios'];
        }
        return $stats;
    }

    /**
     * Obtiene la rutina personalizada de un usuario agrupada por días.
     */
    public function getRutinaPersonalizada(int $userId): array
    {
        // Obtener el ID de la rutina personalizada activa para el usuario
        $stmt = $this->pdo->prepare(
            'SELECT id_rutina_pers FROM rutina_personalizada
             WHERE id_usuario = ? AND activa = 1
             ORDER BY id_rutina_pers DESC LIMIT 1'
        );
        $stmt->execute([$userId]);
        $rutinaId = $stmt->fetchColumn();

        if (!$rutinaId) {
            return [];
        }

        // Obtener los detalles agrupados por día
        $stmt = $this->pdo->prepare(
            'SELECT d.dia, d.id_ejercicio, d.series, d.repeticiones AS reps,
                    e.nombre, e.foto_url AS imagen_url, g.nombre AS grupo_muscular
             FROM rutina_personalizada_detalle d
             JOIN ejercicios e ON e.id_ejercicio = d.id_ejercicio
             JOIN grupo_muscular g ON g.id_grupo = e.id_grupo
             WHERE d.id_rutina_pers = ?
             ORDER BY d.dia ASC, d.id ASC'
        );
        $stmt->execute([$rutinaId]);
        $rows = $stmt->fetchAll();

        $diasMap = [];
        foreach ($rows as $row) {
            $diaIndex = (int)$row['dia'] - 1;
            if (!isset($diasMap[$diaIndex])) {
                $diasMap[$diaIndex] = ['ejercicios' => []];
            }
            $diasMap[$diaIndex]['ejercicios'][] = [
                'id_ejercicio'   => (int)$row['id_ejercicio'],
                'nombre'         => $row['nombre'],
                'imagen_url'     => $row['imagen_url'],
                'grupo_muscular' => $row['grupo_muscular'],
                'reps'           => (int)$row['reps'],
                'series'         => (int)$row['series'],
            ];
        }

        $maxDia = empty($diasMap) ? -1 : max(array_keys($diasMap));
        $result = [];
        for ($i = 0; $i <= $maxDia; $i++) {
            $result[] = $diasMap[$i] ?? ['ejercicios' => []];
        }

        return $result;
    }

    /**
     * Guarda la rutina personalizada de un usuario.
     */
    public function saveRutinaPersonalizada(int $userId, array $dias): void
    {
        $this->pdo->beginTransaction();
        try {
            // Verificar si el usuario ya tiene un registro en rutina_personalizada
            $stmt = $this->pdo->prepare('SELECT id_rutina_pers FROM rutina_personalizada WHERE id_usuario = ? LIMIT 1');
            $stmt->execute([$userId]);
            $rutinaId = $stmt->fetchColumn();

            if (!$rutinaId) {
                // Crear el registro de rutina
                $stmt = $this->pdo->prepare(
                    'INSERT INTO rutina_personalizada (id_usuario, nombre, activa) VALUES (?, ?, 1)'
                );
                $stmt->execute([$userId, 'Rutina personalizada']);
                $rutinaId = $this->pdo->lastInsertId();
                
                // Actualizar bandera en usuario
                $stmt = $this->pdo->prepare('UPDATE usuarios SET plan_personalizado = 1 WHERE id_usuario = ?');
                $stmt->execute([$userId]);
            }

            // Eliminar detalles anteriores
            $stmt = $this->pdo->prepare('DELETE FROM rutina_personalizada_detalle WHERE id_rutina_pers = ?');
            $stmt->execute([$rutinaId]);

            // Insertar nuevos detalles
            if (!empty($dias)) {
                $stmtInsert = $this->pdo->prepare(
                    'INSERT INTO rutina_personalizada_detalle (id_rutina_pers, dia, id_ejercicio, series, repeticiones)
                     VALUES (?, ?, ?, ?, ?)'
                );

                foreach ($dias as $index => $dia) {
                    $diaNum = $index + 1; // 1-indexed for database
                    if (!empty($dia['ejercicios'])) {
                        foreach ($dia['ejercicios'] as $ej) {
                            $id_ejercicio = (int) $ej['id_ejercicio'];
                            $series = (int) ($ej['series'] ?? 3);
                            $reps = (int) ($ej['reps'] ?? 12);
                            $stmtInsert->execute([$rutinaId, $diaNum, $id_ejercicio, $series, $reps]);
                        }
                    }
                }
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene todas las máquinas.
     */
    public function getAllMachines(): array
    {
        return $this->pdo->query(
            'SELECT id_maquina, nombre, descripcion, foto_url as foto, ubicacion, categoria
             FROM maquinas
             ORDER BY id_maquina DESC'
        )->fetchAll();
    }

    /**
     * Agrega una nueva máquina.
     */
    public function addMachine(string $nombre, string $categoria, string $descripcion, string $ubicacion, ?string $fotoBase64): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO maquinas (nombre, categoria, descripcion, ubicacion, foto_url)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$nombre, $categoria, $descripcion, $ubicacion, $fotoBase64]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una máquina existente.
     */
    public function updateMachine(int $id, string $nombre, string $categoria, string $descripcion, string $ubicacion, ?string $fotoBase64): void
    {
        if ($fotoBase64 !== null) {
            $stmt = $this->pdo->prepare(
                'UPDATE maquinas SET nombre = ?, categoria = ?, descripcion = ?, ubicacion = ?, foto_url = ? WHERE id_maquina = ?'
            );
            $stmt->execute([$nombre, $categoria, $descripcion, $ubicacion, $fotoBase64, $id]);
        } else {
            $stmt = $this->pdo->prepare(
                'UPDATE maquinas SET nombre = ?, categoria = ?, descripcion = ?, ubicacion = ? WHERE id_maquina = ?'
            );
            $stmt->execute([$nombre, $categoria, $descripcion, $ubicacion, $id]);
        }
    }

    /**
     * Elimina una máquina.
     */
    public function deleteMachine(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM maquinas WHERE id_maquina = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Obtiene todas las dietas
     */
    public function getAllDietas(): array
    {
        return $this->pdo->query('SELECT * FROM dietas ORDER BY id_dieta DESC')->fetchAll();
    }

    /**
     * Agrega una nueva dieta
     */
    public function addDieta(string $tipo, ?string $descripcion): int
    {
        $stmt = $this->pdo->prepare('INSERT INTO dietas (tipo, descripcion) VALUES (?, ?)');
        $stmt->execute([$tipo, $descripcion]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualiza una dieta existente
     */
    public function updateDieta(int $id, string $tipo, ?string $descripcion): void
    {
        $stmt = $this->pdo->prepare('UPDATE dietas SET tipo = ?, descripcion = ? WHERE id_dieta = ?');
        $stmt->execute([$tipo, $descripcion, $id]);
    }

    /**
     * Elimina una dieta
     */
    public function deleteDieta(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM dietas WHERE id_dieta = ?');
        $stmt->execute([$id]);
    }

    /**
     * Obtiene la dieta asignada a un usuario.
     */
    public function getDietaUsuario(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT rp.id_dieta, d.tipo AS nombre, d.descripcion 
             FROM rutina_personalizada rp
             LEFT JOIN dietas d ON rp.id_dieta = d.id_dieta
             WHERE rp.id_usuario = ? AND rp.activa = 1
             LIMIT 1'
        );
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Guarda o actualiza la dieta asignada a un usuario.
     */
    public function saveDietaUsuario(int $userId, ?int $idDieta): void
    {
        $this->pdo->beginTransaction();
        try {
            // Verificar si existe una rutina_personalizada activa
            $stmt = $this->pdo->prepare('SELECT id_rutina_pers FROM rutina_personalizada WHERE id_usuario = ? AND activa = 1 LIMIT 1');
            $stmt->execute([$userId]);
            $rutinaId = $stmt->fetchColumn();

            if ($rutinaId) {
                // Actualizar la existente
                $stmtUpdate = $this->pdo->prepare('UPDATE rutina_personalizada SET id_dieta = ? WHERE id_rutina_pers = ?');
                $stmtUpdate->execute([$idDieta, $rutinaId]);
            } else {
                // Crear una nueva, pero el usuario debe tener plan_personalizado
                $stmtInsert = $this->pdo->prepare('INSERT INTO rutina_personalizada (id_usuario, nombre, activa, id_dieta) VALUES (?, "Plan Personalizado", 1, ?)');
                $stmtInsert->execute([$userId, $idDieta]);
                
                // También asegurar que el usuario tenga plan_personalizado = 1
                $stmtUser = $this->pdo->prepare('UPDATE usuarios SET plan_personalizado = 1 WHERE id_usuario = ?');
                $stmtUser->execute([$userId]);
            }
            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
