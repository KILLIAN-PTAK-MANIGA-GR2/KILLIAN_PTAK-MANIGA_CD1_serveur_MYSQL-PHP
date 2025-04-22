-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 22 avr. 2025 à 10:22
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `library`
--

-- --------------------------------------------------------

--
-- Structure de la table `book`
--

CREATE TABLE `book` (
  `idbook` int NOT NULL,
  `title` varchar(75) NOT NULL,
  `author` varchar(75) NOT NULL,
  `date_publication` date NOT NULL,
  `category_idcategory` int NOT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `book`
--

INSERT INTO `book` (`idbook`, `title`, `author`, `date_publication`, `category_idcategory`, `disponible`) VALUES
(9, 'L\'Étranger', 'Albert Camus', '1942-01-01', 1, 1),
(10, 'Harry Potter à l\'école des sorciers', 'J.K. Rowling', '1997-06-26', 1, 0),
(11, 'La Vie est une fête', 'David Foenkinos', '2021-03-04', 1, 1),
(12, 'Le Sympathisant', 'Viet Thanh Nguyen', '2015-04-02', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `idcategory` int NOT NULL,
  `name` varchar(45) NOT NULL,
  `desciption` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`idcategory`, `name`, `desciption`) VALUES
(1, 'Roman', 'Fiction littéraire');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`idbook`),
  ADD KEY `fk_book_category_idx` (`category_idcategory`);

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`idcategory`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `book`
--
ALTER TABLE `book`
  MODIFY `idbook` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `idcategory` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `fk_book_category` FOREIGN KEY (`category_idcategory`) REFERENCES `category` (`idcategory`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

--trier les livres par titre
SELECT * FROM `library`.`book`;
SELECT * FROM `library`.`book`
WHERE YEAR(`date_publication`) > 2000
ORDER BY `title` ASC;
