-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 25 Juillet 2013 à 13:51
-- Version du serveur: 5.5.24-log
-- Version de PHP: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `swingmap`
--

-- --------------------------------------------------------

--
-- Structure de la table `client_coordinate`
--

CREATE TABLE IF NOT EXISTS `client_coordinate` (
  `idClient_Coordinate` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` varchar(50) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  PRIMARY KEY (`idClient_Coordinate`),
  KEY `idClient` (`idClient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ip_coordinate`
--

CREATE TABLE IF NOT EXISTS `ip_coordinate` (
  `idIP_Coordinate` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`idIP_Coordinate`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1053 ;

-- --------------------------------------------------------

--
-- Structure de la table `marker`
--

CREATE TABLE IF NOT EXISTS `marker` (
  `idMarker` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `lat` float(10,6) NOT NULL DEFAULT '0.000000',
  `lng` float(10,6) NOT NULL DEFAULT '0.000000',
  `ip` varchar(20) NOT NULL,
  `device` varchar(15) NOT NULL,
  `idDevice` varchar(50) NOT NULL,
  `idClient` varchar(50) NOT NULL,
  `idServer` varchar(50) NOT NULL,
  `volume` int(11) NOT NULL,
  PRIMARY KEY (`idMarker`),
  KEY `time` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39503 ;

DELIMITER $$
--
-- Événements
--
CREATE DEFINER=`root`@`localhost` EVENT `e_daily` ON SCHEDULE EVERY 1 DAY STARTS '2013-07-16 10:15:41' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Clear old ip_coordinate' DO BEGIN
        DELETE FROM `idIP_Coordinate` where `date` < DATE_SUB(curdate(), INTERVAL 30 DAY);
      END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
