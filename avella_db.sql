-- Avella — schéma MySQL aligné sur pages/admin.php
-- Base : avella_db (voir $db dans admin.php)
-- Import : phpMyAdmin ou mysql -u root < avella_db.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS `avella_db`;
CREATE DATABASE `avella_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `avella_db`;

-- ---------------------------------------------------------------------------
-- users : SELECT * ; colonnes utilisées (nom, email, role, created_at)
-- Formulaire admin : nom, email, password, role (acheteur | vendeur | admin)
-- Formulaire public (creation-de-compte) : prénom / téléphone optionnels en BDD
-- ---------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(120) NOT NULL,
  `prenom` VARCHAR(120) DEFAULT NULL,
  `email` VARCHAR(190) NOT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'hash bcrypt (PASSWORD_DEFAULT)',
  `role` ENUM('acheteur','vendeur','admin') NOT NULL DEFAULT 'acheteur',
  `telephone` VARCHAR(40) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- categories : JOIN produits.categorie_id = categories.id ; colonnes nom (alias cat)
-- Formulaire actions/categorie.php : nom, slug, description
-- IDs 1–6 = options du select « Ajouter un produit » dans admin.php
-- ---------------------------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(120) NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`),
  KEY `idx_categories_nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- boutiques : JOIN users ; colonnes nom, user_id, statut (actif | inactif), created_at
-- Formulaire : nom, user_id, description, statut
-- ---------------------------------------------------------------------------
CREATE TABLE `boutiques` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `nom` VARCHAR(180) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `statut` ENUM('actif','inactif') NOT NULL DEFAULT 'actif',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_boutiques_user` (`user_id`),
  KEY `idx_boutiques_statut` (`statut`),
  CONSTRAINT `fk_boutiques_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- produits : JOIN categories ; colonnes id, nom, prix, stock, categorie_id, created_at
-- Formulaire : nom, prix, stock, categorie_id, description, image (fichier)
-- ---------------------------------------------------------------------------
CREATE TABLE `produits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `categorie_id` INT UNSIGNED DEFAULT NULL,
  `nom` VARCHAR(200) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `prix` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `stock` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(512) DEFAULT NULL COMMENT 'chemin relatif ex. uploads/photo.jpg',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_produits_categorie` (`categorie_id`),
  KEY `idx_produits_nom` (`nom`),
  CONSTRAINT `fk_produits_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------------------------
