<?php
//namespace components\com_jchat\models; 
/** 
 * @package JCHAT::components::com_jchat
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Avatar manager
 * @package JCHAT::components::com_jchat
 * @subpackage models
 * @since 1.0
 */ 
class JChatAvatar {
	/**
	 * DB object connector
	 * @access private
	 * @var Object
	 */
	private $DBO;
	
	/**
	 * Site URL
	 * @access private
	 * @var string
	 */
	private $siteurl;
	
	/**
	 * Plugin configuration object
	 * @access private
	 * @var Object
	 */
	private $config;
	
	/**
	 * User identifier here based on session id
	 * @access private
	 * @var int
	 */
	private $sessionId;
	
	/**
	 * User identifier here based on user id
	 * @access private
	 * @var int
	 */
	private $userId;
	
	/**
	 * @access private
	 * @var string
	 */
	private $userName;
	
	/**
	 * @access private
	 * @var string
	 */
	private $avatarFolder;
	
	/**
	 * @access private
	 * @var string
	 */
	private $noAvatarImgName;

	/**
	 * @access private
	 * @static
	 * @var string
	 */
	private static $avatarFormat = 'png';
	
	/**
	 * @access private
	 * @static
	 * @var string
	 */
	private static $avatarSubPath = '/images/avatars/';
	  
	/**
	 * @access private 
	 * @param string $extension2Append
	 * @return array Il path completo e soltanto il filename al file thumb avatar creato o da creare
	 */
	private function createAvatarThumbnailFileName($extension2Append = 'png') {
		// Calculate in base all'md5 di id utente e username
		$calculatedHash = $this->userId ? 'uidavatar_' . $this->userId : 'gsidavatar_' . $this->sessionId;
		$finalName = $calculatedHash . '.' . $extension2Append;
		$completePathName = $this->avatarFolder . '/' . $finalName;
		$liveUrl = $this->siteurl . '/images/avatars';
		 
		return array($completePathName, $finalName, $liveUrl);
	}
	
	/**
	 * @access private
	 * @param string $originalFilename
	 * @param string $thumbFilename
	 * @return boolean 
	 */
	private function createThumbnail($originalFilename, $thumbFilename) { 
		$thumb = PhpThumbFactory::create($originalFilename);
		// Selezione modo di resizing
		switch ($this->config->get('cropmode')){
			case '0':
				$thumb->resize(50, 50);
				break;
				
			case '1':
				$thumb->adaptiveResize(50, 50);
				break;
		}
		
		$thumb->save($thumbFilename, 'png');
	}
	
	/**
	 * Cross system filemtime no bugged
	 * @access private
	 * @param string $filePath
	 * @return int
	 */
	private static function crossFileMTime($filePath) {
		$time = filemtime($filePath);
	
		$isDST = (date('I', $time) == 1);
		$systemDST = (date('I') == 1);
	
		$adjustment = 0;
	
		if($isDST == false && $systemDST == true)
			$adjustment = 3600;
	
		else if($isDST == true && $systemDST == false)
			$adjustment = -3600;
	
		else
			$adjustment = 0;
	
		return ($time + $adjustment);
	}
	
	/**
	 * Singleton for session object
	 * @static
	 *
	 * @access private
	 * @return Object
	 */
	private static function getTableSession() {
		static $sessionTable;
	
		if(!is_object($sessionTable)) {
			$sessionTable = JTable::getInstance('session');
		}
	
		return $sessionTable;
	}
	

