<?php
// ============================================================
// logs.php — Gestion des logs en fichier JSON
// À inclure dans les pages qui ont besoin de logger
// ============================================================

// Chemin vers le fichier JSON (à adapter selon ton arborescence)
// Idéalement EN DEHORS du dossier public (ex: un niveau au-dessus de PROJET/)
define('LOGS_FILE', __DIR__ . '/../../logs.json');

// Nombre maximum de logs à conserver
define('LOGS_MAX', 1000);


// ============================================================
// logAction() — Ajoute un log dans le fichier JSON
// ============================================================
// Paramètres :
//   $action  (string) : ce qui s'est passé, ex: "recherche_trajet"
//   $message (string) : détail lisible, ex: "Bordeaux → Paris"
//   $niveau  (string) : "INFO", "WARNING", "ERROR" (défaut: "INFO")
//   $userId  (int)    : ID de l'utilisateur connecté (défaut: null)
// ============================================================
function logAction(string $action, string $message, string $niveau = 'INFO', ?int $userId = null): void {
    $logFile = LOGS_FILE;

    // Crée le fichier s'il n'existe pas encore
    if (!file_exists($logFile)) {
        file_put_contents($logFile, '[]');
    }

    // Construit l'entrée de log
    $entry = [
        'id'           => uniqid('log_', true),
        'date'         => date('Y-m-d H:i:s'),
        'niveau'       => strtoupper($niveau),
        'action'       => $action,
        'message'      => $message,
        'utilisateur'  => $userId,
        'ip'           => $_SERVER['REMOTE_ADDR'] ?? 'inconnue',
        'page'         => $_SERVER['REQUEST_URI'] ?? 'inconnue',
    ];

    // Ouvre le fichier avec un verrou exclusif pour éviter les conflits
    $handle = fopen($logFile, 'c+');
    if (!$handle) return;

    flock($handle, LOCK_EX); // Verrou en écriture

    $content = stream_get_contents($handle);
    $logs = json_decode($content, true);
    if (!is_array($logs)) $logs = [];

    // Ajoute le nouveau log au début (plus récent en premier)
    array_unshift($logs, $entry);

    // Limite le nombre de logs conservés
    if (count($logs) > LOGS_MAX) {
        $logs = array_slice($logs, 0, LOGS_MAX);
    }

    // Réécrit le fichier
    ftruncate($handle, 0);
    rewind($handle);
    fwrite($handle, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    flock($handle, LOCK_UN); // Libère le verrou
    fclose($handle);
}


// ============================================================
// getLogs() — Lit les logs avec filtres optionnels
// ============================================================
// Paramètres :
//   $niveau  (string|null) : filtrer par niveau ("INFO", "WARNING", "ERROR")
//   $action  (string|null) : filtrer par action (ex: "recherche_trajet")
//   $userId  (int|null)    : filtrer par utilisateur
//   $limite  (int)         : nombre max de résultats (défaut: 100)
//   $offset  (int)         : pour la pagination (défaut: 0)
// Retourne : tableau de logs
// ============================================================
function getLogs(
    ?string $niveau = null,
    ?string $action = null,
    ?int    $userId = null,
    int     $limite = 100,
    int     $offset = 0
): array {
    $logFile = LOGS_FILE;

    if (!file_exists($logFile)) return [];

    $content = file_get_contents($logFile);
    $logs    = json_decode($content, true);
    if (!is_array($logs)) return [];

    // Applique les filtres
    if ($niveau !== null) {
        $logs = array_filter($logs, fn($l) => $l['niveau'] === strtoupper($niveau));
    }
    if ($action !== null) {
        $logs = array_filter($logs, fn($l) => $l['action'] === $action);
    }
    if ($userId !== null) {
        $logs = array_filter($logs, fn($l) => $l['utilisateur'] === $userId);
    }

    // Reindexe après les filtres
    $logs = array_values($logs);

    // Pagination
    return array_slice($logs, $offset, $limite);
}


// ============================================================
// countLogs() — Compte les logs (avec les mêmes filtres)
// ============================================================
function countLogs(?string $niveau = null, ?string $action = null, ?int $userId = null): int {
    return count(getLogs($niveau, $action, $userId, LOGS_MAX, 0));
}


// ============================================================
// clearLogs() — Vide tous les logs (réservé à l'admin)
// ============================================================
function clearLogs(): void {
    file_put_contents(LOGS_FILE, '[]');
}