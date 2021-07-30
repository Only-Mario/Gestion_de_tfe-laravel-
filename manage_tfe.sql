-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 17 mars 2021 à 15:56
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `manage_tfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'milo', '$2y$10$2GP4iFB1/8opN7seU/zikuexV3Q8SV5B81CS2JYBJ8dndcF8YLrKe', NULL, '2021-02-10 09:22:26', '2021-02-10 09:22:26');

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `name`, `path`, `created_at`, `updated_at`) VALUES
(1, 'SMART_IRRIGATION.pdf', 'public/tfe_documents/SMART_IRRIGATION.pdf', '2021-02-08 07:33:06', '2021-02-08 07:33:06'),
(2, 'SMART_GARAGE.pdf', 'public/tfe_documents/SMART_GARAGE.pdf', '2021-02-08 07:47:55', '2021-02-08 07:47:55'),
(3, 'REMOTE_CONTROL.pdf', 'public/tfe_documents/REMOTE_CONTROL.pdf', '2021-02-09 11:06:47', '2021-02-09 11:06:47');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_12_28_141443_create_admins_table', 1),
(5, '2020_12_28_193803_create_tfes_table', 1),
(6, '2020_12_31_105834_create_tfe_documents_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('anselmezodehougan72@gmail.com', '$2y$10$JvC0skj/G5mFbybqoz6lE.UJKeKOgrQB5D1tpt4SnIztdcJZETiP2', '2021-02-08 10:53:17');

-- --------------------------------------------------------

--
-- Structure de la table `tves`
--

CREATE TABLE `tves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `theme` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auteurs` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee_de_realisation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupe_pedagogique` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tuteur_de_stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lieu_de_stage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_tuteur` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `maitre_memoire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_maitre_memoire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tves`
--

INSERT INTO `tves` (`id`, `document_id`, `theme`, `auteurs`, `annee_de_realisation`, `groupe_pedagogique`, `tuteur_de_stage`, `lieu_de_stage`, `email_tuteur`, `maitre_memoire`, `email_maitre_memoire`, `created_at`, `updated_at`) VALUES
(1, 1, 'Smart Irrigation', 'Ahmed ADJIBADE', '2019', 'GEI', 'Abel Konnon', 'Calavi', 'email@g.com', 'Abel Konnon', 'email@g.com', '2021-02-08 07:33:06', '2021-02-08 07:33:06'),
(2, 2, 'Smart Garage', 'Agbolossou', '2000', 'GEI', 'Toto Samba', 'Cotonou', 'a@a.com', 'Toto Samba', 'a@a.com', '2021-02-08 07:47:55', '2021-02-08 07:47:55'),
(3, 3, 'Remote Control', 'Toto', '2000', 'GEI', 'Tata', 'Ctn', 'tata@g.com', 'Tata', 'tata@g.com', '2021-02-09 11:06:47', '2021-02-09 11:06:47');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `study_year` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `matricule` int(11) NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `entity`, `study_year`, `email`, `email_verified_at`, `password`, `matricule`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Anselme Zodehougan', 'GEI', '3', 'anselmezodehougan72@gmail.com', NULL, '$2y$10$2GP4iFB1/8opN7seU/zikuexV3Q8SV5B81CS2JYBJ8dndcF8YLrKe', 12345678, '5JGrduIYtcZAgyLowzAbcEAEFmqbO4HrqxodpGHtEY4bwHmZg7xSJ5cbkJTr', '2021-02-08 07:30:43', '2021-02-08 07:30:43'),
(3, 'HOUNYOVI Stanislas', 'GEI', '3', 'hounyovistanislas@gmail.com', NULL, '$2y$10$/10vLAcnkEs83B6z3dARq.Ekbih0PX..h7aAJmuRByhJaNYopUe9G', 66031744, NULL, '2021-02-09 11:04:25', '2021-02-09 11:04:25');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `tves`
--
ALTER TABLE `tves`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tves_theme_unique` (`theme`),
  ADD KEY `tves_document_id_index` (`document_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_name_unique` (`name`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_matricule_unique` (`matricule`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `tves`
--
ALTER TABLE `tves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
