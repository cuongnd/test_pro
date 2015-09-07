<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

/**
 * Script file of AltaLeda component
 */
class com_ijoomla_seoInstallerScript{

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install(){
		$db =& JFactory::getDBO();
		$sql = "CREATE TABLE if not exists `#__ijseo_config` (
				  `id` int(3) unsigned NOT NULL auto_increment,
				  `params` text NOT NULL,
				   PRIMARY KEY  (`id`)
				 )";
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-1-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE if not exists `#__ijseo_ilinks_category` (
				  `id` int(10) unsigned NOT NULL auto_increment,
				  `name` varchar(255) NOT NULL,
				  `published` tinyint(1) unsigned NOT NULL default '1',
				   PRIMARY KEY  (`id`)
				 )";
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-1-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_ilinks_articles` (
					  `ilink_id` int(10) NOT NULL,
					  `article_id` int(10) NOT NULL,
					  PRIMARY KEY (`ilink_id`,`article_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;
			";
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-1-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE if not exists `#__ijseo_redirect_category` (
				  `id` int(10) unsigned NOT NULL auto_increment,
				  `name` varchar(255) NOT NULL,
				  `published` tinyint(1) unsigned NOT NULL default '1',
				   PRIMARY KEY  (`id`)
				 )";
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-11-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(50) NOT NULL,
				  `links_to` text NOT NULL,
				  `rel_nofollow` tinyint(1) NOT NULL,
				  `target` varchar(10) NOT NULL,
				  `link_text` text NOT NULL,
				  `image` varchar(100) NOT NULL,
				  `hits` int(11) NOT NULL,
				  `last_hit_reset` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				  `catid` int(10) NOT NULL DEFAULT '1',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2";
		
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-2-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_keys` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `rank` int(11) NOT NULL,
				  `rchange` int(10) unsigned NOT NULL,
				  `mode` tinyint(1) NOT NULL DEFAULT '-1',
				  `checkdate` datetime NOT NULL,
				  `sticky` tinyint(3) unsigned NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				)";
		
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-10-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_title` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `article_id` int(11) NOT NULL,
				  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `rank` int(11) NOT NULL,
				  `rchange` int(10) unsigned NOT NULL,
				  `mode` tinyint(1) NOT NULL DEFAULT '-1',
				  `checkdate` datetime NOT NULL,
				  `sticky` tinyint(3) unsigned NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				)";
		
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-10-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE if not exists `#__ijseo_ilinks` (
				  `id` int(10) unsigned NOT NULL auto_increment,
				  `name` varchar(255) NOT NULL,
				  `published` tinyint(1) unsigned NOT NULL default '1',
				  `type` varchar(30) NOT NULL default '1',
				  `location` varchar(255) NOT NULL,
				  `target` tinyint(1) NOT NULL default '1',
				  `articleId` int(10) unsigned NOT NULL,
				  `location2` varchar(200) NOT NULL,
				  `menu_type` varchar(100) NOT NULL,
				  `loc_id` int(11) NOT NULL,
				  `location1` varchar(255) NOT NULL,
				  `catid` int(10) NOT NULL default '1',
				  `other_phrases` tinyint(4) NOT NULL DEFAULT '0',
				  PRIMARY KEY  (`id`)
				)";
			
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-22-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_keys` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
			  `rank` int(11) NOT NULL,
			  `rchange` int(10) unsigned NOT NULL,
			  `mode` tinyint(1) NOT NULL DEFAULT '-1',
			  `checkdate` datetime NOT NULL,
			  `sticky` tinyint(3) unsigned NOT NULL DEFAULT '0',
			  PRIMARY KEY (`id`)
			)";
			
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-01-".$db->getErrorMsg();
		}	
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_keys_id` (
				  `keyword` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				  `type` varchar(255) NOT NULL,
				  `type_id` int(10) unsigned NOT NULL
				)";
				
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-01-".$db->getErrorMsg();
		}	
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_titlekeys` (
				  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) NOT NULL,
				  `rank` int(11) NOT NULL,
				  `rchange` int(10) unsigned NOT NULL,
				  `mode` tinyint(1) NOT NULL DEFAULT '-1',
				  `checkdate` datetime NOT NULL,
				  `sticky` tinyint(3) unsigned NOT NULL DEFAULT '0',
				  `type` varchar(255) NOT NULL,
				  `joomla_id` int(10) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
				
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-011-".$db->getErrorMsg();
		}
		
		$sql = "CREATE TABLE IF NOT EXISTS `#__ijseo_metags` (
				  `mtype` varchar(255) NOT NULL,
				  `id` int(11) NOT NULL,
				  `name` varchar(255) NOT NULL,
				  `titletag` varchar(255) NOT NULL,
				  `metakey` text NOT NULL,
				  `metadesc` text NOT NULL
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
				
		$db->setQuery($sql);		 
		if(!$db->query()){
			echo "error-0111-".$db->getErrorMsg();
		}
				
		$sql = "select count(*) from #__ijseo_ilinks_category";
		$db->setQuery($sql);
		if(!$db->query()){
			echo "error-3-".$db->getErrorMsg();
		}
		$result = $db->loadColumn();
		$result = $result["0"];
		
		if(intval($result)==0){
			$sql = "INSERT INTO `#__ijseo_ilinks_category` (`name`,`published`) VALUES ('General', '1')";
			$db->setQuery($sql);
			if(!$db->query()){
				echo "error-4-".$db->getErrorMsg();
			}
		}
		
		$sql = "SHOW columns FROM `#__ijseo_ilinks`";
		$db->setQuery($sql);
		$cols_ilinks = $db->loadColumn();
		
		if (!in_array("title", $cols_ilinks)) {
			$sql = "ALTER TABLE `#__ijseo_ilinks` ADD `title` VARCHAR( 255 ) NOT NULL ";
			$db->setQuery($sql);
			if (!$db->query()) {
				echo "error-4222-".$db->getErrorMsg();
			}
		}    
		if (!in_array("include_in", $cols_ilinks)) {
			$sql = "ALTER TABLE `#__ijseo_ilinks` ADD `include_in` TINYINT( 1 ) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			if (!$db->query()) {
				echo "error-42223-".$db->getErrorMsg();
			}
		}
		if (!in_array("activate_for_some", $cols_ilinks)) {
			$sql = "ALTER TABLE `#__ijseo_ilinks` ADD `activate_for_some` TINYINT( 1 ) NOT NULL DEFAULT '0'";
			$db->setQuery($sql);
			if (!$db->query()) {
				echo "error-42224-".$db->getErrorMsg();
			}
		}
		
		$sql = "select count(*) from #__ijseo_redirect_category";
		$db->setQuery($sql);
		if(!$db->query()){
			echo "error-33-".$db->getErrorMsg();
		}
		$result = $db->loadColumn();
		$result = $result["0"];
		
		if(intval($result)==0){
			$sql = "INSERT INTO `#__ijseo_redirect_category` (`name`,`published`) VALUES ('General', '1')";
			$db->setQuery($sql);
			if(!$db->query()){
				echo "error-44-".$db->getErrorMsg();
			}
		}
		
		$sql = "select element from #__extensions where element='ijseo_plugin'";
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		$name = @$name["0"];
	
		if (empty($name)){
			$query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('ijseo_plugin', 'plugin', 'ijseo_plugin', 'content', 0, 1, 1, 0, '{\"legacy\":false,\"name\":\"ijseo_plugin\",\"type\":\"plugin\",\"creationDate\":\"01 June 2012\",\"author\":\"iJoomla\",\"copyright\":\"(C) 2010 iJoomla.com\",\"authorEmail\":\"webmaster2@ijoomla.com\",\"authorUrl\":\"www.iJoomla.com\",\"version\":\"2.0.6\",\"description\":\"This is the iJoomla SEO plugin, make sure it`s always published in order to display the metatags on your site.\",\"group\":\"\"}', '', '', '', 0, '0000-00-00 00:00:00', -10000, 0)";
			$db->setQuery($query);
			$db->query();
		}
	
		$sql = "select element from #__extensions where element='ijseo'";
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		$name = @$name["0"];
	
		if (empty($name)){
		   $query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('System - iJSEO', 'plugin', 'ijseo', 'system', 0, 1, 1, 0, '{\"legacy\":false,\"name\":\"System - iJSEO\",\"type\":\"plugin\",\"creationDate\":\"01 June 2012\",\"author\":\"iJoomla\",\"copyright\":\"(C) 2010 iJoomla.com\",\"authorEmail\":\"webmaster2@ijoomla.com\",\"authorUrl\":\"www.iJoomla.com\",\"version\":\"2.0.6\",\"description\":\"This plugin displays tabs with news and change log of your iJoomla extension, right next to the CPanel of each extension.\",\"group\":\"\"}', '', '', '', 0, '0000-00-00 00:00:00' , -10200, 0)";
			$db->setQuery($query);
			$db->query();
		}
		
		$sql = "select element from #__extensions where element='ijoomlanews'";
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		$name = $name["0"];
	
		if (empty($name)){
		   $query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('iJoomla News', 'plugin', 'ijoomlanews', 'system', 0, 1, 1, 0, '{\"legacy\":false,\"name\":\"iJoomla News\",\"type\":\"plugin\",\"creationDate\":\"01 June 2012\",\"author\":\"iJoomla\",\"copyright\":\"(C) 2010 iJoomla.com\",\"authorEmail\":\"webmaster2@ijoomla.com\",\"authorUrl\":\"www.iJoomla.com\",\"version\":\"1.0\",\"description\":\"This plugin displays tabs with news and change log of your iJoomla extension, right next to the CPanel of each extension.\",\"group\":\"\"}', '{\"nr_articles\":\"5\",\"text_length\":\"500\",\"image_width\":\"50\"}', '', '', 0, '0000-00-00 00:00:00' , -10300, 0)";
			$db->setQuery($query);
			$db->query();
		}
		
		$sql = "select element from #__extensions where element='ijoomlaupdate'";
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();
		$name = $name["0"];
	
		if (empty($name)){
		   $query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('iJoomla Upgrade Alert', 'plugin', 'ijoomlaupdate', 'system', 0, 1, 1, 0, '{\"legacy\":false,\"name\":\"iJoomla Upgrade Alert\",\"type\":\"plugin\",\"creationDate\":\"01 June 2012\",\"author\":\"iJoomla\",\"copyright\":\"(C) 2010 iJoomla.com\",\"authorEmail\":\"webmaster2@ijoomla.com\",\"authorUrl\":\"www.iJoomla.com\",\"version\":\"1.0\",\"description\":\"This plugin alerts you when an iJoomla extension is out of date.\",\"group\":\"\"}', '{\"lastcheck\":\"0\"}', '', '', 0, '0000-00-00 00:00:00' , -10300, 0)";
			$db->setQuery($query);
			$db->query();
		}
		
		$sql = "SELECT `name` FROM #__ijseo_ilinks LIMIT 1";
		$db->setQuery($sql);
		$any_ilinks = $db->loadColumn();
		$any_ilinks = @$any_ilinks["0"];
		
		if (!$any_ilinks) {
			$sql = "INSERT INTO `#__ijseo_ilinks` (`id`, `name`, `published`, `type`, `location`, `target`, `articleId`, `location2`, `menu_type`, `loc_id`, `location1`, `catid`, `other_phrases`, `title`) VALUES
						(2, 'joomla', 1, '3', '', 2, 0, 'http://www.ijoomla.com/', '', 0, '', 1, 1, 'joomla'),
						(3, 'templates', 1, '3', '', 2, 0, 'http://templates.ijoomla.com/', '', 0, '', 1, 0, 'joomla templates'),
						(4, 'modules', 1, '3', '', 2, 0, 'http://www.ijoomla.com/', '', 0, '', 1, 0, 'joomla module'),
						(5, 'SEF', 1, '3', '', 2, 0, 'http://seo.ijoomla.com/', '', 0, '', 1, 0, 'joomla seo'),
						(6, 'Components', 1, '3', '', 2, 0, 'http://www.ijoomla.com/', '', 0, '', 1, 0, 'joomla components'),
						(7, 'extensions', 1, '3', '', 2, 0, 'http://www.ijoomla.com/', '', 0, '', 1, 0, 'joomla extensions');";
			$db->setQuery($sql);
			$db->query();
		}
		
		//install plugin
		$component_dir = JPATH_SITE.'/administrator/components/com_ijoomla_seo/plugins';
		$content_dir   = JPATH_SITE.'/plugins/content/ijseo_plugin';
		$plugin_dir    = JPATH_SITE.'/plugins/system/ijseo';	
		
		if(!is_dir($content_dir)){
			mkdir($content_dir, 0777);
		}
		
		if(!is_dir($plugin_dir)){
			mkdir($plugin_dir, 0777);
		}
		
		$plugin_main   = 'ijseo_plugin.php';
		$plugin_xml    = 'ijseo_plugin.xml';
	
		$ijseo		   = 'ijseo.php';
		$ijseo_xml 	   = 'ijseo.xml';
		
		if(!copy($component_dir."/".$plugin_main, $content_dir."/".$plugin_main)){
			echo 'Error copying ijseo_plugin.php';
		}
	
		if(!copy($component_dir."/".$plugin_xml, $content_dir."/".$plugin_xml)){
			echo 'Error copying ijseo_plugin.xml';
		}
	
		if(!copy($component_dir."/".$ijseo, $plugin_dir."/".$ijseo)){
			echo 'Error copying ijseo_plugin.php';
		}
	
		if(!copy($component_dir."/".$ijseo_xml, $plugin_dir."/".$ijseo_xml)){
			echo 'Error copying ijseo_plugin.xml';
		}	
		
		if(!unlink($component_dir.'/'.$plugin_main)){
			echo 'Cannot delete '.$component_dir.'/'.$plugin_main."<br/>";
		}	
	
		if(!unlink($component_dir.'/'.$plugin_xml)){
			echo 'Cannot delete '.$component_dir.'/'.$plugin_xml."<br/>";
		}	
	
		if(!unlink($component_dir.'/'.$ijseo)){
			echo 'Cannot delete '.$component_dir.'/'.$ijseo."<br/>";
		}	
	
		if(!unlink($component_dir.'/'.$ijseo_xml)){
			echo 'Cannot delete '.$component_dir.'/'.$ijseo_xml."<br/>";
		}
		
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__extensions where element='ijoomlanews'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = $count["0"];

		$component_dir = JPATH_SITE.'/administrator/components/com_ijoomla_seo/plugins';

		if($count == 0){
		   $query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('iJoomla News', 'plugin', 'ijoomlanews', 'system', 0, 1, 1, 0, '', '{\"nr_articles\":\"3\",\"text_length\":\"100\",\"image_width\":\"50\"}', '', '', 0, '0000-00-00 00:00:00' , -10300, 0)";
			$db->setQuery($query);
			$db->query();
		}

		$sql = "select element from #__extensions where element='ijoomlaupdate'";
		$db->setQuery($sql);
		$db->query();
		$name = $db->loadColumn();

		if (empty($name["0"])){
		   $query = "INSERT INTO #__extensions (name,type,element,folder,client_id,enabled,access,protected,manifest_cache,params,custom_data,system_data,checked_out, 	checked_out_time,ordering,state)"
			."\n VALUES ('iJoomla Upgrade Alert', 'plugin', 'ijoomlaupdate', 'system', 0, 1, 1, 0, '', '{\"lastcheck\":\"0\"}', '', '', 0, '0000-00-00 00:00:00' , -10300, 0)";
			$db->setQuery($query);
			$db->query();
		}

		//----------------------------------------start news plugin
		$news_dir = JPATH_SITE.'/plugins/system/ijoomlanews';
		if(!is_dir($news_dir)){
			mkdir($news_dir, 0755);
		}
		$news_php = 'ijoomlanews.php';
		$news_xml = 'ijoomlanews.xml';
		$news_folder = 'ijoomlanews';
		@chmod($news_dir, 0755);
		if(!copy($component_dir."/ijoomlanews/".$news_xml, $news_dir."/".$news_xml)){
			echo 'Error copying ijoomlanews.xml'."<br/>";
		}
		if(!copy($component_dir."/ijoomlanews/".$news_php, $news_dir."/".$news_php)){
			echo 'Error copying ijoomlanews.php'."<br/>";
		}
		if(!is_dir($news_dir."/".$news_folder)){
			mkdir($news_dir."/".$news_folder, 0755);
		}
		if(!copy($component_dir."/ijoomlanews/".$news_folder."/feed.php", $news_dir."/".$news_folder."/feed.php")){
			echo 'Error copying feed.php'."<br/>";
		}
		if(!copy($component_dir."/ijoomlanews/".$news_folder."/tabs.php", $news_dir."/".$news_folder."/tabs.php")){
			echo 'Error copying tabs.php'."<br/>";
		}
		if(!copy($component_dir."/ijoomlanews/".$news_folder."/index.html", $news_dir."/".$news_folder."/index.html")){
			echo 'Error copying index.html'."<br/>";
		}
		
		if(!unlink($component_dir.'/ijoomlanews/'.$news_php)){
			echo 'Cannot delete '.$component_dir.'/ijoomlanews/'.$news_php."<br/>";
		}
		if(!unlink($component_dir.'/ijoomlanews/'.$news_xml)){
			echo 'Cannot delete '.$component_dir.'/ijoomlanews/'.$news_xml."<br/>";
		}
		//----------------------------------------stop news plugin

		//----------------------------------------start upgrade plugin
		$update_dir = JPATH_SITE.'/plugins/system/ijoomlaupdate';
		if(!is_dir($update_dir)){
			mkdir($update_dir, 0755);
		}
		$update_php = 'ijoomlaupdate.php';
		$update_xml = 'ijoomlaupdate.xml';
		$update_folder = 'ijoomlaupdate';
		@chmod($update_dir, 0755);
		if(!copy($component_dir."/ijoomlaupdate/".$update_xml, $update_dir."/".$update_xml)){
			echo 'Error copying ijoomlaupdate.xml'."<br/>";
		}
		if(!copy($component_dir."/ijoomlaupdate/".$update_php, $update_dir."/".$update_php)){
			echo 'Error copying ijoomlaupdate.php'."<br/>";
		}
		if(!is_dir($update_dir."/".$update_folder)){
			mkdir($update_dir."/".$update_folder, 0755);
		}
		if(!copy($component_dir."/ijoomlaupdate/".$update_folder."/editversions.php", $update_dir."/".$update_folder."/editversions.php")){
			echo 'Error copying editversions.php'."<br/>";
		}
		if(!copy($component_dir."/ijoomlaupdate/".$update_folder."/ijoomla.gif", $update_dir."/".$update_folder."/ijoomla.gif")){
			echo 'Error copying ijoomla.gif'."<br/>";
		}
		if(!copy($component_dir."/ijoomlaupdate/".$update_folder."/logo.png", $update_dir."/".$update_folder."/logo.png")){
			echo 'Error copying logo.png'."<br/>";
		}
		if(!copy($component_dir."/ijoomlaupdate/".$update_folder."/index.html", $update_dir."/".$update_folder."/index.html")){
			echo 'Error copying index.html'."<br/>";
		}
		@chmod($component_dir.'/ijoomlaupdate/'.$update_php, 0755);
		@chmod($component_dir.'/ijoomlaupdate/'.$update_xml, 0755);
		@chmod($component_dir.'/ijoomlaupdate/'.$update_folder, 0755);
		if(!unlink($component_dir.'/ijoomlaupdate/'.$update_php)){
			echo 'Cannot delete '.$component_dir.'/ijoomlaupdate/'.$update_php."<br/>";
		}
		if(!unlink($component_dir.'/ijoomlaupdate/'.$update_xml)){
			echo 'Cannot delete '.$component_dir.'/ijoomlaupdate/'.$update_xml."<br/>";
		}
		//----------------------------------------stop upgrade plugin
			
		$this->create_folder();
				
		//set default delimiters if none set
		$sql = "select params from #__ijseo_config";
		$db->setQuery($sql);
		if($db->query()){
			$result = $db->loadColumn();
			$result = @$result["0"];
			
			if(trim($result) != "" && trim($result) != "{}"){
				$result = json_decode($result, true);
				if(!isset($result["delimiters"])){
					$result["delimiters"] = ",|;:";
					$sql = "update #__ijseo_config set `params` = '".json_encode($result)."'";
					$db->setQuery($sql);			
					$db->query();	
				}
			}
		}
	}

	function create_folder(){
		$folder = JPATH_SITE.DS.'images'.DS.'ijseo_redirects';
		if (!is_dir($folder) && !is_file($folder)){
			jimport('joomla.filesystem.*');
			JFolder::create($folder);
			$path = $folder.DS."index.html";
			$content = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
			JFile::write($path, $content);
		}
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent){
		// create lang and config backups
		$path_backup = JPATH_SITE."/components/ijoomla_seo_backup";     
		$lang_source = JPATH_SITE."/administrator/language/en-GB";
		$lang = "/en-GB.com_ijoomla_seo.ini";
		if(!is_dir($path_backup)){ 
			mkdir($path_backup,0777);
		}	
		@JFolder::copy($lang_source.$lang, $path_backup.$lang);
		
		$this->remove_plugin();
		echo "Component successfully uninstalled.";
	}
	
	function remove_plugin () {
		$database = JFactory::getDBO();
	
		$mosConfig_absolute_path = JPATH_ROOT;
		$query = "delete from #__extensions where element='ijseo_plugin'";
		$database->setQuery($query);
		$database->query();
		$query = "delete from #__extensions where element='ijseo'";
		$database->setQuery($query);
		$database->query();
			
		@unlink($mosConfig_absolute_path."/plugins/content/ijseo_plugin/ijseo_plugin.php");
		@unlink($mosConfig_absolute_path."/plugins/content/ijseo_plugin/ijseo_plugin.xml");
		@unlink($mosConfig_absolute_path."/plugins/system/ijseo/ijseo.php");
		@unlink($mosConfig_absolute_path."/plugins/system/ijseo/ijseo.xml");	
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		$this->install();
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_ALTACOACH_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//echo '<p>' . JText::_('COM_ALTACOACH_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
		
		echo '<script type="text/jscript" language="javascript">
				window.location.href = "'.JURI::root().'administrator/index.php?option=com_ijoomla_seo&installer=1";
			</script>';
	}
}