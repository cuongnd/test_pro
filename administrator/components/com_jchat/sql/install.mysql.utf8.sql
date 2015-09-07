-- Basic release schema 1.0
CREATE TABLE IF NOT EXISTS `#__jchat` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `from` int(11) NOT NULL,
	 `to` int(11) NOT NULL,
	 `message` text NOT NULL,
	 `sent` int(11) NOT NULL,
	 `read` tinyint(4) NOT NULL,
	 `type` varchar(255) NOT NULL DEFAULT 'message',
	 `status` tinyint(4) NOT NULL DEFAULT 0,
	 `clientdeleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`)
) ENGINE=InnoDB CHARACTER SET `utf8`;
		
 CREATE TABLE IF NOT EXISTS `#__jchat_status` (
	 `userid` int(11) unsigned NOT NULL,
	 `status` varchar(11) DEFAULT NULL,
	PRIMARY KEY (`userid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

 CREATE TABLE IF NOT EXISTS `#__jchat_contacts` (
	`ownerid` int(11) NOT NULL, 
	`contactid` int(11) NOT NULL,
  PRIMARY KEY (`ownerid`, `contactid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

 CREATE TABLE IF NOT EXISTS `#__jchat_wall` (
	`messageid` int(11) NOT NULL, 
	`userid` int(11) NOT NULL,
  PRIMARY KEY (`messageid`, `userid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

-- Updates on version 2.0
ALTER TABLE  `#__jchat_contacts` CHANGE  `ownerid`  `ownerid` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `#__jchat_contacts` CHANGE  `contactid`  `contactid` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `#__jchat_status` CHANGE  `userid`  `userid` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `#__jchat_wall` CHANGE  `userid`  `userid` VARCHAR( 100 ) NOT NULL;
DROP TABLE `#__jchat`;
CREATE TABLE `#__jchat` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	 `from` VARCHAR( 255 ) NOT NULL,
	 `to` VARCHAR( 255 ) NOT NULL,
	 `message` text NOT NULL,
	 `sent` int(11) NOT NULL,
	 `read` tinyint(4) NOT NULL,
	 `type` varchar(255) NOT NULL DEFAULT 'message',
	 `status` tinyint(4) NOT NULL DEFAULT 0,
	 `clientdeleted` tinyint(4) NOT NULL DEFAULT 0,
	 `actualfrom` VARCHAR( 255 ) NOT NULL,
	 `actualto` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `to` (`to`),
  INDEX `from` (`from`),
  INDEX `actualfrom` (`actualfrom`),
  INDEX `actualto` (`actualto`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

DROP TABLE `#__jchat_status`;
CREATE TABLE IF NOT EXISTS `#__jchat_status` (
	 `userid` varchar(255) NOT NULL,
	 `status` varchar(11) DEFAULT NULL,
	 `skypeid` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`userid`)
) ENGINE=InnoDB CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__jchat_skypeuser` (
	`userid` INT NOT NULL ,
	`skypeid` VARCHAR( 255 ) NOT NULL ,
	PRIMARY KEY ( `userid` )
) ENGINE = INNODB;
