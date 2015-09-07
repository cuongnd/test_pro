-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2014 at 08:31 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `asian`
--

-- --------------------------------------------------------

--
-- Table structure for table `asian_bookpro_packageratedaytripjoingroup`
--

CREATE TABLE IF NOT EXISTS `asian_bookpro_packageratedaytripjoingroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `tourpackage_id` int(11) NOT NULL,
  `adult` float NOT NULL,
  `teen` float NOT NULL,
  `child1` float NOT NULL,
  `child2` float NOT NULL,
  `child3` float NOT NULL,
  `adult_promo` float NOT NULL,
  `teen_promo` float NOT NULL,
  `child_promo` int(11) NOT NULL,
  `extra_bed` float NOT NULL,
  `pretransfer` float NOT NULL,
  `posttransfer` float NOT NULL,
  `type` tinyint(4) NOT NULL,
  `state` tinyint(4) NOT NULL,
  `close` tinyint(4) DEFAULT NULL,
  `guaranteed` tinyint(4) DEFAULT NULL,
  `request` tinyint(4) DEFAULT NULL,
  `available` tinyint(4) DEFAULT NULL,
  `tour_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
