<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Crée et configure une instance PHPMailer avec Mailtrap
 */
function getMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth   = true;
    $mail->Username   = '8ac67b99202455';
    $mail->Password   = 'e8ba00db449506';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 2525;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('noreply@ecoride.fr', 'EcoRide');

    return $mail;
}

/**
 * US10 — Mail envoyé aux passagers quand le chauffeur annule un trajet
 */
function envoyerMailAnnulation(array $passager, array $trajet): bool {
    try {
        $mail = getMailer();
        $mail->addAddress($passager['email'], $passager['prenom'] . ' ' . $passager['nom']);
        $mail->Subject = '🚗 Votre trajet EcoRide a été annulé';
        $mail->isHTML(true);
        $mail->Body = "
            <h2>Bonjour {$passager['prenom']},</h2>
            <p>Nous vous informons que le trajet auquel vous étiez inscrit(e) a été <strong>annulé par le conducteur</strong>.</p>
            <ul>
                <li><strong>Trajet :</strong> {$trajet['depart']} → {$trajet['arrivee']}</li>
                <li><strong>Date :</strong> {$trajet['date_depart']}</li>
                <li><strong>Heure de départ :</strong> {$trajet['heure_depart']}</li>
            </ul>
            <p>Vos crédits ont été <strong>remboursés</strong> automatiquement.</p>
            <p>Vous pouvez rechercher un autre trajet sur <a href='http://localhost/eco_ride/PROJET/UTILISATEUR/USR-index.php'>EcoRide</a>.</p>
            <br>
            <p>L'équipe EcoRide 🌿</p>
        ";
        $mail->AltBody = "Bonjour {$passager['prenom']}, votre trajet {$trajet['depart']} → {$trajet['arrivee']} du {$trajet['date_depart']} a été annulé. Vos crédits ont été remboursés.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erreur mail annulation : " . $e->getMessage());
        return false;
    }
}

/**
 * US11 — Mail envoyé aux passagers quand le chauffeur termine le trajet
 */
function envoyerMailFinTrajet(array $passager, array $trajet): bool {
    try {
        $mail = getMailer();
        $mail->addAddress($passager['email'], $passager['prenom'] . ' ' . $passager['nom']);
        $mail->Subject = '✅ Votre trajet EcoRide est terminé — donnez votre avis !';
        $mail->isHTML(true);
        $mail->Body = "
            <h2>Bonjour {$passager['prenom']},</h2>
            <p>Votre trajet est <strong>terminé</strong> ! Nous espérons que tout s'est bien passé.</p>
            <ul>
                <li><strong>Trajet :</strong> {$trajet['depart']} → {$trajet['arrivee']}</li>
                <li><strong>Date :</strong> {$trajet['date_depart']}</li>
            </ul>
            <p>Rendez-vous sur votre espace pour :</p>
            <ul>
                <li>✅ Valider que le trajet s'est bien passé (les crédits du conducteur seront mis à jour)</li>
                <li>⭐ Laisser un avis et une note au conducteur</li>
                <li>🚨 Signaler un problème si nécessaire</li>
            </ul>
            <p><a href='http://localhost/eco_ride/PROJET/UTILISATEUR/USR-mes-trajets.php'>Accéder à mon espace</a></p>
            <br>
            <p>L'équipe EcoRide 🌿</p>
        ";
        $mail->AltBody = "Bonjour {$passager['prenom']}, votre trajet {$trajet['depart']} → {$trajet['arrivee']} est terminé. Connectez-vous pour valider le trajet et laisser un avis.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erreur mail fin trajet : " . $e->getMessage());
        return false;
    }
}

/**
 * Mail envoyé aux passagers quand le chauffeur modifie un trajet
 */
function envoyerMailModification(array $passager, array $trajet): bool {
    try {
        $mail = getMailer();
        $mail->addAddress($passager['email'], $passager['prenom'] . ' ' . $passager['nom']);
        $mail->Subject = '✏️ Votre trajet EcoRide a été modifié';
        $mail->isHTML(true);
        $mail->Body = "
            <h2>Bonjour {$passager['prenom']},</h2>
            <p>Le conducteur a <strong>modifié</strong> un trajet auquel vous êtes inscrit(e).</p>
            <ul>
                <li><strong>Trajet :</strong> {$trajet['depart']} → {$trajet['arrivee']}</li>
                <li><strong>Nouvelle date :</strong> {$trajet['date_depart']}</li>
                <li><strong>Nouvelle heure de départ :</strong> " . substr($trajet['heure_depart'], 0, 5) . "</li>
            </ul>
            <p>Consultez les détails mis à jour sur votre espace :</p>
            <p><a href='http://localhost/eco_ride/PROJET/UTILISATEUR/USR-mes-trajets.php'>Voir mes trajets</a></p>
            <br>
            <p>L'équipe EcoRide 🌿</p>
        ";
        $mail->AltBody = "Bonjour {$passager['prenom']}, le trajet {$trajet['depart']} → {$trajet['arrivee']} a été modifié. Connectez-vous pour voir les nouveaux détails.";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erreur mail modification : " . $e->getMessage());
        return false;
    }
}
