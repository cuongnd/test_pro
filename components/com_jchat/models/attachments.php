<?php
//namespace components\com_jchat\models; 
/** 
 * @package JCHAT::FILES::components::com_jchat
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.filesystem.file');
/**
 * @package JCHAT::FILES::components::com_jchat
 * @subpackage models
 * @since 1.0
 */ 
class JChatAttachments {
	/**
	 * DB object connector
	 * @access private
	 * @var Object
	 */
	private $DBO;
	
	/**
	 * Sender user ID
	 * @access private
	 * @var int
	 */
	private $from;
	
	/**
	 * Receiver user ID
	 * @access private
	 * @var int
	 */
	private $to;
	
	/**
	 * Plugin configuration object
	 * @access private
	 * @var Object
	 */
	private $config;
	 
	
	/**
	 * @access private
	 * @var int
	 */
	private $cacheFolder;
	
	/**
	 * @access private
	 * @param string $filename
	 * @param string $userid
	 * @return string 
	 */
	private function generaHash($filename, $userid) { 
		$filenameStripped = JFile::stripExt($filename); 
		$fileExtension = JFile::getExt($filename);
		 
		$hash = md5($filenameStripped . $userid);
		return $hash . '.' . $fileExtension;
	}
	
	/**
	 * @access private
	 * @param string $filename
	 * @return boolean 
	 */
	private function storeDBMessage($filename) { 
		if (!empty($this->to) && !empty($filename)) {  
			// Get users actual names
			$actualNames = JChatUsers::getActualNames ( $this->from, $this->to, $this->config );
			
			$sql = "INSERT INTO #__jchat (" .
					$this->DBO->qn('from') . ',' .
					$this->DBO->qn('to') . ',' .
					$this->DBO->qn('message') . ',' .
					$this->DBO->qn('sent') . ',' .
					$this->DBO->qn('read') . ',' .
					$this->DBO->qn('type') . ',' .
					$this->DBO->qn('status') . ',' .
					$this->DBO->qn('actualfrom') . ',' .
					$this->DBO->qn('actualto') . ') VALUES( ' . 
					$this->DBO->quote($this->from). ", ".
					$this->DBO->quote($this->to). ",".
					$this->DBO->quote($filename) . 
					",UNIX_TIMESTAMP(NOW())" . "," .
					"0" . "," .
					$this->DBO->quote('file') . "," .
					"0" . "," .
					$this->DBO->quote($actualNames['fromActualName']) . ", ".
					$this->DBO->quote($actualNames['toActualName']) . 
					")";
		    $this->DBO->setQuery($sql);
			if(!$this->DBO->execute()){
				return false;
			} 
			
			if (empty($_SESSION['jchat_user_'.$this->to])) {
				$_SESSION['jchat_user_'.$this->to] = array();
			}
			$_SESSION['jchat_user_'.$this->to][] = array("id" => $this->DBO->insertid(), 
															 "from" => $this->to, 
															 "message" => $filename, 
				 											 "type" => 'file',
								 							 "status" => 0,
															 "self" => 1, 
															 "old" => 1) ; 
		}
		return true;
	}

	/**
	 * @access private
	 * @param string $nomefile
	 * @return boolean
	 */
	private function readFileChunked($filePath) {
		$chunksize = 1 * (1024 * 1024); // how many bytes per chunk
		$buffer = '';
		$cnt = 0;
		$handle = fopen ( $filePath, 'rb' );
		if ($handle === false) {
			return false;
		}
		while ( ! feof ( $handle ) ) {
			$buffer = fread ( $handle, $chunksize );
			echo $buffer;
			@ob_flush ();
			flush ();
		}
		$status = fclose ( $handle );
		return $status;
	}
	
	/**
	 * @access private
	 * @param $filename
	 * @return string Il mime type trovato a fronte del lookup nella tabella
	 */
	private function detectMimeType($filename) {
		global $mosConfig_absolute_path;
		include_once JPATH_COMPONENT . '/libraries/mime.mapping.php';
		
		$filename = strtolower ( $filename );
		$exts = preg_split ( "#[/\\.]#i", $filename );
		$n = count ( $exts ) - 1;
		$fileExtension = $exts [$n];
		
		foreach ( $mime_extension_map as $extension => $mime ) {
			if ($extension === $fileExtension)
				return $mime;
		}
		return 'application/octet-stream';
	}
	
