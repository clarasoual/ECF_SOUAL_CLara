-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 27 avr. 2026 à 14:04
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eco_ride`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `prenom`, `email`, `password`) VALUES
(1, 'Clara', 'clara@admin-eco.com', '$2y$10$.pP8pnF6FVJ27.6aVOSoIO2M1kSGByHymIcUcWKfRusWB8dwmosJC'),
(2, 'Léon', 'leon@admin-eco.com', '$2y$10$.pP8pnF6FVJ27.6aVOSoIO2M1kSGByHymIcUcWKfRusWB8dwmosJC');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `id_trajet` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL,
  `id_destinataire` int(11) NOT NULL,
  `note` decimal(2,1) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `statut` enum('en_attente','valide','refuse') NOT NULL DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `id_trajet`, `id_auteur`, `id_destinataire`, `note`, `commentaire`, `date_creation`, `statut`) VALUES
(1, 2, 2, 1, 4.0, 'Trajet confortable, Nino est très respectueux et à l\'écoute de ses passagers. Petit bémol, léger retard à l\'arrivée.', '2026-01-27 10:00:00', 'valide'),
(2, 2, 3, 1, 5.0, 'Nino est toujours ponctuel et très sympa, je recommande !', '2026-01-27 11:00:00', 'valide'),
(3, 2, 1, 2, 5.0, 'Finn a été un passager agréable.', '2026-01-27 12:00:00', 'valide'),
(4, 2, 1, 3, 5.0, 'Antoine a été un passager agréable.', '2026-01-27 12:10:00', 'valide'),
(5, 3, 5, 2, 5.0, 'Super trajet, Finn est ponctuel et agréable.', '2026-02-08 10:00:00', 'valide'),
(6, 3, 4, 2, 4.0, 'Finn conduit bien et le trajet est confortable.', '2026-02-08 10:30:00', 'valide'),
(7, 3, 1, 2, 5.0, 'Tout parfait, trajet agréable.', '2026-02-08 11:00:00', 'valide'),
(8, 3, 2, 5, 5.0, 'Théo est un passager sympa.', '2026-02-08 12:00:00', 'valide'),
(9, 3, 2, 1, 5.0, 'Nino est un passager agréable.', '2026-02-08 12:10:00', 'valide'),
(10, 3, 2, 4, 4.0, 'Nina est un passager correct.', '2026-02-08 12:20:00', 'valide'),
(11, 4, 4, 3, 3.5, 'Je ne me suis pas sentie à l\'aise.', '2026-03-16 10:00:00', 'en_attente'),
(12, 4, 3, 4, 5.0, 'Nina a été une passagère agréable.', '2026-03-16 11:00:00', 'en_attente'),
(14, 8, 2, 19, 5.0, 'Super trajet, conducteur très sympa et ponctuel !', '2026-04-24 09:54:11', 'valide'),
(15, 13, 3, 19, 4.0, 'Très bon voyage, je recommande !', '2026-04-24 09:54:11', 'valide');

-- --------------------------------------------------------

--
-- Structure de la table `credits`
--

CREATE TABLE `credits` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `solde` int(11) NOT NULL DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `credits`
--

INSERT INTO `credits` (`id`, `id_utilisateur`, `solde`) VALUES
(3, 19, 7),
(4, 48, 20);

-- --------------------------------------------------------

--
-- Structure de la table `employes`
--

CREATE TABLE `employes` (
  `id` int(11) NOT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL DEFAULT '',
  `service` varchar(100) DEFAULT NULL,
`date_embauche` date NOT NULL DEFAULT (CURRENT_DATE),  `suspendu` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `employes`
--

INSERT INTO `employes` (`id`, `prenom`, `email`, `mot_de_passe`, `service`, `date_embauche`, `suspendu`) VALUES
(2, 'Blathazar', 'balthazar@emp-eco.com', '$2y$10$EzkzVK9PvO0o0KV8GCWvPOax86VdxvYcEgQYHZd2Ms1BNXhJfndYW', 'maintenance', '2025-07-15', 0),
(3, 'Luna', 'luna@emp-eco.com', '$2y$10$EzkzVK9PvO0o0KV8GCWvPOax86VdxvYcEgQYHZd2Ms1BNXhJfndYW', 'Administration', '2025-08-01', 0),
(6, 'nini', 'nini@example.com', '$2y$10$5AkBhqqCrdMVPZVw0fwraexjmZUJbr5V8P2ZWixLZoNkVPGk5Spay', 'maintenance', '2026-04-14', 0);

-- --------------------------------------------------------

--
-- Structure de la table `signalements`
--

CREATE TABLE `signalements` (
  `id` int(11) NOT NULL,
  `id_trajet` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `motif` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp(),
  `statut` enum('en_cours','resolu_credits_verses','resolu_credits_bloques') DEFAULT 'en_cours',
  `note_employe` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `signalements`
--

INSERT INTO `signalements` (`id`, `id_trajet`, `id_utilisateur`, `motif`, `date_creation`, `statut`, `note_employe`) VALUES
(1, 4, 4, 'Antoine roulait trop vite', '2026-03-16 10:00:00', 'en_cours', NULL),
(2, 42, 19, 'conduit mal', '2026-04-23 09:42:59', 'en_cours', '30 avril : mail envoyé au passager');

-- --------------------------------------------------------

--
-- Structure de la table `trajets`
--

CREATE TABLE `trajets` (
  `id` int(11) NOT NULL,
  `id_conducteur` int(11) NOT NULL,
  `depart` varchar(255) NOT NULL,
  `arrivee` varchar(255) NOT NULL,
  `etapes` text DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `date_depart` date NOT NULL,
  `heure_depart` time NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `vehicule_id` int(11) DEFAULT NULL,
  `places_disponibles` int(11) DEFAULT 1,
  `prix` int(11) NOT NULL DEFAULT 2,
  `statut` enum('publie','complet','annule','termine','en_cours') DEFAULT 'publie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajets`
