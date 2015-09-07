--
-- Installation package for Notes.
--
-- @package		Notes
CREATE TABLE IF NOT EXISTS `#__social_tasks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `state` tinyint(3) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`state`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;