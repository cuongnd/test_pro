<?php
// namespace administrator\components\com_jchat\models;
/**
 *
 * @package JCHAT::CPANEL::administrator::components::com_jchat
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.model' );
 
/**
 * Messages model responsibilities contract
 *
 * @package JCHAT::MESSAGES::administrator::components::com_jchat
 * @subpackage models
 * @since 1.0
 */
interface ICPanelModel {
	/**
	 * Delete from file system all obsolete exchanged files
	 * @access public
	 * @return boolean
	 */
	public function purgeFileCache();
	
	/**
	 * Counter result set
	 * @access public
	 * @return int
	 */
	public function getTotalUsers();
	
	/**
	 * Counter result set
	 * @access public
	 * @return int
	 */
	public function getTotalLoggedUsers();
}
/**
 * CPanel model concrete implementation
 *
 * @package JCHAT::CPANEL::administrator::components::com_jchat
 * @subpackage models
 * @since 1.0
 */
class JChatModelCpanel extends JModelLegacy {
	/**
	 * Component configuration params
	 * @access private
	 * @var Object
	 */
	private $cParams = null;
	
	/**
	 * Delete from file system all obsolete exchanged files
	 * @access public
	 * @return boolean
	 */
	 public function purgeFileCache() {
	 	// Garbage files cache folder
	 	try {
	 		if(is_dir($this->cParams->get('cacheFolder'))) {
	 			$filenames = array();
	 			if(class_exists('DirectoryIterator', false)) {
	 				// Clear exchanged attachment cache
	 				$iterator = new DirectoryIterator($this->cParams->get('cacheFolder'));
	 				foreach ($iterator as $fileinfo) {
	 					if ($fileinfo->isFile() && $fileinfo->getFilename() != 'index.html') {
 							unlink($fileinfo->getRealPath());
	 					}
	 				}
	 				
	 				// Clear avatars cache
	 				$iterator = new DirectoryIterator($this->cParams->get('avatarFolder'));
	 				foreach ($iterator as $fileinfo) {
	 					if ($fileinfo->isFile() && $fileinfo->getFilename() != 'index.html' && strpos($fileinfo->getFilename(), 'gsid') === 0) {
 							unlink($fileinfo->getRealPath());
	 					}
	 				}
	 			} else {
	 				throw new Exception(JText::_('NO_SPL_SUPPORT'));
	 			}
	 		} else {
	 			throw new Exception(JText::_('INVALID_CACHE_PATH'));
	 		}
	 	} catch (Exception $e) {
	 		JError::raiseNotice(100, $e->getMessage());
	 		return false;
	 	}
	 	return true;
	 }
	 
	 /**
	  * Counter result set
	  * @access public
	  * @return int
	  */
	 public function getTotalUsers() {
	 	$query = "SELECT COUNT(*) FROM #__users" .
	 	 		 "\n WHERE " . $this->_db->qn('block') . " = 0";
	 	$this->_db->setQuery($query);
	 	$numValidUsers = $this->_db->loadResult();

	 	return $numValidUsers;
	 }
	 
	 /**
	  * Counter result set
	  * @access public
	  * @return int
	  */
	 public function getTotalLoggedUsers() {
	 	$query = "SELECT COUNT(*) FROM #__users AS u" .
	 	 		 "\n INNER JOIN #__session AS sess" .
	 	 		 "\n ON sess.userid = u.id" .
 				 "\n WHERE u.block = 0" .
 				 "\n AND sess.guest = 0" .
	 			 "\n AND sess.client_id = 0";
	 	$this->_db->setQuery($query);
	 	$numValidUsers = $this->_db->loadResult();
	 	
	 	return $numValidUsers;
	 }
	 

	 /**
	  * Class constructor
	  * @access public
	  * @param array $config
	  * @return Object&
	  */
	 public function __construct($config = array()) {
	 	// Parent constructor
	 	parent::__construct($config);
	 	
	 	$this->cParams = JComponentHelper::getParams('com_jchat');
	 	$this->cParams->set('cacheFolder', JPATH_COMPONENT_SITE . '/cache/');
	 	$this->cParams->set('avatarFolder', JPATH_COMPONENT_SITE . '/images/avatars/');
	 }
	 
}