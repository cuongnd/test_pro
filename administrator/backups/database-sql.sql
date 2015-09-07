#
# Powered by XCloner Site Backup
# http://www.xcloner.com
#
# Host: localhost
# Generation Time: Sep 30, 2014 at 16:59
# Server version: 5.5.27
# PHP Version: 5.4.7
# Database : `test_pro`
# --------------------------------------------------------
#
# Table structure for table `ueb3c_xmap_sitemap`
#
CREATE TABLE `ueb3c_xmap_sitemap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `introtext` text,
  `metadesc` text,
  `metakey` text,
  `attribs` text,
  `selections` text,
  `excluded_items` text,
  `is_default` int(1) DEFAULT '0',
  `state` int(2) DEFAULT NULL,
  `access` int(11) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `count_xml` int(11) DEFAULT NULL,
  `count_html` int(11) DEFAULT NULL,
  `views_xml` int(11) DEFAULT NULL,
  `views_html` int(11) DEFAULT NULL,
  `lastvisit_xml` int(11) DEFAULT NULL,
  `lastvisit_html` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
#
# Dumping data for table `ueb3c_xmap_sitemap`
#
INSERT INTO `ueb3c_xmap_sitemap` VALUES ('1','simap1','simap1','','','','{\"showintro\":\"1\",\"show_menutitle\":\"1\",\"classname\":\"\",\"columns\":\"\",\"exlinks\":\"img_blue.gif\",\"compress_xml\":\"1\",\"beautify_xml\":\"1\",\"include_link\":\"1\",\"news_publication_name\":\"\"}','{\"mainmenu\":{\"priority\":\"0.5\",\"changefreq\":\"weekly\",\"ordering\":0},\"footer-menu-3\":{\"priority\":\"0.5\",\"changefreq\":\"weekly\",\"ordering\":1}}','','1','1','1','2014-06-09 18:50:00','41596','0','66','0','1410157914','0');
