<?php
//namespace administrator\components\com_jrealtimeanalytics;
/**  
 * @package JREALTIMEANALYTICS::administrator::components::com_jrealtimeanalytics 
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
  
/** 
 * Script per i processi di install/update/uninstall del componente. Segue una convenzione di classe
 * @package JREALTIMEANALYTICS::administrator::components::com_jrealtimeanalytics  
 */
class com_jrealtimeanalyticsInstallerScript {
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
		$lang->load ( 'com_jrealtimeanalytics' );
		
		// Utilizzo dell'installer direttamente da qui per il plugin
		$componentInstaller = &JInstaller::getInstance ();
		$pathToPlugin = $componentInstaller->getPath ( 'source' ) . '/plugin';
		
		// Controllo esistenza del plugin
		$query = "SELECT COUNT(*)" . "\n FROM #__extensions" .
				 "\n WHERE type = 'plugin' AND element = " . $database->Quote ( 'jrealtimeanalytics' ) .
				 "\n AND folder = " . $database->Quote ( 'system' );
		$database->setQuery ( $query );
		$pluginInstalled = ( bool ) $database->loadResult ();
		if ($pluginInstalled) {
			echo '<p>' . JText::_ ( 'PLUGIN_ALREADY_INSTALLED' ) . '</p>';
		} else {
			// Si necessita una nuova istanza dell'installer per il plugin
			$pluginInstaller = new JInstaller ();
			if (! $pluginInstaller->install ( $pathToPlugin )) {
				echo '<p>' . JText::_ ( 'ERROR_INSTALLING_PLUGINS' ) . '</p>';
			} else {
				$query = "UPDATE #__extensions" . "\n SET enabled = 1" . 
						 "\n WHERE type = 'plugin' AND element = " . $database->Quote ( 'jrealtimeanalytics' ) . 
						 "\n AND folder = " . $database->Quote ( 'system' );
				$database->setQuery ( $query );
				if (! $database->execute ()) {
					echo '<p>' . JText::_ ( 'ERROR_PUBLISHING_PLUGIN' ) . '</p>';
				}
			}
		} 
	}
	
	/*
	 * $parent is the class calling this method.
	 * update runs after the database scripts are executed.
	 * If the extension exists, then the update method is run.
	 * If this returns false, Joomla will abort the update and undo everything already done.
	 */
	function update($parent) {
		// Indifferentemente gestionamo l'installazione del plugin
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
			$params ['chatrefresh'] = '2';
			$params ['realtime_refresh'] = '2';
			$params ['maxlifetime_session'] = '5';
			$params ['guestprefix'] = 'Guest';
			$params ['gcenabled'] = '1'; 
 			$params ['probability'] = '5';
			$params ['noconflict'] = '1';
			$params ['includeevent'] = 'afterdispatch';
			$params ['includejquery'] = '1';
			$params ['cbnoconflict'] = '1';
			
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
		$lang->load('com_jrealtimeanalytics');
		 
		// Controllo esistenza del plugin
		$query = "SELECT extension_id" .
				 "\n FROM #__extensions" .
				 "\n WHERE type = 'plugin' AND element = " . $database->Quote('jrealtimeanalytics') .
				 "\n AND folder = " . $database->Quote('system');
		$database->setQuery($query);
		$pluginID = $database->loadResult();
		if(!$pluginID) {
			echo '<p>' . JText::_('PLUGIN_ALREADY_REMOVED') . '</p>';
		} else {
			// Si necessita una nuova istanza dell'installer per il plugin
			$pluginInstaller = new JInstaller ();
			if(!$pluginInstaller->uninstall('plugin', $pluginID)) {
				echo '<p>' . JText::_('ERROR_UNINSTALLING_PLUGINS') . '</p>';
			} 
		}
		
		// Processing completo
		return true;
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	 */
	function getParam($name) {
		$db = JFactory::getDbo ();
		$db->setQuery ( 'SELECT manifest_cache FROM #__extensions WHERE name = "jrealtimeanalytics"' );
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
			$db->setQuery ( 'UPDATE #__extensions SET params = ' . $db->quote ( $paramsString ) . ' WHERE name = "jrealtimeanalytics"' );
			$db->execute ();
		}
	}
}