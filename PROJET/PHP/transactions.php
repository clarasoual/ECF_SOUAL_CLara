<?php
// Chemin vers le fichier JSON des transactions
define('TRANSACTIONS_FILE', __DIR__ . '/../../transactions.json');

// Initialise le fichier s'il n'existe pas
function initTransactionsFile() {
    if (!file_exists(TRANSACTIONS_FILE)) {
        file_put_contents(TRANSACTIONS_FILE, json_encode([]));
        chmod(TRANSACTIONS_FILE, 0666);    }
}

// Enregistre une transaction
function ajouterTransaction($id_utilisateur, $type, $description, $montant, $solde_apres, $id_trajet = null) {
    initTransactionsFile();

    $transaction = [
        'id'             => 'transaction_' . uniqid(),
        'date'           => date('Y-m-d H:i:s'),
        'id_utilisateur' => (int) $id_utilisateur,
        'type'           => $type,
        'description'    => $description,
        'montant'        => (int) $montant,
        'solde_apres'    => (int) $solde_apres,
        'id_trajet'      => $id_trajet
    ];

    $fp = fopen(TRANSACTIONS_FILE, 'c+');
    if (flock($fp, LOCK_EX)) {
        $contenu = stream_get_contents($fp);
        $transactions = json_decode($contenu, true) ?? [];
        $transactions[] = $transaction;
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($transactions, JSON_PRETTY_PRINT));
        flock($fp, LOCK_UN);
    }
    fclose($fp);
}

// Récupère les transactions d'un utilisateur
function getTransactions($id_utilisateur, $limite = 20, $offset = 0) {
    initTransactionsFile();

    $contenu = file_get_contents(TRANSACTIONS_FILE);
    $transactions = json_decode($contenu, true) ?? [];

    // Filtre par utilisateur
    $filtrees = array_filter($transactions, function($t) use ($id_utilisateur) {
        return (int)$t['id_utilisateur'] === (int)$id_utilisateur;
    });

    // Tri par date décroissante (plus récent en premier)
    usort($filtrees, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });

    // Pagination
    return array_slice(array_values($filtrees), $offset, $limite);
}

// Récupère le nombre de transactions d'un utilisateur
function countTransactions($id_utilisateur) {
    initTransactionsFile();

    $contenu = file_get_contents(TRANSACTIONS_FILE);
    $transactions = json_decode($contenu, true) ?? [];

    return count(array_filter($transactions, function($t) use ($id_utilisateur) {
        return (int)$t['id_utilisateur'] === (int)$id_utilisateur;
    }));
}