	/**
	 * @access public
	 * @param string $msg Eventuale messaggio informativo utente
	 * @return void 
	 */
	public function showForms($msg = null) {
		$translations = array('upload'=>JTEXT::_('JCHAT_UPLOAD'), 
							  'user_avatar'=>JText::_('JCHAT_USER_AVATAR'),
							  'avatar_delete'=>JText::_('JCHAT_AVATAR_DELETE'),
							  'upload_newavatar'=>JText::_('JCHAT_UPLOAD_NEWAVATAR'));
		// Controlla se l'avatar � stato caricato per l'utente corrente
		$avatar = $this->createAvatarThumbnailFileName();
		$avatarDeleteButton = null;
		if(file_exists($avatar[0])) {
			$userAvatar = $avatar[1] .'?nocache='.time();
			$avatarDeleteButton = '<input id="avatar_delete" type="submit" value="" /><label class="buttonlabel">' . $translations['avatar_delete'] . '</label>';
		} else {
			$userAvatar = $this->noAvatarImgName .'?nocache='.time();
		}
		
				
		$includes = <<<INCLUDES
		<script type="text/javascript" src="{$this->siteurl}/js/jquery.js"></script>
		<script type="text/javascript">
			jQuery(function(){jQuery('div.closemsgbtn').on('click', function(){jQuery(this).parent().css('display', 'none')});})
		</script>
		<link type="text/css" rel="stylesheet" media="all" href="{$this->siteurl}/css/mainstyle.css" />
		<!--[if lt IE 9]>
			<style type="text/css">
				div.up {
					margin-top: 0px; 
				}
			</style>
		<![endif]-->
INCLUDES;

		$deleteForm = <<<DELETEFORM
		<form name="deleteform" enctype="multipart/form-data" id="deleteform" method="post" action="index.php">
			    <img class="avatar_img" src="{$this->siteurl}/images/avatars/$userAvatar" alt="avatar" />
				<div class="formbutton up">
					$avatarDeleteButton
					<input type="hidden" name="option" value="com_jchat" />
					<input type="hidden" name="controller" value="avatar" />
					<input type="hidden" name="task" value="deleteAvatar" />
					<input type="hidden" name="format" value="raw" />
				</div>
		</form>
DELETEFORM;
		
		$uploadForm = <<<UPLOADFORM
		<form name="uploadform" enctype="multipart/form-data" id="uploadform" method="post" action="index.php">
				<input type="file" name="newavatar" />
				<div class="formbutton">
					<input id="avatar_upload" type="submit" value="" /><label class="buttonlabel">{$translations['upload']}</label>
					<input type="hidden" name="option" value="com_jchat" />
					<input type="hidden" name="controller" value="avatar" />
					<input type="hidden" name="task" value="doUpload" />
					<input type="hidden" name="format" value="raw" />
				</div>
		</form> 
UPLOADFORM;
		
		
		echo $includes;
		// Controllo presenza GD library
		if (extension_loaded('gd') && function_exists('gd_info')) { 
			echo $uploadForm;
			echo $deleteForm;
		} else {
			$msg = JText::_('JCHAT_GDERROR');
		}
	
		$visibileClass = $msg !== null ? 'visible' : '';
		$userMessage = <<<MESSAGE
		<div class="upload_usermessage $visibileClass">$msg<div class="closemsgbtn"></div></div>
MESSAGE;
		echo $userMessage;
	}