--

INSERT INTO `trajets` (`id`, `id_conducteur`, `depart`, `arrivee`, `etapes`, `commentaire`, `date_depart`, `heure_depart`, `heure_arrivee`, `vehicule_id`, `places_disponibles`, `prix`, `statut`) VALUES
(1, 6, 'Mussidan', 'Perigueux', NULL, NULL, '2026-01-02', '15:00:00', NULL, 4, 1, 2, 'termine'),
(2, 1, 'Perigueux', 'Limoges', NULL, NULL, '2026-01-26', '08:00:00', NULL, 1, 0, 2, 'termine'),
(3, 2, 'Saint Pierre de Chignac', 'Bordeaux', NULL, NULL, '2026-02-07', '10:00:00', NULL, 2, 0, 2, 'termine'),
(4, 3, 'Béziers', 'Bordeaux', NULL, NULL, '2026-03-15', '14:00:00', NULL, 3, 0, 2, 'termine'),
(5, 1, 'Bordeaux', 'Bayonne', NULL, NULL, '2026-10-25', '08:30:00', NULL, 1, 2, 2, 'publie'),
(6, 1, 'Bayonne', 'Dax', NULL, NULL, '2026-10-30', '17:45:00', NULL, 1, 3, 2, 'publie'),
(7, 2, 'Toulouse', 'Auch', NULL, NULL, '2026-09-03', '10:00:00', NULL, 2, 3, 2, 'publie'),
(8, 3, 'Agen', 'Pau', NULL, NULL, '2026-09-26', '14:15:00', '17:00:00', 3, 2, 2, 'publie'),
(13, 19, 'Bordeaux', 'Biarritz', NULL, '', '2026-03-12', '10:00:00', NULL, 15, 2, 2, 'termine'),
(14, 19, 'Bergerac', 'Mussidan', NULL, '', '2026-03-12', '10:00:00', NULL, 15, 1, 2, 'termine'),
(39, 19, 'mussidan', 'montpon', '[\"saint martial\"]', '', '2026-07-12', '10:00:00', NULL, 15, 3, 2, 'publie'),
(42, 1, 'begles', 'gradignan', NULL, '', '2026-04-30', '15:00:00', NULL, 15, 1, 3, 'termine'),
(44, 19, 'Agen', 'Niort', '[\"Bergerac\"]', '', '2026-06-22', '11:00:00', '15:00:00', 15, 3, 2, 'publie'),
(45, 19, 'Mérignac', 'Montpon', NULL, 'Départ Carrefour Mérignac soleil, arrivée gare de Montpon', '2026-08-12', '10:00:00', '11:00:00', 15, 3, 5, 'publie');

