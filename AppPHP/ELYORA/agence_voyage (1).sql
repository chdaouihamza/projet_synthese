-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 06:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agence_voyage`
--

-- --------------------------------------------------------

--
-- Table structure for table `activite`
--

CREATE TABLE `activite` (
  `id_activite` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `duree` varchar(50) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `lieu` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activite`
--

INSERT INTO `activite` (`id_activite`, `nom`, `description`, `prix`, `duree`, `disponible`, `lieu`) VALUES
(2, 'Lalla Aicha Park', 'Tourist attraction visit', 5.00, NULL, 1, 'Oujda'),
(3, 'Medina of Oujda', 'Tourist attraction visit', 0.00, NULL, 1, 'Oujda'),
(4, 'Medina of Oujda', 'Tourist attraction visit', 0.00, NULL, 1, 'Oujda');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `cin` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chambre`
--

CREATE TABLE `chambre` (
  `id_chambre` int(11) NOT NULL,
  `id_hotel` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guidetouristique`
--

CREATE TABLE `guidetouristique` (
  `id_guide_touristique` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `langues` varchar(255) DEFAULT NULL,
  `tarifHoraire` decimal(10,2) NOT NULL,
  `disponibilites` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hotel`
--

CREATE TABLE `hotel` (
  `id_hotel` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `disponibilites` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hotel`
--

INSERT INTO `hotel` (`id_hotel`, `nom`, `adresse`, `disponibilites`) VALUES
(1, 'Hotel Ibis Oujda', 'Oujda', 1);

-- --------------------------------------------------------

--
-- Table structure for table `itineraire`
--

CREATE TABLE `itineraire` (
  `id_itineraire` int(11) NOT NULL,
  `tempsTotal` decimal(10,2) DEFAULT NULL,
  `distance` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itineraire_lieutouristique`
--

CREATE TABLE `itineraire_lieutouristique` (
  `id_itineraire` int(11) NOT NULL,
  `id_lieu_touristique` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `dispo` tinyint(1) DEFAULT 1,
  `duree_estimee` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `itineraire_transport`
--

CREATE TABLE `itineraire_transport` (
  `id_itineraire` int(11) NOT NULL,
  `id_transport` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `dispo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lieutouristique`
--

CREATE TABLE `lieutouristique` (
  `id_lieu_touristique` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `histoire` text DEFAULT NULL,
  `photos` text DEFAULT NULL,
  `videos` text DEFAULT NULL,
  `horaires` varchar(255) DEFAULT NULL,
  `accessibilite` varchar(255) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lieutouristique`
--

INSERT INTO `lieutouristique` (`id_lieu_touristique`, `nom`, `description`, `histoire`, `photos`, `videos`, `horaires`, `accessibilite`, `prix`, `ville`, `image`) VALUES
(1, 'Lalla Aicha Park', 'Lush gardens, lake promenade, perfect picnic spot.', NULL, NULL, NULL, NULL, NULL, 5.00, 'Oujda', 'park.jfif'),
(2, 'Oujda Museum', 'Archaeologic & ethnographic richness of eastern Morocco.', NULL, NULL, NULL, NULL, NULL, 8.00, 'Oujda', '434646.webp'),
(3, 'Medina of Oujda', 'Souks, traditional crafts, authentic atmosphere.', NULL, NULL, NULL, NULL, NULL, 0.00, 'Oujda', 'lmdina.jfif'),
(4, 'Great Mosque of Oujda', '13th-century Islamic architecture.', NULL, NULL, NULL, NULL, NULL, 0.00, 'Oujda', 'mosque.jfif'),
(5, 'Jemaa el-Fnaa', 'Vibrant square with storytellers, food stalls.', NULL, NULL, NULL, NULL, NULL, 0.00, 'Marrakech', 'jemaa.jpg'),
(6, 'Majorelle Garden', 'Botanical garden & Berber museum.', NULL, NULL, NULL, NULL, NULL, 10.00, 'Marrakech', 'majorelle.jpg'),
(7, 'Al Quaraouiyine Mosque', 'Ancient university & mosque.', NULL, NULL, NULL, NULL, NULL, 0.00, 'Fes', 'quaraouiyine.jpg'),
(8, 'Chouara Tannery', 'Famous leather dye pits.', NULL, NULL, NULL, NULL, NULL, 5.00, 'Fes', 'tannery.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `statut` enum('EnAttente','Reussi','Echoue','Annule') NOT NULL DEFAULT 'EnAttente',
  `modePaiement` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `id_reservation`, `montant`, `statut`, `modePaiement`, `date`) VALUES
(1, 2, 5.50, 'Reussi', 'paypal', '2026-05-10 00:29:15'),
(2, 3, 16.50, 'Reussi', 'paypal', '2026-05-10 11:57:41'),
(3, 4, 71.50, 'Reussi', 'paypal', '2026-05-10 11:58:00'),
(4, 5, 170.50, 'Reussi', 'paypal', '2026-05-10 11:58:35'),
(5, 6, 0.00, 'Reussi', 'paypal', '2026-05-10 15:49:26'),
(6, 7, 0.00, 'Reussi', 'paypal', '2026-05-10 15:59:29'),
(7, 8, 33.00, 'Reussi', 'paypal', '2026-05-10 16:30:03'),
(8, 9, 0.00, 'Reussi', 'paypal', '2026-05-10 16:31:21');

-- --------------------------------------------------------

--
-- Table structure for table `plat`
--

CREATE TABLE `plat` (
  `id_plat` int(11) NOT NULL,
  `id_restaurant` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `allergenes` text DEFAULT NULL,
  `disponibles` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `id_touriste` int(11) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `statut` enum('EnAttente','Confirme','Annule','Paye') NOT NULL DEFAULT 'EnAttente',
  `id_hotel` int(11) DEFAULT NULL,
  `id_restaurant` int(11) DEFAULT NULL,
  `id_transport` int(11) DEFAULT NULL,
  `id_guide_touristique` int(11) DEFAULT NULL,
  `id_activite` int(11) DEFAULT NULL,
  `id_lieu_touristique` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id_reservation`, `id_touriste`, `date_debut`, `date_fin`, `statut`, `id_hotel`, `id_restaurant`, `id_transport`, `id_guide_touristique`, `id_activite`, `id_lieu_touristique`) VALUES
(2, 1, '2026-05-10 00:29:15', '2026-05-11 00:29:15', 'Paye', NULL, NULL, NULL, NULL, 2, NULL),
(3, 1, '2026-05-10 11:57:41', '2026-05-11 11:57:41', 'Paye', NULL, 1, NULL, NULL, NULL, NULL),
(4, 1, '2026-05-10 11:58:00', '2026-05-11 11:58:00', 'Paye', 1, NULL, NULL, NULL, NULL, NULL),
(5, 1, '2026-05-10 11:58:35', '2026-05-11 11:58:35', 'Paye', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, '2026-05-10 15:49:26', '2026-05-11 15:49:26', 'Paye', NULL, NULL, NULL, NULL, 3, NULL),
(7, 1, '2026-05-10 15:59:29', '2026-05-11 15:59:29', 'Paye', NULL, NULL, NULL, NULL, 4, NULL),
(8, 1, '2026-05-10 16:30:03', '2026-05-11 16:30:03', 'Paye', NULL, NULL, 1, NULL, NULL, NULL),
(9, 1, '2026-05-10 16:31:21', '2026-05-11 16:31:21', 'Paye', NULL, NULL, NULL, NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `id_restaurant` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `cuisine` varchar(100) DEFAULT NULL,
  `adresse` varchar(255) NOT NULL,
  `disponibles` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`id_restaurant`, `nom`, `cuisine`, `adresse`, `disponibles`) VALUES
(1, 'Café Al Andalous', NULL, 'Oujda', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sejour`
--

CREATE TABLE `sejour` (
  `id_sejour` int(11) NOT NULL,
  `id_touriste` int(11) NOT NULL,
  `budget` decimal(10,2) DEFAULT NULL,
  `dureeSejour` int(11) DEFAULT NULL,
  `centresInteret` text DEFAULT NULL,
  `modeTransportPrefere` varchar(100) DEFAULT 'Marche',
  `ville_preferee` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sejour`
--

INSERT INTO `sejour` (`id_sejour`, `id_touriste`, `budget`, `dureeSejour`, `centresInteret`, `modeTransportPrefere`, `ville_preferee`) VALUES
(1, 1, 200.00, 3, 'Food', 'Taxi', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `touriste`
--

CREATE TABLE `touriste` (
  `id_touriste` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `motDePasse` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `touriste`
--

INSERT INTO `touriste` (`id_touriste`, `nom`, `prenom`, `email`, `motDePasse`, `telephone`) VALUES
(1, 'chdaoui', 'hamza', 'chdaoui.hamza04@gmail.com', '$2y$10$unbsJWoIVDwvzcM.6G/H/ORJSGOoFkdQA7aj7Kq1aIkrwygq.gYvO', '0770703738');

-- --------------------------------------------------------

--
-- Table structure for table `transport`
--

CREATE TABLE `transport` (
  `id_transport` int(11) NOT NULL,
  `type` enum('Taxi','Location','Scooter','Marche','Velos') NOT NULL,
  `disponibilite` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transport`
--

INSERT INTO `transport` (`id_transport`, `type`, `disponibilite`) VALUES
(1, 'Taxi', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activite`
--
ALTER TABLE `activite`
  ADD PRIMARY KEY (`id_activite`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `chambre`
--
ALTER TABLE `chambre`
  ADD PRIMARY KEY (`id_chambre`),
  ADD KEY `id_hotel` (`id_hotel`);

--
-- Indexes for table `guidetouristique`
--
ALTER TABLE `guidetouristique`
  ADD PRIMARY KEY (`id_guide_touristique`);

--
-- Indexes for table `hotel`
--
ALTER TABLE `hotel`
  ADD PRIMARY KEY (`id_hotel`);

--
-- Indexes for table `itineraire`
--
ALTER TABLE `itineraire`
  ADD PRIMARY KEY (`id_itineraire`);

--
-- Indexes for table `itineraire_lieutouristique`
--
ALTER TABLE `itineraire_lieutouristique`
  ADD PRIMARY KEY (`id_itineraire`,`id_lieu_touristique`),
  ADD KEY `id_lieu_touristique` (`id_lieu_touristique`);

--
-- Indexes for table `itineraire_transport`
--
ALTER TABLE `itineraire_transport`
  ADD PRIMARY KEY (`id_itineraire`,`id_transport`),
  ADD KEY `id_transport` (`id_transport`);

--
-- Indexes for table `lieutouristique`
--
ALTER TABLE `lieutouristique`
  ADD PRIMARY KEY (`id_lieu_touristique`);

--
-- Indexes for table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Indexes for table `plat`
--
ALTER TABLE `plat`
  ADD PRIMARY KEY (`id_plat`),
  ADD KEY `id_restaurant` (`id_restaurant`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_touriste` (`id_touriste`),
  ADD KEY `id_hotel` (`id_hotel`),
  ADD KEY `id_restaurant` (`id_restaurant`),
  ADD KEY `id_transport` (`id_transport`),
  ADD KEY `id_guide_touristique` (`id_guide_touristique`),
  ADD KEY `id_activite` (`id_activite`),
  ADD KEY `id_lieu_touristique` (`id_lieu_touristique`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`id_restaurant`);

--
-- Indexes for table `sejour`
--
ALTER TABLE `sejour`
  ADD PRIMARY KEY (`id_sejour`),
  ADD KEY `id_touriste` (`id_touriste`);

--
-- Indexes for table `touriste`
--
ALTER TABLE `touriste`
  ADD PRIMARY KEY (`id_touriste`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `transport`
--
ALTER TABLE `transport`
  ADD PRIMARY KEY (`id_transport`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activite`
--
ALTER TABLE `activite`
  MODIFY `id_activite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chambre`
--
ALTER TABLE `chambre`
  MODIFY `id_chambre` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guidetouristique`
--
ALTER TABLE `guidetouristique`
  MODIFY `id_guide_touristique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hotel`
--
ALTER TABLE `hotel`
  MODIFY `id_hotel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `itineraire`
--
ALTER TABLE `itineraire`
  MODIFY `id_itineraire` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lieutouristique`
--
ALTER TABLE `lieutouristique`
  MODIFY `id_lieu_touristique` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `plat`
--
ALTER TABLE `plat`
  MODIFY `id_plat` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `id_restaurant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sejour`
--
ALTER TABLE `sejour`
  MODIFY `id_sejour` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `touriste`
--
ALTER TABLE `touriste`
  MODIFY `id_touriste` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transport`
--
ALTER TABLE `transport`
  MODIFY `id_transport` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chambre`
--
ALTER TABLE `chambre`
  ADD CONSTRAINT `chambre_ibfk_1` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`) ON DELETE CASCADE;

--
-- Constraints for table `itineraire_lieutouristique`
--
ALTER TABLE `itineraire_lieutouristique`
  ADD CONSTRAINT `itineraire_lieutouristique_ibfk_1` FOREIGN KEY (`id_itineraire`) REFERENCES `itineraire` (`id_itineraire`) ON DELETE CASCADE,
  ADD CONSTRAINT `itineraire_lieutouristique_ibfk_2` FOREIGN KEY (`id_lieu_touristique`) REFERENCES `lieutouristique` (`id_lieu_touristique`) ON DELETE CASCADE;

--
-- Constraints for table `itineraire_transport`
--
ALTER TABLE `itineraire_transport`
  ADD CONSTRAINT `itineraire_transport_ibfk_1` FOREIGN KEY (`id_itineraire`) REFERENCES `itineraire` (`id_itineraire`) ON DELETE CASCADE,
  ADD CONSTRAINT `itineraire_transport_ibfk_2` FOREIGN KEY (`id_transport`) REFERENCES `transport` (`id_transport`) ON DELETE CASCADE;

--
-- Constraints for table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id_reservation`) ON DELETE CASCADE;

--
-- Constraints for table `plat`
--
ALTER TABLE `plat`
  ADD CONSTRAINT `plat_ibfk_1` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurant` (`id_restaurant`) ON DELETE CASCADE;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_touriste`) REFERENCES `touriste` (`id_touriste`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`id_hotel`) REFERENCES `hotel` (`id_hotel`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservation_ibfk_3` FOREIGN KEY (`id_restaurant`) REFERENCES `restaurant` (`id_restaurant`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservation_ibfk_4` FOREIGN KEY (`id_transport`) REFERENCES `transport` (`id_transport`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservation_ibfk_5` FOREIGN KEY (`id_guide_touristique`) REFERENCES `guidetouristique` (`id_guide_touristique`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservation_ibfk_6` FOREIGN KEY (`id_activite`) REFERENCES `activite` (`id_activite`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservation_ibfk_7` FOREIGN KEY (`id_lieu_touristique`) REFERENCES `lieutouristique` (`id_lieu_touristique`) ON DELETE SET NULL;

--
-- Constraints for table `sejour`
--
ALTER TABLE `sejour`
  ADD CONSTRAINT `sejour_ibfk_1` FOREIGN KEY (`id_touriste`) REFERENCES `touriste` (`id_touriste`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