	/**
	 * @access public
	 * @return void 
	 */
	public function doUpload() {
		// Recupera il file in upload
		$tmpFile = $_FILES['newavatar']['tmp_name'];
		$tmpFileName = $_FILES['newavatar']['name'];
		
		// Nessun file inviato
		if(!$tmpFile || !$tmpFileName) {
			$msg = JText::_('JCHAT_NOFILE_SELECTED');
			$this->showForms($msg);
			return;
		}
		 
		// Controlla se il file � inferiore alla dimensione massima, altrimenti interrompe con errore
		$tmpFileSize = $_FILES['newavatar']['size'];
		$allowedFileSize = $this->config->get('maxfilesize') * 1024 * 1024; // MB->Bytes
		if($tmpFileSize > $allowedFileSize) {
			$msg = JText::_('JCHAT_SIZE_ERROR') . 'Max ' . $this->config->get('maxfilesize') . 'MB.';
			$this->showForms($msg);
			return;
		}
		
		// Controlla se il file ha una estensione ammessa, altrimenti interrompe con errore
		$allowedExtensions = explode(',', $this->config->get('avatar_allowed_extensions')); 
		$tmpFileExtension = @array_pop(explode('.', $tmpFileName));
		if(!in_array($tmpFileExtension, $allowedExtensions)) {
			$msg = JText::_('JCHAT_EXT_ERROR') . $this->config->get('avatar_allowed_extensions');
			$this->showForms($msg);
			return;
		}
				
		// Controlla se la cartella target � scrivibile, altrimenti prova a settare i chmod, altrimenti interrompe con errore
		if(!is_writable($this->avatarFolder)) {
			// prova a amodificare i permessi
			try {
				if(!chmod($this->avatarFolder, 0775)) {
					throw new Exception( JText::_('JCHAT_DIR_WRITABLE'));
				}
			} catch(Exception $e) {
				$msg = $e->getMessage();
				$this->showForms($msg);
				return;
			}
		}
		 
		// Effettua la move uploaded file
		if(!move_uploaded_file($tmpFile, $this->avatarFolder . '/' . $tmpFileName)) {
			$msg = JText::_('JCHAT_UPLOAD_ERROR');
			$this->showForms($msg);
			return;
		}
		
		// Richiede la creazione di un hash filename per il thumbnail con format force a png
		$thumbnailFileName = $this->createAvatarThumbnailFileName(self::$avatarFormat);
		
		// Se esiste il file ripuliamo adesso; il file name � sempre univoco per utente anche il formato � forced a png
		if(file_exists($thumbnailFileName[0])) {
			unlink($thumbnailFileName[0]);
		}
		
		// Genera un thumbnail per l'immagine caricata
		$this->createThumbnail($this->avatarFolder . '/' . $tmpFileName, $thumbnailFileName[0]);
		
		// Elimina il file originale caricato 
		unlink ($this->avatarFolder . '/' . $tmpFileName);
		 
		// Richiama la showForms con user message 
		$msg = JText::_('JCHAT_SUCCESS_AVATAR');
		$this->showForms($msg);
	}

	/**
	 * @access public
	 * @return void 
	 */
	public function deleteAvatar() {
	// Richiede la creazione di un hash filename per il thumbnail con format force a png
		$thumbnailFileName = $this->createAvatarThumbnailFileName(self::$avatarFormat);
		
		// Se esiste il file ripuliamo adesso; il file name � sempre univoco per utente anche il formato � forced a png
		if(file_exists($thumbnailFileName[0])) {
			if(unlink($thumbnailFileName[0])){ 
				$msg = JText::_('JCHAT_SUCCESS_DELETE_AVATAR'); 
			} else {
				$msg = JText::_('JCHAT_ERROR_DELETING_AVATAR');
			}
		} else {
			$msg = JText::_('JCHAT_AVATAR_NOTFOUND');
		}
		
		// Richiama la showForms con user message  
		$this->showForms($msg);
	}
	
