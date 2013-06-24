-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2013 at 02:30 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `swingmap`
--

-- --------------------------------------------------------

--
-- Table structure for table `client_coordinate`
--

CREATE TABLE IF NOT EXISTS `client_coordinate` (
  `idClient_Coordinate` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` varchar(50) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  PRIMARY KEY (`idClient_Coordinate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ip_coordinate`
--

CREATE TABLE IF NOT EXISTS `ip_coordinate` (
  `idIP_Coordinate` int(11) NOT NULL AUTO_INCREMENT,
  `ip` int(20) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  PRIMARY KEY (`idIP_Coordinate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `marker`
--

CREATE TABLE IF NOT EXISTS `marker` (
  `idMarker` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `device` varchar(15) NOT NULL,
  `idClient` varchar(50) NOT NULL,
  `idServer` varchar(50) NOT NULL,
  `volume` int(11) NOT NULL,
  `idIP_Coordinate` int(11) NOT NULL,
  `idClient_Coordinate` int(11) NOT NULL,
  PRIMARY KEY (`idMarker`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