-- commandes : JOIN users ; total, statut, created_at
-- Statuts affichés : en_attente, confirmee, expediee, livree, annulee
-- ---------------------------------------------------------------------------
CREATE TABLE `commandes` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `total` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `statut` ENUM('en_attente','confirmee','expediee','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_commandes_user` (`user_id`),
  KEY `idx_commandes_statut` (`statut`),
  CONSTRAINT `fk_commandes_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------------------------
-- DONNÉES DE TEST
-- Mot de passe pour tous les comptes seed : password
-- Hash bcrypt équivalent à password_hash('password', PASSWORD_DEFAULT) PHP
-- ---------------------------------------------------------------------------
SET @pwd := '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `telephone`, `created_at`) VALUES
(1, 'Admin Avella', NULL, 'admin@avella.tn', @pwd, 'admin', NULL, '2025-01-10 10:00:00'),
(2, 'Ben Salah Amira', 'Amira', 'amira.vendeur@mail.tn', @pwd, 'vendeur', '+216 55 100 200', '2025-02-01 11:00:00'),
(3, 'Mansour Karim', 'Karim', 'karim.shoes@mail.tn', @pwd, 'vendeur', '+216 55 200 300', '2025-02-05 12:00:00'),
(4, 'Trabelsi Leila', 'Leila', 'leila.client@mail.tn', @pwd, 'acheteur', '+216 98 111 222', '2025-02-10 14:00:00'),
(5, 'Ben Youssef Sami', 'Sami', 'sami.client@mail.tn', @pwd, 'acheteur', '+216 22 333 444', '2025-03-01 09:30:00');

INSERT INTO `categories` (`id`, `nom`, `slug`, `description`, `created_at`) VALUES
(1, 'Vêtements', 'vetements', 'Prêt-à-porter et pièces signature', '2025-01-01 08:00:00'),
(2, 'Chaussures', 'shoes', 'Chaussures et mules', '2025-01-01 08:00:00'),
(3, 'Accessoires', 'accesoires', 'Sacs et accessoires (slug comme dans admin.php)', '2025-01-01 08:00:00'),
(4, 'Bijoux Pearls', 'pearls', 'Bijoux et perles', '2025-01-01 08:00:00'),
(5, 'Soins de peau', 'skin', 'Cosmétiques et soins', '2025-01-01 08:00:00'),
(6, 'Grace', 'grace', 'Collection Grace', '2025-01-01 08:00:00'),
(7, 'Home Decor', 'home-decor', 'Décoration intérieure', '2025-01-01 08:00:00'),
(8, 'Produits Alimentaires', 'alimentaires', 'Épicerie fine', '2025-01-01 08:00:00'),
(9, 'Beauté & Soins', 'beaute-soins', 'Beauté', '2025-01-01 08:00:00'),
(10, 'Fitness', 'fitness', 'Sport et bien-être', '2025-01-01 08:00:00'),
(11, 'High Tech', 'high-tech', 'Électronique', '2025-01-01 08:00:00'),
(12, 'Livres & BD', 'livres', 'Lecture', '2025-01-01 08:00:00'),
(13, 'Artisanat', 'artisanat', 'Créations artisanales', '2025-01-01 08:00:00');

INSERT INTO `boutiques` (`id`, `user_id`, `nom`, `description`, `statut`, `created_at`) VALUES
(1, 2, 'SHA — Stylée & Raffinée', 'Boutique vêtements et ensembles coordonnés.', 'actif', '2025-02-02 10:00:00'),
(2, 3, 'Step by Sarah', 'Chaussures et mules tendance.', 'actif', '2025-02-06 11:00:00'),
(3, 2, 'Pearls & Co', 'Bijoux et accessoires nacrés.', 'inactif', '2025-02-15 16:00:00');

INSERT INTO `produits` (`id`, `categorie_id`, `nom`, `description`, `prix`, `stock`, `image`, `created_at`) VALUES
(1, 1, 'Set coordonné beige', 'Ensemble deux pièces ton sur ton.', 189.00, 12, '../boutiques/set.png', '2025-02-20 10:00:00'),
(2, 1, 'Robe longue blanche', 'Robe fluide pour soirée.', 245.50, 5, '../boutiques/robe.png', '2025-02-21 11:00:00'),
(3, 2, 'Mules Avella camel', 'Mules confort semelle cuir.', 129.99, 20, '../boutiques/shoes.png', '2025-02-22 09:00:00'),
(4, 4, 'Collier perles classique', 'Perles d’eau douce et fermoir argent.', 79.00, 30, '../boutiques/access.png', '2025-02-25 14:00:00'),
(5, 5, 'Sérum hydratant', 'Sérum visage usage quotidien.', 65.00, 0, '../boutiques/beauty.png', '2025-03-01 12:00:00'),
(6, 6, 'Cadre décoratif « Grace »', 'Décoration murale style minimal.', 45.00, 8, '../boutiques/art.png', '2025-03-05 08:00:00');

INSERT INTO `commandes` (`id`, `user_id`, `total`, `statut`, `created_at`) VALUES
(1, 4, 314.50, 'confirmee', '2025-03-10 15:00:00'),
(2, 5, 129.99, 'en_attente', '2025-03-12 18:30:00'),
(3, 4, 65.00, 'livree', '2025-02-28 09:00:00'),
(4, 5, 189.00, 'expediee', '2025-03-14 10:00:00'),
(5, 4, 50.00, 'annulee', '2025-03-01 11:00:00');