	/**
	 * Generate and assign avatars to users
	 * 
	 * @param string $sessionID
	 * @return string
	 */
	public static function getAvatar($sessionID) { 
		$baseURL = JURI::base();
		$cParams = JComponentHelper::getParams('com_jchat');
		
		// User session object
		$userSessionTable = self::getTableSession();
		$userSessionTable->load($sessionID);
		$userId = $userSessionTable->userid;
		
		// PRIORITY 1 - Try for JomSocial avatar if integration active
		if($cParams->get('3pdintegration', false) === 'jomsocial' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('$baseURL', thumb) AS avatar" .
					"\n FROM #__community_users AS cu" .
					"\n INNER JOIN #__users AS u ON cu.userid = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.thumb != ''";
			$DBO->setQuery($sql);
			if($jomSocialAvatar = $DBO->loadResult()) {
				return $jomSocialAvatar;
			}
		}
		
		// PRIORITY 1 - Try for EasySocial avatar if integration active
		if($cParams->get('3pdintegration', false) === 'easysocial' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('" . $baseURL . "media/com_easysocial/avatars/users/" . $userId . "/', square) AS avatar" .
					"\n FROM #__social_avatars AS cu" .
					"\n INNER JOIN #__users AS u ON cu.uid = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.square != ''";
			$DBO->setQuery($sql);
			if($jomSocialAvatar = $DBO->loadResult()) {
				return $jomSocialAvatar;
			}
		}
		
		// PRIORITY 1 - Try for CB avatar if integration active
		if($cParams->get('3pdintegration', false) === 'cbuilder' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('" . $baseURL . "images/comprofiler/', avatar)" .
					"\n FROM #__comprofiler AS cu" .
					"\n INNER JOIN #__users AS u ON cu.id = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.avatarapproved = 1 AND cu.avatar != ''";
			$DBO->setQuery($sql);
			if($cbAvatar = $DBO->loadResult()) {
				return $cbAvatar;
			}
		}
		
		// PRIORITY 1 - Try for Kunena avatar if integration active
		if($cParams->get('3pdintegration', false) === 'kunena' && $userId) {
			$DBO = JFactory::getDBO();
			$sql = 	"SELECT CONCAT('" . $baseURL . "media/kunena/avatars/resized/size36/', avatar) AS avatar" .
					"\n FROM #__kunena_users AS cu" .
					"\n INNER JOIN #__users AS u ON cu.userid = u.id" .
					"\n WHERE u.id = " . $DBO->quote($userId) .
					"\n AND cu.avatar != ''";
			$DBO->setQuery($sql);
			if($kunenaAvatar = $DBO->loadResult()) {
				return $kunenaAvatar;
			}
		}
		
		// Calculate avatar name based on md5 from user id and username
		$calculatedHash = $userId ? 'uidavatar_' . $userId : 'gsidavatar_' . $sessionID;
		$finalName = $calculatedHash . '.' . self::$avatarFormat; 
		$filePath = JPATH_COMPONENT . self::$avatarSubPath . $finalName;
		
		// PRIORITY 2 - User uploaded avatar, check if user has uploaded avatar
		if(file_exists($filePath)) {
			$lastModTimeFile = self::crossFileMTime($filePath);
			$liveUrl = JURI::base() . '/components/com_jchat/images/avatars/' . $finalName . '?nocache='.$lastModTimeFile;
			return $liveUrl;
		} else { 
			// PRIORITY 3 - Default avatar image for my and other users
			// Current user session table
			$userSessionTable->load(session_id());
			$am_i = $sessionID == $userSessionTable->session_id ? 'my' : 'other';
			$defaultAvatar = 'default_' . $am_i . '.png';
			$liveUrl = JURI::base() . '/components/com_jchat/images/avatars/' . $defaultAvatar ;
			return $liveUrl;
		}  
	}

	/**
	 * Class constructor
	 * @access public 
	 * @return Object &
	 */
	public function __construct() {
		$this->DBO = JFactory::getDBO();
		$this->siteurl = JURI::base() . '/components/com_jchat/';
		$this->avatarFolder = JPATH_COMPONENT . '/images/avatars';
        $fromsoftware = JRequest::getVar('fromsoftware');

        $userObject = JFactory::getUser();
        if($fromsoftware==42)
            $userObject = JFactory::getUser($fromsoftware);

		// Current user object

		// Current user session table
		$userSessionTable = JTable::getInstance('session');
		$userSessionTable->load(session_id());
		
		$this->sessionId = $userSessionTable->session_id;
		$this->userId = $userObject->id;
		$this->userName = $userObject->username;
	 
		// CONFIG LOAD
		$this->config = JComponentHelper::getParams('com_jchat'); 
		$this->noAvatarImgName = 'default_my.png';
	}
} 
?>