	/**
	 * @access public
	 * @param string $msg
	 * @return void 
	 */
	public function showForms($msg = null) { 
		$baseUri = JURI::base(); 
		
		$maxFileSize = $this->config->get('maxfilesize');
		$disallowedExt = json_encode(explode(',', $this->config->get('disallowed_extensions')));
		$translations = array('upload'=>JTEXT::_('JCHAT_UPLOAD'));
		
		$includes = <<<INCLUDES
		<script type="text/javascript" src="{$baseUri}components/com_jchat/js/jquery.js"></script>
		<link type="text/css" rel="stylesheet" href="{$baseUri}components/com_jchat/css/mainstyle.css"/>
		<script type="text/javascript">
			jQuery(function(){jQuery('div.closemsgbtn').on('click', function(){jQuery(this).parent().css('display', 'none')});})
		</script>
INCLUDES;
		echo $includes;
		
		$visibileClass = $msg !== null ? 'visible' : '';
		$userMessage = <<<MESSAGE
		<div class="upload_usermessage $visibileClass">$msg<div class="closemsgbtn"></div></div>
MESSAGE;
		echo $userMessage;
		
		$uploadForm = <<<UPLOADFORM
		
		<form name="uploadform" enctype="multipart/form-data" id="uploadform" method="post" action="index.php">
			
				<input type="file" name="newfile" />
				<div class="formbutton">
					<input id="file_upload_button" type="submit" value="" /><label class="buttonlabel">{$translations['upload']}</label>
				</div>
			<input type="hidden" name="option" value="com_jchat" />
			<input type="hidden" name="controller" value="attachments" />
			<input type="hidden" name="task" value="doUpload" />
			<input type="hidden" name="format" value="raw" />
			<input type="hidden" name="to" value="$this->to" />
		</form> 
UPLOADFORM;
		echo $uploadForm; 
	}

	/**
	 * @access public
	 * @return void 
	 */
	public function doUpload() {
		$tmpFile = $_FILES['newfile']['tmp_name'];
		$tmpFileName = $_FILES['newfile']['name'];
		
		if(!$tmpFile || !$tmpFileName) {
			$msg = JText::_('JCHAT_NOFILE_SELECTED');
			$this->showForms($msg);
			return;
		}
		
		$tmpFileSize = $_FILES['newfile']['size'];
		$allowedFileSize = $this->config->get('maxfilesize') * 1024 * 1024; // MB->Bytes
		if($tmpFileSize > $allowedFileSize) {
			$msg = JText::_('JCHAT_SIZE_ERROR') .' Max ' . $this->config->get('maxfilesize') . 'MB.';
			$this->showForms($msg);
			return;
		}
		
		$disallowedExtensions = explode(',', $this->config->get('disallowed_extensions')); 
		$tmpFileExtension = @array_pop(explode('.', $tmpFileName));
		if(in_array($tmpFileExtension, $disallowedExtensions)) {
			$msg = JText::_('JCHAT_EXT_ERROR') . $this->config->get('disallowed_extensions');
			$this->showForms($msg);
			return;
		}
				
		if(!is_dir($this->cacheFolder)) {
			JFolder::create($this->cacheFolder);
		}
		
		if(!is_writable($this->cacheFolder)) {
			try {
				if(!chmod($this->cacheFolder, 0775)) {
					throw new Exception( JText::_('JCHAT_DIR_WRITABLE'));
				}
			} catch(Exception $e) {
				$msg = $e->getMessage();
				$this->showForms($msg);
				return;
			}
		}
		 
		if(!move_uploaded_file($tmpFile, $this->cacheFolder . $tmpFileName)) {
			$msg =  JText::_('JCHAT_UPLOAD_ERROR');
			$this->showForms($msg);
			return;
		}
	 
		$hashedFileName = $this->generaHash($tmpFileName, $this->from);
		
		if(file_exists($this->cacheFolder . $hashedFileName)) {
			unlink($this->cacheFolder . $hashedFileName);
		}
		 
		if(!rename($this->cacheFolder . $tmpFileName, $this->cacheFolder . $hashedFileName)) {
			$msg = JText::_('JCHAT_RENAME_ERROR');
			$this->showForms($msg);
			return;
		}
		 
		if(!$this->storeDBMessage($tmpFileName)) {
			$msg =  JText::_('JCHAT_SENDMSGFILE_ERROR');
			$this->showForms($msg);
			return;
		}
		
		$msg =  JText::_('JCHAT_SUCCESS_FILEUPLOAD');
		$this->showForms($msg);
	}

