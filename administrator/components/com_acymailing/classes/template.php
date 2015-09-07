<?php
/**
 * @package	AcyMailing for Joomla!
 * @version	4.2.0
 * @author	acyba.com
 * @copyright	(C) 2009-2013 ACYBA S.A.R.L. All rights reserved.
 * @license	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die('Restricted access');
?><?php

class templateClass extends acymailingClass{

	var $tables = array('template');
	var $pkey = 'tempid';
	var $namekey = 'alias';
	var $templateNames = array();
	var $archiveSection = false;
	var $proposedAreas = false;

	function get($tempid,$default = null){
		$column = is_numeric($tempid) ? 'tempid' : 'name';
		$this->database->setQuery('SELECT * FROM '.acymailing_table('template').' WHERE '.$column.' = '.$this->database->Quote($tempid).' LIMIT 1');
		$template = $this->database->loadObject();
		return $this->_prepareTemplate($template);
	}

	function getDefault(){
		$this->database->setQuery('SELECT * FROM '.acymailing_table('template').' WHERE premium = 1 AND published = 1 ORDER BY ordering ASC LIMIT 1');
		$template = $this->database->loadObject();
		return $this->_prepareTemplate($template);
	}

	function _prepareTemplate($template){
		if(!isset($template->styles)) return $template;

		if(empty($template->styles)){
			$template->styles = array();
		}else{
			$template->styles = unserialize($template->styles);
		}

		return $template;
	}

	function saveForm(){

		$app = JFactory::getApplication();
		$config = acymailing_config();
		$template = new stdClass();
		$template->tempid = acymailing_getCID('tempid');

		$formData = JRequest::getVar( 'data', array(), '', 'array' );

		foreach($formData['template'] as $column => $value){
			acymailing_secureField($column);
			$template->$column = strip_tags($value);
		}

		$styles = JRequest::getVar('styles',array(),'','array');
		foreach($styles as $class => $oneStyle){
		$styles[$class] = str_replace('"',"'",$oneStyle);
			if(empty($oneStyle)) unset($styles[$class]);
		}

		$newStyles = JRequest::getVar('otherstyles',array(),'','array');
		if(!empty($newStyles)){
			foreach($newStyles['classname'] as $id => $className){
				if(!empty($className) AND $className != JText::_('CLASS_NAME') AND !empty($newStyles['style'][$id]) AND $newStyles['style'][$id] != JText::_('CSS_STYLE')){
					$className = str_replace(array(',',' ',':','.','#'),'',$className);
					$styles[$className] = str_replace('"',"'",$newStyles['style'][$id]);
				}
			}
		}
		$template->styles = serialize($styles);

		$files = JRequest::getVar( 'pictures', array(), 'files', 'array' );
		if(!empty($files)){
			jimport('joomla.filesystem.file');

			$uploadFolder = JPath::clean(html_entity_decode($config->get('uploadfolder')));
			$uploadFolder = trim($uploadFolder,DS.' ').DS;
			$uploadPath = JPath::clean(ACYMAILING_ROOT.$uploadFolder);

			acymailing_createDir($uploadPath,true);

			if(!is_writable($uploadPath)){
				@chmod($uploadPath,'0755');
				if(!is_writable($uploadPath)){
					$app->enqueueMessage(JText::sprintf( 'WRITABLE_FOLDER',$uploadPath), 'notice');
				}
			}

			$allowedExtensions = array('jpg','png','gif','jpeg');

			foreach($files['name'] as $id => $filename){
				if(empty($filename)) continue;
				$extension = strtolower(substr($filename,strrpos($filename,'.')+1));
				if(!in_array($extension,$allowedExtensions)){
					$app->enqueueMessage(JText::sprintf('ACCEPTED_TYPE',$extension,implode(', ',$allowedExtensions)), 'notice');
					continue;
				}

				$pictname = strtolower(substr(JFile::makeSafe($filename),0,strrpos($filename,'.')+1));
				$pictname = preg_replace('#[^0-9a-z]#i','_',$pictname);
				$pictfullname = $pictname.'.'.$extension;
				if(file_exists($uploadPath.$pictfullname)){
					$pictfullname = $pictname.time().'.'.$extension;
				}

				if(!JFile::upload($files['tmp_name'][$id], $uploadPath.$pictfullname)){
					if ( !move_uploaded_file($files['tmp_name'][$id], $uploadPath . $pictfullname)) {
						$app->enqueueMessage(JText::sprintf( 'FAIL_UPLOAD','<b><i>'.$files['tmp_name'][$id].'</i></b>','<b><i>'.$uploadPath . $pictfullname.'</i></b>'), 'error');
						continue;
					}
				}

				$template->$id = str_replace(DS,'/',$uploadFolder).$pictfullname;
			}
		}



		$template->body = JRequest::getVar('editor_body','','','string',JREQUEST_ALLOWRAW);

		if(!empty($styles['color_bg'])){
			$pat1 = '#^([^<]*<[^>]*background-color:)([^;">]{1,30})#i';
			$found = false;
			if(preg_match($pat1,$template->body)){
				$template->body = preg_replace($pat1,'$1'.$styles['color_bg'],$template->body);
				$found = true;
			}
			$pat2 = '#^([^<]*<[^>]*bgcolor=")([^;">]{1,10})#i';
			if(preg_match($pat2,$template->body)){
				$template->body = preg_replace($pat2,'$1'.$styles['color_bg'],$template->body);
				$found = true;
			}
			if(!$found){
				$template->body = '<div style="background-color:'.$styles['color_bg'].';" width="100%">'.$template->body.'</div>';
			}
		}

		$acypluginsHelper = acymailing_get('helper.acyplugins');
		$acypluginsHelper->cleanHtml($template->body);

		$template->description = JRequest::getVar('editor_description','','','string',JREQUEST_ALLOWRAW);

		$tempid = $this->save($template);
		if(!$tempid) return false;

		if(empty($template->tempid)){
			$orderClass = acymailing_get('helper.order');
			$orderClass->pkey = 'tempid';
			$orderClass->table = 'template';
			$orderClass->reOrder();
		}

		$this->createTemplateFile($tempid);

		JRequest::setVar( 'tempid', $tempid);
		return true;

	}

	function save($element){
		if(empty($element->tempid)){
			if(empty($element->namekey)) $element->namekey = time().JFilterOutput::stringURLSafe($element->name);
		}else{
			 if(file_exists(ACYMAILING_TEMPLATE.'css'.DS.'template_'.intval($element->tempid).'.css')){
				 jimport('joomla.filesystem.file');
				 if(!JFile::delete(ACYMAILING_TEMPLATE.'css'.DS.'template_'.intval($element->tempid).'.css')){
					 echo acymailing_display('Could not delete the file '.ACYMAILING_TEMPLATE.'css'.DS.'template_'.intval($element->tempid).'.css','error');
				 }
			 }
		}

		if(!empty($element->styles) AND !is_string($element->styles))  $element->styles = serialize($element->styles);

		if(!empty($element->stylesheet)){
			$element->stylesheet = preg_replace('#:(active|current|visited)#i','',$element->stylesheet);
		}

		return parent::save($element);

	}

	function detecttemplates($folder){
		$allFiles = JFolder::files($folder);
		if(!empty($allFiles)){
			foreach($allFiles as $oneFile){
				if(preg_match('#^.*(html|htm)$#i',$oneFile)){
					if($this->installtemplate($folder.DS.$oneFile)) return true;
				}
			}
		}

		$status = false;
		$allFolders = JFolder::folders($folder);
		if(!empty($allFolders)){
			foreach($allFolders as $oneFolder){
				$status = $this->detecttemplates($folder.DS.$oneFolder) || $status;
			}
		}

		return $status;
	}

	function buildCSS($styles,$stylesheet){
		$inline = '';

		if(preg_match_all('#@import[^;]*;#is',$stylesheet,$results)){
			foreach($results[0] as $oneResult){
				$inline .= trim($oneResult)."\n";
				$stylesheet = str_replace($oneResult,'',$stylesheet);
			}
		}

		if(!empty($styles)){
			foreach($styles as $class => $style){
				if(preg_match('#^tag_(.*)$#',$class,$result)){
					if(!empty($style))	$inline.= $result[1].' { '.$style.' } '."\n";
				}elseif($class != 'color_bg'){
					if(!empty($style)) $inline.= '.'.$class.' {'.$style.'} '."\n";
				}else{
					if(!empty($style)) $inline.= 'body{background-color:'.$style.'} '."\n";
				}
			}
		}

		if(version_compare(PHP_VERSION, '5.0.0', '>=') && class_exists('DOMDocument') && function_exists('mb_convert_encoding')){
			$inline .= 'a img{ border:0px; text-decoration:none;} '."\n";
			$inline .= $stylesheet;
		}

		return $inline;
	}

	function createTemplateFile($id){
		if(empty($id)) return '';
		$cssfile = ACYMAILING_TEMPLATE.'css'.DS.'template_'.$id.'.css';
		if(file_exists($cssfile)) return $cssfile;

		$template = $this->get($id);
		if(empty($template->tempid)) return '';
		$css = $this->buildCSS($template->styles,$template->stylesheet);

		if(empty($css)) return '';

		jimport('joomla.filesystem.file');

		acymailing_createDir(ACYMAILING_TEMPLATE.'css');

		if(JFile::write($cssfile,$css)){
			return $cssfile;
		}else{
			acymailing_display('Could not create the file '.$cssfile,'error');
			return '';
		}
	}

	function installtemplate($filepath){
		$fileContent = file_get_contents($filepath);

		$newTemplate = new stdClass();
		$newTemplate->name = trim(preg_replace('#[^a-z0-9]#i',' ',substr(dirname($filepath),strpos($filepath,'_template'))));
		if(preg_match('#< *title[^>]*>(.*)< */ *title *>#Uis',$fileContent,$results) && !empty($results[1])) $newTemplate->name = $results[1];

		$newFolder =preg_replace('#[^a-z0-9]#i','_',strtolower($newTemplate->name));
		$newTemplateFolder = $newFolder;
		$i = 1;
		while(is_dir(ACYMAILING_TEMPLATE.$newTemplateFolder)){
			$newTemplateFolder = $newFolder.'_'.$i;
			$i++;
		}
		$newTemplate->namekey = rand(0,10000).$newTemplateFolder;
		$moveResult = JFolder::copy(dirname($filepath),ACYMAILING_TEMPLATE.$newTemplateFolder);
		if($moveResult !== true){
			acymailing_display(array('Error copying folder from '.dirname($filepath).' to '.ACYMAILING_TEMPLATE.$newTemplateFolder,$moveResult),'error');
			return false;
		}

		if(!file_exists(ACYMAILING_TEMPLATE.$newTemplateFolder.DS.'index.html')){
			$indexFile = '<html><body bgcolor="#FFFFFF"></body></html>';
			JFile::write(ACYMAILING_TEMPLATE.$newTemplateFolder.DS.'index.html',$indexFile);
		}
		$fileContent = preg_replace('#(src|background)[ ]*=[ ]*\"(?!(https?://|/))(?:\.\./|\./)?#','$1="media/com_acymailing/templates/'.$newTemplateFolder.'/',$fileContent);

		if(preg_match('#< *body[^>]*>(.*)< */ *body *>#Uis',$fileContent,$results)){ $newTemplate->body = $results[1];}else{$newTemplate->body = $fileContent;}

		$newTemplate->stylesheet = '';
		if(preg_match('#< *style[^>]*>(.*)< */ *style *>#Uis',$fileContent,$results)){
			$newTemplate->stylesheet .= preg_replace('#(<!--|-->)#s','',$results[1]);
		}
		$cssFiles = array();
		$cssFiles[ACYMAILING_TEMPLATE.$newTemplateFolder] = JFolder::files(ACYMAILING_TEMPLATE.$newTemplateFolder,'\.css$');
		$subFolders = JFolder::folders(ACYMAILING_TEMPLATE.$newTemplateFolder);
		foreach($subFolders as $oneFolder){
			$cssFiles[ACYMAILING_TEMPLATE.$newTemplateFolder.DS.$oneFolder] = JFolder::files(ACYMAILING_TEMPLATE.$newTemplateFolder.DS.$oneFolder,'\.css$');
		}

		foreach($cssFiles as $cssFolder => $cssFile){
			if(empty($cssFile)) continue;
			$newTemplate->stylesheet .= "\n".file_get_contents($cssFolder.DS.reset($cssFile));
		}

		if(!empty($newTemplate->stylesheet)){
			if(preg_match('#body *\{[^\}]*background-color:([^;]*);#Uis',$newTemplate->stylesheet,$backgroundresults)){
				$newTemplate->styles['color_bg'] = trim($backgroundresults[1]);
				$newTemplate->stylesheet = preg_replace('#(body *\{[^\}]*)background-color:[^;]*;#Uis','$1',$newTemplate->stylesheet);
			}

			$quickstyle = array('tag_h1' => 'h1','tag_h2' => 'h2', 'tag_h3' => 'h3','tag_h4' => 'h4','tag_h5' => 'h5','tag_h6' => 'h6','tag_a' => 'a','tag_ul' => 'ul','tag_li' => 'li','acymailing_unsub' => '\.acymailing_unsub','acymailing_online' => '\.acymailing_online','acymailing_title' => '\.acymailing_title','acymailing_content' => '\.acymailing_content','acymailing_readmore' => '\.acymailing_readmore');
			foreach($quickstyle as $styledb => $oneStyle){
				if(preg_match('#[^a-z\. ,] *'.$oneStyle.' *{([^}]*)}#Uis',$newTemplate->stylesheet,$quickstyleresults)){
					$newTemplate->styles[$styledb] = trim(str_replace(array("\n","\r","\t","\s"),' ',$quickstyleresults[1]));
					$newTemplate->stylesheet = str_replace($quickstyleresults[0],'',$newTemplate->stylesheet);
				}
			}
		}

		if(!empty($newTemplate->styles['color_bg'])){
			$pat1 = '#^([^<]*<[^>]*background-color:)([^;">]{1,10})#i';
			$found = false;
			if(preg_match($pat1,$newTemplate->body)){
				$newTemplate->body = preg_replace($pat1,'$1'.$newTemplate->styles['color_bg'],$newTemplate->body);
				$found = true;
			}
			$pat2 = '#^([^<]*<[^>]*bgcolor=")([^;">]{1,10})#i';
			if(preg_match($pat2,$newTemplate->body)){
				$newTemplate->body = preg_replace($pat2,'$1'.$newTemplate->styles['color_bg'],$newTemplate->body);
				$found = true;
			}
			if(!$found){
				$newTemplate->body = '<div style="background-color:'.$newTemplate->styles['color_bg'].';" width="100%">'.$newTemplate->body.'</div>';
			}
		}

		$foldersForPicts = array($newTemplateFolder);
		$otherFolders = JFolder::folders(ACYMAILING_TEMPLATE.$newTemplateFolder);
		foreach($otherFolders as $oneFold){
			$foldersForPicts[] = $newTemplateFolder.DS.$oneFold;
		}
		$allPictures = array();
		foreach($foldersForPicts as $oneFolder){
			$allPictures[$oneFolder] = JFolder::files(ACYMAILING_TEMPLATE.$oneFolder);
		}
		foreach($allPictures as $folder => $pictfolders){
			foreach($pictfolders as $onePict){
				if(!preg_match('#\.(png|jpg|jpeg|gif)$#i',$onePict)) continue;
				if(preg_match('#(thumbnail|screenshot|muestra)#i',$onePict)){
					$newTemplate->thumb = 'media/com_acymailing/templates/'.str_replace(DS,'/',$folder).'/'.$onePict;
				}elseif(preg_match('#(readmore|lirelasuite)#i',$onePict)){
					$newTemplate->readmore = 'media/com_acymailing/templates/'.str_replace(DS,'/',$folder).'/'.$onePict;
				}
			}
		}

		$newTemplate->ordering = 0;

		$tempid = $this->save($newTemplate);

		$this->proposedAreas = $this->proposeApplyAreas($tempid,false) || $this->proposedAreas;

		$this->createTemplateFile($tempid);

		$orderClass = acymailing_get('helper.order');
		$orderClass->pkey = 'tempid';
		$orderClass->table = 'template';
		$orderClass->reOrder();

		$this->templateNames[] = $newTemplate->name;

		return true;
	}

	function displayPreview($idArea,$tempid,$newslettersubject = ''){
		acymailing_loadMootools();

		if(isset($_SERVER["REQUEST_URI"])){
			$requestUri = $_SERVER["REQUEST_URI"];
		}else{
			$requestUri = $_SERVER['PHP_SELF'];
			if (!empty($_SERVER['QUERY_STRING'])) $requestUri = rtrim($requestUri,'/').'?'.$_SERVER['QUERY_STRING'];
		}
		$currentURL = ((empty($_SERVER['HTTPS']) OR strtolower($_SERVER['HTTPS']) != "on" ) ? 'http://' : 'https://').$_SERVER["HTTP_HOST"].$requestUri;

		$js = "var iframecreated = false;
				function acydisplayPreview(){
					var d = document, area = d.getElementById('$idArea');
					if(!area) return;
					if(iframecreated) return;
					iframecreated = true;
					var content = area.innerHTML;
					var myiframe = d.createElement(\"iframe\");
					myiframe.id = 'iframepreview';
					curHeight = 400;
					if(area.offsetHeight){curHeight=area.offsetHeight;}
					else if(area.style.pixelHeight){curHeight=area.style.pixelHeight;}
					myiframe.style.height = (curHeight+100) + 'px';
					myiframe.style.width = '100%';
					myiframe.style.borderWidth = '0px';
					myiframe.allowtransparency = \"true\";
					myiframe.frameBorder = '0';
					area.innerHTML = '';
					area.appendChild(myiframe);
					myiframe.onload = function(){
						initIframePreview(myiframe,content);
						replaceAnchors(myiframe);
					}
					myiframe.src = '';

				}
				function resetIframeSize(myiframe){


					innerDoc = (myiframe.contentDocument) ? myiframe.contentDocument : myiframe.contentWindow.document;
					objToResize = (myiframe.style) ? myiframe.style : myiframe;
					if(objToResize.width != '100%') return;

					newHeight = innerDoc.body.scrollHeight;
					if(parseInt(objToResize.height,10)+10 < newHeight) objToResize.height = newHeight+'px';
					setTimeout(function(){resetIframeSize(myiframe);},1000);
				}
				function replaceAnchors(myiframe){
					myiframedoc = myiframe.contentWindow.document;
					myiframebody = myiframedoc.body;
					el = myiframe;
					var myiframeOffset = el.offsetTop;
					while ( ( el = el.offsetParent ) != null )
					{
						myiframeOffset += el.offsetTop;
					}

					var elements = myiframebody.getElementsByTagName(\"a\");
					for( var i = elements.length - 1; i >= 0; i--){
						var aref = elements[i].getAttribute('href');
						if(!aref) continue;
						if(aref.indexOf(\"#\") != 0 && aref.indexOf(\"".addslashes($currentURL)."#\") != 0) continue;

						if(elements[i].onclick && elements[i].onclick != \"\") continue;

						var adest = aref.substring(aref.indexOf(\"#\")+1);
						if( adest.length < 1 ) continue;

						elements[i].dest = adest;
						elements[i].onclick = function(){
							elem = myiframedoc.getElementById(this.dest);
							if(!elem){
								elems = myiframedoc.getElementsByName(this.dest);
								if(!elems || !elems[0]) return false;
								elem = elems[0];
							}
							if( !elem ) return false;

							el = elem;
							var elemOffset = el.offsetTop;
							while ( ( el = el.offsetParent ) != null )
							{
								elemOffset += el.offsetTop;
							}
							window.scrollTo(0,elemOffset+myiframeOffset-15);
							return false;
						};
					}
				}
				function initIframePreview(myiframe,content){
					var d = document;
					if(!myiframe.contentWindow || !myiframe.contentWindow.document) return;

					myiframe.contentWindow.document.body.innerHTML = content;

					setTimeout(function(){resetIframeSize(myiframe);},100);

					var title1 = d.createElement(\"title\");
					title1.innerHTML = '".addslashes($newslettersubject)."';


					var base1 = d.createElement(\"base\");
					base1.target = \"_blank\";
					var head = myiframe.contentWindow.document.getElementsByTagName(\"head\")[0];
					head.appendChild(base1);
					head.appendChild(title1);
				";
		if(!empty($tempid)){
			$js .= "var link1 = d.createElement(\"link\");
					link1.type = \"text/css\";
					link1.rel = \"stylesheet\";
					link1.href =  '".ACYMAILING_LIVE."media/com_acymailing/templates/css/template_".$tempid.".css?time=".time()."';
					head.appendChild(link1);
				";
		}

		$js .= "var style1 = d.createElement(\"style\");
				style1.type = \"text/css\";
				style1.id = \"overflowstyle\";
				try{style1.innerHTML = 'html,body,iframe{overflow-y:hidden} ';}catch(err){style1.styleSheet.cssText = 'html,body,iframe{overflow-y:hidden} ';}
				";

		if($this->archiveSection){
			$js .= "try{style1.innerHTML += ' .hideonline{display:none;} ';}catch(err){style1.styleSheet.cssText += ' .hideonline{display:none;} ';}";
		}

		$js .="head.appendChild(style1);
			}
			window.addEvent('domready', function(){acydisplayPreview();} );";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );

		$resize = "function previewResize(newWidth,newHeight){
			if(document.getElementById('iframepreview')){
				myiframe = document.getElementById('iframepreview');
			}else{
				myiframe = document.getElementById('newsletter_preview_area');
			}
			myiframe.style.width = newWidth;
			if(newHeight == '100%'){
				resetIframeSize(myiframe);
			}else{
				myiframe.style.height = newHeight;
				myiframe.contentWindow.document.getElementById('overflowstyle').media = \"print\";
			}
		}
		function previewSizeClick(elem){
			var ids = new Array('preview320','preview480','preview768','previewmax');
			for(var i=0;i<ids.length;i++){
				document.getElementById(ids[i]).className = 'previewsize '+ids[i];
			}
			elem.className += 'enabled';
		}";
		$doc->addScriptDeclaration( $resize );
		$switchPict = "function switchPict(){
			var myiframe = document.getElementById('iframepreview');
			var doc = myiframe.contentWindow.document;
			var area = doc.body;
			if(document.getElementById('previewpict').className == 'previewsize previewpictenabled'){
				remove = true;
				document.getElementById('previewpict').className = 'previewsize previewpict';
			}else{
				remove = false;
				document.getElementById('previewpict').className = 'previewsize previewpictenabled';
			}
			var elements = area.getElementsByTagName(\"img\");
			for( var i = elements.length - 1; i >= 0; i-- ) {
				if(remove){
					elements[i].src_temp = elements[i].src;
					elements[i].src = 'pictureremoved';
				}else{
					elements[i].src = elements[i].src_temp;
				}
			}
			if(myiframe.style.width == '100%'){
				resetIframeSize(myiframe);
			}
		}";
		$doc->addScriptDeclaration( $switchPict );
	}

	function proposeApplyAreas($tempid,$addextrawarning = true){
		if(empty($tempid)) return false;

		$config = acymailing_config();
		if($config->get('editor') != 'acyeditor') return false;

		$template = $this->get($tempid);
		if(empty($template->body)) return false;
		if(strpos($template->body,'acyeditor_')) return false;

		$messages = array('<a href="index.php?option=com_acymailing&ctrl=template&task=applyareas&tempid='.$tempid.(JRequest::getCmd('tmpl') == 'component' ? '&tmpl=component' : '').'">'.JText::_('ACYEDITOR_ADDAREAS').'</a>');
		if($addextrawarning) $messages[] = JText::_('ACYEDITOR_ADDAREAS_ONLYFINISHED');
		acymailing_display($messages,'warning');
		return true;

	}

	function applyAreas(&$html){

		if(strpos($html,'acyeditor_')) return false;

		if(preg_match_all('#(<td[^>]*>) *(<img[^>]*> *</td>)#Uis',$html,$results)){
			foreach($results[0] as $i => $oneResult){
				if(preg_match('#class=("|\'])#Uis',$results[1][$i],$charused)){
					$newTag = str_replace('class='.$charused[1],'class='.$charused[1].'acyeditor_picture ',$results[1][$i]);
				}else{
					$newTag = str_replace('<td','<td class="acyeditor_picture"',$results[1][$i]);
				}
				$html = str_replace($results[0][$i],$newTag.$results[2][$i],$html);
			}
		}

		$textElements = array('td','div');
		$divhtml = $html;
		foreach($textElements as $starttag){
			if(!preg_match_all('#(<'.$starttag.'(?:(?!>|acyeditor_).)*>)((?:(?!<td|acyeditor_|<'.$starttag.').)*</'.$starttag.'>)#Uis',$divhtml,$results)) continue;

			$class='acyeditor_text';
			if($starttag == 'div') $class .= ' acyeditor_delete';

			foreach($results[0] as $i => $oneResult){

				$content = trim(str_replace(array(' ','&nbsp;',"\n","\r"),'',strip_tags($results[0][$i])));

				if(empty($content)) continue;

				if(preg_match('#class=("|\'])#Uis',$results[1][$i],$charused)){
					$newTag = str_replace('class='.$charused[1],'class='.$charused[1].$class.' ',$results[1][$i]);
				}else{
					$newTag = str_replace('<'.$starttag,'<'.$starttag.' class="'.$class.'"',$results[1][$i]);
				}
				$html = str_replace($results[0][$i],$newTag.$results[2][$i],$html);
				$divhtml = str_replace($results[0][$i],'',$divhtml);
			}
		}

		if(preg_match_all('#(<tr[^>]*>)((?:(?!<tr|acyeditor_delete).)*</tr>)#Uis',$html,$results)){
			foreach($results[0] as $i => $oneResult){
				if(preg_match('#class=("|\'])#Uis',$results[1][$i],$charused)){
					$newTag = str_replace('class='.$charused[1],'class='.$charused[1].'acyeditor_delete ',$results[1][$i]);
				}else{
					$newTag = str_replace('<tr','<tr class="acyeditor_delete"',$results[1][$i]);
				}
				$html = str_replace($results[0][$i],$newTag.$results[2][$i],$html);
			}
		}

		return true;


	}
}
