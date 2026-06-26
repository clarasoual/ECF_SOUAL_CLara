<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page introuvable - Eco Ride</title>
    <link rel="stylesheet" href="/eco_ride/PROJET/CSS/style_global.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .error-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 60vh;
            text-align: center;
            padding: 2rem;
            gap: 1rem;
        }
        .error-code {
            font-family: 'Montserrat', sans-serif;
            font-size: 6rem;
            font-weight: 700;
            color: var(--vert-doux);
            line-height: 1;
            margin: 0;
        }
        .error-emoji { font-size: 3rem; }
        .error-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            color: var(--texte);
            margin: 0;
        }
        .error-desc {
            font-family: 'Quicksand', sans-serif;
            font-size: 1rem;
            color: var(--gris-doux);
            max-width: 400px;
            line-height: 1.7;
            margin: 0;
        }
        .error-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 0.5rem;
        }
        .btn-home {
            background-color: var(--vert-doux);
            color: black;
            font-family: 'Quicksand', sans-serif;
            font-weight: 700;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }
        .btn-home:hover { background-color: #8ec9a4; text-decoration: none; }
        .btn-back {
            background: transparent;
            color: var(--gris-doux);
            font-family: 'Quicksand', sans-serif;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: 1px solid rgba(224,224,224,0.3);
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-back:hover { border-color: var(--gris-doux); color: var(--texte); text-decoration: none; }
    </style>
</head>
<body>

<?php
if (file_exists(__DIR__ . '/COMPONENTS/COMP-header.php')) {
    include(__DIR__ . '/COMPONENTS/COMP-header.php');
}
?>

<div class="error-page">
    <span class="error-emoji">🗺️</span>
    <h1 class="error-code">404</h1>
    <h2 class="error-title">Page introuvable</h2>
    <p class="error-desc">
        Cette page n'existe pas ou a été déplacée. Vérifiez l'adresse ou retournez à l'accueil.
    </p>
    <div class="error-actions">
        <a href="/eco_ride/PROJET/UTILISATEUR/USR-index.php" class="btn-home">🏠 Retour à l'accueil</a>
        <a href="javascript:history.back()" class="btn-back">← Page précédente</a>
    </div>
</div>

<?php
if (file_exists(__DIR__ . '/COMPONENTS/COMP-footer.php')) {
    include(__DIR__ . '/COMPONENTS/COMP-footer.php');
}
?>
</body>
</html>