	/**
	 * @return void
	 */
	public function doDownload() { 
		$idMessage = JRequest::getInt('idMessage', 0);
		$idUserConversation = JRequest::getInt('from', 0);
		 
		try {
			$query = "SELECT #__jchat.from, #__jchat.message FROM #__jchat WHERE id = " . (int)$idMessage;
			$this->DBO->setQuery($query);
			$resultInfo = $this->DBO->loadObject();
			if(!$resultInfo) {
				$conversationArray = $_SESSION['jchat_user_' . $idUserConversation];
				foreach ($conversationArray as $message) {
					if($message['id'] == $idMessage) {
						$resultInfo = new stdClass();
						$resultInfo->from = $message['from'];
						$resultInfo->message = $message['message'];
						break;
					} 
				}
				if(!$resultInfo) {
					throw new Exception('JCHAT_ERROR_NOTFOUND_FILE');
				}
			}
			$fileName = $this->generaHash($resultInfo->message, $resultInfo->from);
			$filePath = $this->cacheFolder . $fileName;
			
			if(!file_exists($filePath)) {
				throw new Exception('JCHAT_ERROR_DELETED_FILE');
			}
		} catch (Exception $e) {
			// JS inject
			echo '<script type="text/javascript">alert("' . JText::_($e->getMessage()) . '");window.history.go(-1);</script>';
			exit();
		} 
		
		$fsize = @filesize ( $filePath );
		$mod_date = date ( 'r', filemtime ( $filePath ) ); 
		$cont_dis = 'attachment';
		$mimeType = $this->detectMimeType ( $fileName );
		
		// required for IE, otherwise Content-disposition is ignored
		if (ini_get ( 'zlib.output_compression' )) {
			ini_set ( 'zlib.output_compression', 'Off' );
		}
		header ( "Pragma: public" );
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Expires: 0" );
		header ( "Content-Transfer-Encoding: binary" );
		header ( 'Content-Disposition:' . $cont_dis . ';' . ' filename="' . $resultInfo->message . '";' . ' modification-date="' . $mod_date . '";' . ' size=' . $fsize . ';' ); //RFC2183
		header ( "Content-Type: " . $mimeType ); // MIME type
		header ( "Content-Length: " . $fsize );
		if (! ini_get ( 'safe_mode' )) { // set_time_limit doesn't work in safe mode
			@set_time_limit ( 0 );
		}
		// No encoding - we aren't using compression... (RFC1945)
		//header("Content-Encoding: none");
		//header("Vary: none");
		$downloadStatus = $this->readFileChunked ( $filePath );
		
		// Al raggiungimento dell'effettivo download si aggiorna lo status update
		if($downloadStatus) {
			$query = "UPDATE #__jchat SET status=1 WHERE id = " . (int)$idMessage;
			$this->DBO->setQuery($query); 
			$this->DBO->execute();
		} 
		exit();
	}

	/**
	 * Class constructor
	 * @access public
	 * @param int $from
	 * @param int $to
	 * @return Object &
	 */
	public function __construct($from, $to) {
		$this->DBO = JFactory::getDBO();
		$this->config = JComponentHelper::getParams ( 'com_jchat' ); 
		
		// From and to user identifiers
		$this->from = $from;
		$this->to = $to;
		$this->cacheFolder = 'components/com_jchat/cache/';
	}
} 
?>