-- --------------------------------------------------------

--
-- Structure de la table `trajets_passagers`
--

CREATE TABLE `trajets_passagers` (
  `id` int(11) NOT NULL,
  `id_trajet` int(11) NOT NULL,
  `id_passager` int(11) NOT NULL,
  `note_passager` decimal(2,1) DEFAULT NULL,
  `statut` enum('reserve','annule','termine','valide','litige','avis_laisse') DEFAULT 'reserve',
  `date_reservation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajets_passagers`
--

INSERT INTO `trajets_passagers` (`id`, `id_trajet`, `id_passager`, `note_passager`, `statut`, `date_reservation`) VALUES
(1, 2, 2, 5.0, 'termine', '2026-01-19 16:01:34'),
(2, 2, 3, 5.0, 'termine', '2026-01-19 16:01:34'),
(3, 3, 5, 5.0, 'termine', '2026-01-19 16:01:34'),
(4, 3, 1, 5.0, 'termine', '2026-01-19 16:01:34'),
(5, 3, 4, 4.0, 'termine', '2026-01-19 16:01:34'),
(6, 4, 4, 5.0, 'termine', '2026-01-19 16:01:34'),
(7, 5, 2, NULL, 'reserve', '2026-01-19 16:19:49'),
(8, 5, 4, NULL, 'reserve', '2026-01-19 16:19:49'),
(9, 6, 3, NULL, 'reserve', '2026-01-19 16:19:49'),
(10, 7, 4, NULL, 'reserve', '2026-01-19 16:19:49'),
(16, 42, 19, NULL, 'litige', '2026-04-14 15:29:56'),
(18, 44, 2, NULL, 'reserve', '2026-04-24 10:01:15');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('passager','conducteur','passager-conducteur') NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `credits` int(11) DEFAULT 0,
  `date_inscription` datetime DEFAULT current_timestamp(),
  `photo` varchar(255) DEFAULT 'default.jpg',
  `driver_completed` tinyint(1) DEFAULT 0,
  `profile_completed` tinyint(1) DEFAULT 0,
  `suspendu` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_naissance`, `bio`, `credits`, `date_inscription`, `photo`, `driver_completed`, `profile_completed`, `suspendu`) VALUES
(1, 'C.', 'Nino', 'nino@example.com', 'mdp123', 'passager-conducteur', '1998-01-01', NULL, 0, '2025-11-06 16:57:52', 'nino.jpg', 1, 1, 0),
(2, 'M.', 'Finn', 'finn@example.com', 'mdp123', 'passager-conducteur', '1990-01-01', NULL, 0, '2025-11-06 16:57:52', 'finn.png', 1, 1, 0),
(3, 'B.', 'Antoine', 'antoine@example.com', 'mdp123', 'passager-conducteur', '1983-01-01', NULL, 0, '2025-11-06 16:57:52', 'antoine.jpg', 1, 1, 0),
(4, 'R.', 'Nina', 'nina@example.com', 'mdp123', 'passager', '2002-01-01', NULL, 0, '2025-11-06 16:57:52', 'nina.jpg', 0, 1, 0),
(5, 'K.', 'Théo', 'theo@example.com', 'mdp123', 'passager', '1995-01-01', NULL, 0, '2025-11-06 16:57:52', 'theo.jpg', 0, 1, 0),
(6, 'S.', 'Jeanne', 'jeanne@example.com', 'mdp123', 'conducteur', '1963-01-01', NULL, 0, '2025-11-06 16:57:52', 'jeanne.jpg', 1, 1, 0),
(19, 'Soual', 'Clara', 'clara.soual@example.com', '$2y$10$FZ8OsJdJDWO0i8B.v2/oPusnZqYI9ioFQg08QwQT/o8e3GWkJI1F2', 'passager-conducteur', '2002-02-07', 'j\'aime pas conduire', 0, '2026-01-22 15:23:09', '6995c08cbbe06_anniv chat.png', 0, 0, 0),
(47, 'testcredits', 'testcredits', 'test.credits@example.com', '$2y$10$.fG/lTZJoYUoj0sdU6a/JejbAROGatL7T9y2HI0L3pw/GG7Hc62la', 'passager', '2002-02-02', '', 0, '2026-04-06 13:42:16', 'default.jpg', 0, 1, 0),
(48, 'profiltest', 'testprofil', 'testprofil@example.com', '$2y$10$oY4xQObjH9MWryIYK5jBSuLVKhXcWeRejcIkA.J1//Y3mVouNofOO', 'passager', NULL, NULL, 0, '2026-04-15 17:01:23', 'default.jpg', 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

CREATE TABLE `vehicules` (
  `vehicule_id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `plaque` varchar(20) NOT NULL,
  `date_premiere_immat` date DEFAULT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `modele` varchar(50) DEFAULT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `carburant` varchar(50) DEFAULT NULL,
  `places` int(11) DEFAULT 4,
  `animaux_acceptes` enum('oui','non') DEFAULT 'non',
  `fumeur` enum('oui','non') DEFAULT 'non',
  `musique` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicules`
--

INSERT INTO `vehicules` (`vehicule_id`, `id_utilisateur`, `plaque`, `date_premiere_immat`, `marque`, `modele`, `couleur`, `carburant`, `places`, `animaux_acceptes`, `fumeur`, `musique`) VALUES
(1, 1, 'FE-543-JI', '2017-09-22', 'Honda', 'Civic', 'Bleu', 'Electrique', 4, 'non', 'non', NULL),
(2, 2, 'CD-321-BA', '2014-12-03', 'Opel', 'Agila', 'Blanche', 'Essence', 3, 'non', 'non', NULL),
(3, 3, 'GH-678-IJ', '2012-02-27', 'Citroen', 'C3', 'Noir', 'Diesel', 4, 'non', 'non', NULL),
(4, 6, 'CD-345-EF', '2016-11-29', 'Peugeot', '307', 'Grise', 'Diesel', 4, 'non', 'non', NULL),
(15, 19, 'AB-123-CD', '2020-05-15', 'Toyota', 'Corolla', 'Bleu', 'Electrique', 5, 'oui', 'non', 'Pop');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trajet` (`id_trajet`),
  ADD KEY `id_auteur` (`id_auteur`),
  ADD KEY `id_destinataire` (`id_destinataire`);

--
-- Index pour la table `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `employes`
--
ALTER TABLE `employes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `signalements`
--
ALTER TABLE `signalements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trajet` (`id_trajet`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_conducteur` (`id_conducteur`),
  ADD KEY `vehicule_id` (`vehicule_id`);

--
-- Index pour la table `trajets_passagers`
--
ALTER TABLE `trajets_passagers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_trajet` (`id_trajet`),
  ADD KEY `fk_passager` (`id_passager`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vehicules`
--
ALTER TABLE `vehicules`
  ADD PRIMARY KEY (`vehicule_id`),
  ADD KEY `fk_utilisateur` (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `credits`
--
ALTER TABLE `credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `employes`
--
ALTER TABLE `employes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `signalements`
--
ALTER TABLE `signalements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `trajets`
--
ALTER TABLE `trajets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT pour la table `trajets_passagers`
--
ALTER TABLE `trajets_passagers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `vehicules`
--
ALTER TABLE `vehicules`
  MODIFY `vehicule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_trajet`) REFERENCES `trajets` (`id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_auteur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `avis_ibfk_3` FOREIGN KEY (`id_destinataire`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `credits`
--
ALTER TABLE `credits`
  ADD CONSTRAINT `credits_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `signalements`
--
ALTER TABLE `signalements`
  ADD CONSTRAINT `signalements_ibfk_1` FOREIGN KEY (`id_trajet`) REFERENCES `trajets` (`id`),
  ADD CONSTRAINT `signalements_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD CONSTRAINT `trajets_ibfk_1` FOREIGN KEY (`id_conducteur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `trajets_ibfk_2` FOREIGN KEY (`vehicule_id`) REFERENCES `vehicules` (`vehicule_id`);

--
-- Contraintes pour la table `trajets_passagers`
--
ALTER TABLE `trajets_passagers`
  ADD CONSTRAINT `fk_passager` FOREIGN KEY (`id_passager`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trajet` FOREIGN KEY (`id_trajet`) REFERENCES `trajets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `vehicules`
--
ALTER TABLE `vehicules`
  ADD CONSTRAINT `fk_utilisateur` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicules_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
