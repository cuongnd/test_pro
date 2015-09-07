-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2014 at 07:36 PM
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
-- Table structure for table `asian_bookpro_sendemail`
--

CREATE TABLE IF NOT EXISTS `asian_bookpro_sendemail` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `code` varchar(50) NOT NULL,
  `desc` text NOT NULL,
  `state` tinyint(4) NOT NULL,
  `email_send_from_name` varchar(250) NOT NULL,
  `email_send_from` varchar(250) NOT NULL,
  `email_admin` varchar(200) NOT NULL,
  `email_body` text,
  `email_subject` varchar(250) DEFAULT NULL,
  `order_status` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `asian_bookpro_sendemail`
--

INSERT INTO `asian_bookpro_sendemail` (`id`, `title`, `code`, `desc`, `state`, `email_send_from_name`, `email_send_from`, `email_admin`, `email_body`, `email_subject`, `order_status`, `payment_status`, `ordering`) VALUES
(5, 'Tour 1', 'TOUR', 'Tour booking component', 0, 'Tour booking (Demo)', 'demo@ibookingonline.com', 'demo@ibookingonline.com', '<p>Using this guide</p>', 'New order received', '', '', 0),
(8, 'Tour Manager', 'TOUR', 'Tour booking component', 1, 'Tour booking (Demo)', 'demo@ibookingonline.com', 'demo@ibookingonline.com', '<p>Using this guide</p>', 'New order received', '', '', 0),
(9, 'Tour Manager', 'TOUR', 'Tour booking component', 1, 'Tour booking (Demo)', 'demo@ibookingonline.com', 'demo@ibookingonline.com', '<p>Using this guide</p>', 'New order received', '', '', 0),
(10, 'Kien chuan 1', 'HOTEL', '', 1, 'kien@ibookingonline.com', 'Email test 1', 'kien@ibookingonline.com', '<p>TÉT</p>\r\n<p>{pre-trip_transfer}{additional_trips}{type}{pre-trip_hotel}</p>', 'okokokok', 'PENDING', 'SUCCESS', 0),
(11, 'Kien chuan 1', 'HOTEL', '', 0, 'kien@ibookingonline.com', 'Email test 1', 'kien@ibookingonline.com', '<p>TÉT</p>\r\n<p>{pre-trip_transfer}{additional_trips}{type}{pre-trip_hotel}</p>', 'okokokok', 'PENDING', 'SUCCESS', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
