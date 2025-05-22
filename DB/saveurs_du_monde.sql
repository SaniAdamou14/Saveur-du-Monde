-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 07 avr. 2025 à 09:57
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `saveurs_du_monde`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$12$l4n/Rt7PgQ/o88.S7dgA6ucxyO0DY3iuQweYUAflOB2JPL44rMIJG');

-- --------------------------------------------------------

--
-- Structure de la table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `admin_reply` text,
  `reply_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`, `admin_reply`, `reply_date`) VALUES
(1, 'Ali', 'ali@gmail.com', 'J&#39;adore votre restaurant ', '2025-04-01 12:28:44', 'Merci beaucoup', '2025-04-01 12:42:06'),
(2, 'moussa', 'moussa@gmail.com', 'Votre site web este tres interactif, bravo !', '2025-04-01 12:46:26', 'merci ', '2025-04-01 12:50:27'),
(3, 'Eunice', 'eunice@gmail.com', 'Comment ont fait pour que vous validiez nos reservations ?', '2025-04-01 13:35:53', 'Ca a ete pris en charge madame, merci pour votre fideliter !', '2025-04-01 14:18:31'),
(4, 'Ali', 'ali@gmail.com', 'Bonjour, je voulais vous dire que votre restaurant est de loin le meilleur que j&#39;ai jamais visiter !', '2025-04-01 15:17:06', 'merci', '2025-04-05 10:29:19'),
(5, 'Sani', 'sani@gmail.com', 'Bravo !', '2025-04-05 10:33:11', 'dedqdqwdqwdwdqwdqw', '2025-04-05 13:42:04');

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `menu`
--

INSERT INTO `menu` (`id`, `name`, `description`, `price`, `category`, `image`, `created_at`) VALUES
(5, 'Pizza 3 fromages', 'Une pizza geante pour les amateur de plat rare', 12.01, 'Pizza', 'pizza.jpg', '2025-04-01 11:18:43'),
(6, 'Salade de Thon', 'Une excellente Salade de Thon très bien assaisonner ', 5.13, 'Fruit de mer', 'salade_thon.png', '2025-04-01 11:19:45'),
(7, 'Coca Cola', 'Boisson a la mode et tres rafrechissant', 3.05, 'Boisson', 'cola.jpg', '2025-04-01 13:28:46'),
(8, 'Chawarma', 'Délicieuse nourriture qui nous vient tout droit du Mexique !', 7.03, 'fast-food', 'charwarma.jpg', '2025-04-01 15:06:36'),
(9, 'Cuise de Poulet', 'Délicieuse Cuise de Poulet avec de la sauce tomate bien épicé ', 8.02, 'Volaille', 'poulet.jpg', '2025-04-01 15:14:24'),
(10, 'Danbou', 'Spécialité nigérienne a base de couscous !', 6.00, 'Couscous', 'Dambou.jpg', '2025-04-05 10:31:02');

-- --------------------------------------------------------

--
-- Structure de la table `menu_comments`
--

DROP TABLE IF EXISTS `menu_comments`;
CREATE TABLE IF NOT EXISTS `menu_comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `menu_id` int NOT NULL,
  `comment` text NOT NULL,
  `rating` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_id` (`menu_id`)
) ;

--
-- Déchargement des données de la table `menu_comments`
--

INSERT INTO `menu_comments` (`id`, `menu_id`, `comment`, `rating`, `created_at`) VALUES
(1, 7, 'Très rafraichissant !!!', 5, '2025-04-01 15:49:33'),
(2, 5, 'Une pizza pas comme les autres, je recommande vivement !', 5, '2025-04-01 15:50:24'),
(3, 9, 'Pour mes frere et soeur qui aime le poulet, vous n&#39;aller pas le regretter !', 5, '2025-04-01 15:51:19'),
(4, 7, 'Chere !', 2, '2025-04-05 10:28:27'),
(5, 7, 'Tres cool !', 3, '2025-04-05 13:43:51'),
(6, 10, 'J&#39;ai essayer cet plat il y&#39;a quelques jours, je recommande fortement ! C&#39;est vraiment extraordinairement delicieux !!!', 5, '2025-04-07 08:21:43');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `guests` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `email`, `phone`, `reservation_date`, `reservation_time`, `guests`) VALUES
(1, 'Eunice', 'eunice@gmail.com', '+22798765432', '2025-04-09', '10:30:00', 2),
(2, 'Ali', 'ali@gmail.com', '+22797341254', '2025-04-26', '12:00:00', 2),
(3, 'Ali', 'ali@gmail.com', '+22794805811', '2025-06-12', '22:20:00', 4);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `created_at`) VALUES
(1, 'ali@gmail.com', '$2y$12$te1eiavt5cjw9Ey.9xrAfOIMwY/Y4xhX7HOkH7B2aLR5ZRyfRVaGS', 'Ali', '2025-04-01 14:14:31'),
(2, 'sani@gmail.com', '$2y$12$/no19.Z5guk9So1n1szlWO3HMquVqjvyWq7tUnlgGM9qXcrnrTbLq', 'Sani', '2025-04-05 10:27:37');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
