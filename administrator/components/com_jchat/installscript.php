<?php
//namespace administrator\components\com_jchat;
/**
 * Application install script
 * @package JCHAT::INSTALL::administrator::components::com_jchat 
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html    
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/** 
 * Application install script class
 * @package FBChat::administrator::components::com_jchat  
 */
class com_jchatInstallerScript {
	/*
	 * The release value to be displayed and checked against throughout this file.
	 */
	private $release = '1.0';
	
	/*
	* Find mimimum required joomla version for this extension. It will be read from the version attribute (install tag) in the manifest file
	*/
	private $minimum_joomla_release = '1.6.0';
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	 * If preflight returns false, Joomla will abort the update and undo everything already done.
	 */
	function preflight($type, $parent) {
	
	}
	
	/*
	 * $parent is the class calling this method.
	 * install runs after the database scripts are executed.
	 * If the extension is new, the install method is run.
	 * If install returns false, Joomla will abort the install and undo everything already done.
	 */
	function install($parent) {
		$database = JFactory::getDBO ();
		$lang = JFactory::getLanguage ();
		$lang->load ( 'com_jchat' );
		
		// Component installer
		$componentInstaller = JInstaller::getInstance ();
		$pathToPlugin = $componentInstaller->getPath ( 'source' ) . '/plugin';
		
		// New plugin installer
		$pluginInstaller = new JInstaller ();
		if (! $pluginInstaller->install ( $pathToPlugin )) {
			echo '<p>' . JText::_ ( 'ERROR_INSTALLING_PLUGINS' ) . '</p>';
			// Install failed, rollback changes
			$parentParent->abort(JText::_('ERROR_INSTALLING_PLUGINS'));
			return false;
		} else {
			$query = "UPDATE #__extensions" . "\n SET enabled = 1" . 
					 "\n WHERE type = 'plugin' AND element = " . $database->quote ( 'jchat' ) . 
					 "\n AND folder = " . $database->quote ( 'system' );
			$database->setQuery ( $query );
			if (! $database->execute ()) {
				echo '<p>' . JText::_ ( 'ERROR_PUBLISHING_PLUGIN' ) . '</p>';
			}
		}
		
		return true;
	}
	
	/*
	 * $parent is the class calling this method.
	 * update runs after the database scripts are executed.
	 * If the extension exists, then the update method is run.
	 * If this returns false, Joomla will abort the update and undo everything already done.
	 */
	function update($parent) {
		// Execute always sql install file to get added updates in that file
		$parentParent = $parent->getParent();
		$parentManifest = $parentParent->getManifest();
		if (isset($parentManifest->install->sql)) {
			$utfresult = $parentParent->parseSQLFiles($parentManifest->install->sql);
			if ($utfresult === false) {
				// Install failed, rollback changes
				$parentParent->abort(JText::_('UPDATE_ERROR'));
		
				return false;
			}
		}
		
		$this->install($parent);
	}
	
	/*
	 * $parent is the class calling this method.
	 * $type is the type of change (install, update or discover_install, not uninstall).
	 * postflight is run after the extension is registered in the database.
	 */
	function postflight($type, $parent) { 
		// define the following parameters only if it is an original install
		if ($type == 'install') {  
			// Preferences
			$params ['chatrefresh'] = '2';
			$params ['lastmessagetime'] = '60';
			$params ['maxinactivitytime'] = '60';
			$params ['forceavailable'] = '0';
			$params ['usefullname'] = 'username';
			$params ['start_open_mode'] = '1';
			$params ['chatboxes_open_mode'] = '0';
			$params ['includejquery'] = '1';
			$params ['noconflict'] = '1';
			$params ['includeevent'] = 'afterdispatch';
			$params ['3pdintegration'] = '';
			$params ['skypebridge'] = '1';
			$params ['groupchat'] = '1';
			
			// File system
			$params ['avatar_allowed_extensions'] = 'jpg,jpeg,png,gif';
			$params ['cropmode'] = '0';
			$params ['avatarupload'] = '1';
			$params ['avatardisable'] = '0';  
			$params ['maxfilesize'] = '2';
			$params ['disallowed_extensions'] = 'exe,bat,pif';
			$params ['guestenabled'] = '0';
			$params ['guestprefix'] = 'Guest';
			$params ['chat_title'] = 'Chat';
			$params ['maxtimeinterval_groupmessages'] = '12';
			
			// Notifications
			$params ['offline_message_switcher'] = '0';
			$params ['offline_message'] = '';
			$params ['notification_email_switcher'] = '0';
			$params ['notification_email'] = '';
			$params ['email_subject'] = 'JChatSocial - New conversation started';
			$params ['email_start_text'] = '';
			
			// Permissions
			$params ['allow_guest_fileupload'] = '1';
			$params ['allow_guest_avatarupload'] = '1';
			$params ['allow_guest_skypebridge'] = '1';
			$params ['chatadmins_gids'] = array('0');
			$params ['chat_exclusions'] = array('0');
			
			$this->setParams ( $params );  
		} 
	}
	
	/*
	 * $parent is the class calling this method
	 * uninstall runs before any other action is taken (file removal or database processing).
	 */
	function uninstall($parent) {
		$database = JFactory::getDBO ();
		$lang = JFactory::getLanguage();
		$lang->load('com_jchat');
		 
		// Check if plugin exists
		$query = "SELECT extension_id" .
				 "\n FROM #__extensions" .
				 "\n WHERE type = 'plugin' AND element = " . $database->quote('jchat') .
				 "\n AND folder = " . $database->quote('system');
		$database->setQuery($query);
		$pluginID = $database->loadResult();
		if(!$pluginID) {
			echo '<p>' . JText::_('PLUGIN_ALREADY_REMOVED') . '</p>';
		} else {
			// New plugin installer
			$pluginInstaller = new JInstaller ();
			if(!$pluginInstaller->uninstall('plugin', $pluginID)) {
				echo '<p>' . JText::_('ERROR_UNINSTALLING_PLUGINS') . '</p>';
			} 
		}
		
		// Uninstall complete
		return true;
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam($name) {
		$db = JFactory::getDbo ();
		$db->setQuery ( 'SELECT manifest_cache FROM #__extensions WHERE name = "jchat"' );
		$manifest = json_decode ( $db->loadResult (), true );
		return $manifest [$name];
	}
	
	/*
	 * sets parameter values in the component's row of the extension table
	 */
	function setParams($param_array) {
		if (count ( $param_array ) > 0) { 
			$db = JFactory::getDbo (); 
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode ( $param_array );
			$db->setQuery ( 'UPDATE #__extensions SET params = ' . $db->quote ( $paramsString ) . ' WHERE name = "jchat"' );
			$db->execute ();
		}
	}
}