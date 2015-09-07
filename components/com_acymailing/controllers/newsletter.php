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

class NewsletterController extends acymailingController{

	function form(){
		return $this->edit();
	}

	function listing(){
		$listid = JRequest::getInt('listid');
		$redirect = empty($listid) ? acymailing_completeLink('lists',false,true) : acymailing_completeLink('archive&listid='.$listid,false,true);
		$this->setRedirect($redirect);
	}

	function checkAccessList(){
		$listid = JRequest::getInt('listid');
		if(empty($listid)){
			$listClass = acymailing_get('class.list');
			$allAllowedLists = $listClass->getFrontendLists();
			if(!empty($allAllowedLists)){
				$firstList = reset($allAllowedLists);
				$listid = $firstList->listid;
				JRequest::setVar('listid',$listid);
			}
		}

		if(empty($listid)) return false;
		$my = JFactory::getUser();
		$listClass = acymailing_get('class.list');
		$myList = $listClass->get($listid);
		if(empty($myList->listid)) die('Invalid List');
			 if(!empty($my->id) AND (int)$my->id == (int)$myList->userid) return true;
		if(empty($my->id) OR $myList->access_manage =='none') return false;
		if($myList->access_manage != 'all'){
			if(!acymailing_isAllowed($myList->access_manage)) return false;
		}
		return true;
	}

	function scheduleconfirm(){
		if(!$this->checkAccess()) return;
		JRequest::setVar( 'layout', 'scheduleconfirm'  );
		return parent::display();
	}

	function schedule(){
		if(!$this->isAllowed('newsletters','schedule')) return;
		JRequest::checkToken() or die( 'Invalid Token' );
		if(!$this->checkAccess()) return;

		$mailid = acymailing_getCID('mailid');

		$senddate = JRequest::getString( 'senddate','');
		$user = JFactory::getUser();

		if(empty($senddate)){
			acymailing_display(JText::_('SPECIFY_DATE'),'warning');
			return $this->scheduleconfirm();
		}

		$realSendDate = acymailing_getTime($senddate);
		if($realSendDate<time()){
			acymailing_display(JText::_('DATE_FUTURE'),'warning');
			return $this->scheduleconfirm();
		}

		$mail = new stdClass();
		$mail->mailid = $mailid;
		$mail->senddate = $realSendDate;
		$mail->sentby = $user->id;
		$mail->published = 2;

		$mailClass = acymailing_get('class.mail');
		$mailClass->save($mail);

		$myNewsletter = $mailClass->get($mailid);

		JRequest::setVar('tmpl','component');

		acymailing_display(JText::sprintf('AUTOSEND_DATE','<b><i>'.$myNewsletter->subject.'</i></b>',acymailing_getDate($realSendDate)),'success');

		$config = acymailing_config();
		$redirecturl = $config->get('redirect_schedule');
		if(empty($redirecturl)) $redirecturl = "index.php?option=com_acymailing&ctrl=archive&listid=".JRequest::getInt('listid');

		$js = "setTimeout('redirect()',2000); function redirect(){window.top.location.href = '".$redirecturl."'; }";

		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );

	}

	function unschedule(){
		if(!$this->isAllowed('newsletters','schedule')) return;
		if(!$this->checkAccess()) return;

		$mailid = acymailing_getCID('mailid');
		(JRequest::checkToken() && !empty($mailid)) or die( 'Invalid Token' );
		$mail = new stdClass();
		$mail->mailid = $mailid;
		$mail->senddate = 0;
		$mail->published = 0;

		$mailClass = acymailing_get('class.mail');
		$mailClass->save($mail);

		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('SUCC_UNSCHED'));

		return $this->preview();
	}

	function checkAccessNewsletter($edit = true){
		$mailid = acymailing_getCID('mailid');
		$listid = JRequest::getInt('listid');
		if(empty($mailid)) return true;

		$db = JFactory::getDBO();

		$db->setQuery('SELECT * FROM `#__acymailing_mail` WHERE mailid = '.intval($mailid));
		$mail = $db->loadObject();
		if(empty($mail->mailid)) return false;
		$config = acymailing_config();
		$my = JFactory::getUser();
		if($edit AND !$config->get('frontend_modif',1) AND $my->id != $mail->userid) return false;
		if($edit AND !$config->get('frontend_modif_sent',1) AND !empty($mail->senddate)) return false;

		$db->setQuery('SELECT `mailid` FROM `#__acymailing_listmail` WHERE `mailid` = '.intval($mailid).' AND `listid` = '.intval($listid));
		$result = $db->loadResult();
		if(empty($result) && $my->id != $mail->userid) return false;

		return true;
	}

	function checkAccess($edit = true){
		$app = JFactory::getApplication();
		$listid = JRequest::getInt('listid');
		if(!$this->checkAccessList()){

			$my = JFactory::getUser();
			if(empty($my->id)){
				$usercomp = !ACYMAILING_J16 ? 'com_user' : 'com_users';
				$uri = JFactory::getURI();
				$url = 'index.php?option='.$usercomp.'&view=login&return='.base64_encode($uri->toString());
				$app->redirect($url, JText::_('ACY_NOTALLOWED') );
				return false;
			}

			$app->enqueueMessage('You can not have access to this list','error');
			$this->setRedirect('index.php?option=com_acymailing'.(empty($listid) ? '' : '&ctrl=archive&listid='.$listid));
			return false;
		}

		if(!$this->checkAccessNewsletter($edit)){
			$app->enqueueMessage('You can not have access to this Newsletter','error');
			$this->setRedirect('index.php?option=com_acymailing'.(empty($listid) ? '' : '&ctrl=archive&listid='.$listid));
			return false;
		}

		return true;
	}

	function delete(){
		if(!$this->checkAccess()) return;

		list($mailid,$attachid) = explode('_',JRequest::getCmd('value'));
		$mailid = intval($mailid);
		if(empty($mailid)) return false;

		$db	= JFactory::getDBO();
		$db->setQuery('SELECT `attach` FROM '.acymailing_table('mail').' WHERE mailid = '.$mailid.' LIMIT 1');
		$attachment = $db->loadResult();
		if(empty($attachment)) return;
		$attach = unserialize($attachment);

		unset($attach[$attachid]);
		$attachdb = serialize($attach);

		$db->setQuery('UPDATE '.acymailing_table('mail').' SET attach = '.$db->Quote($attachdb).' WHERE mailid = '.$mailid.' LIMIT 1');

		$db->query();
		exit;
	}

	function store(){

		JRequest::checkToken() or die( 'Invalid Token' );
		if(!$this->checkAccess()) return;

		$app = JFactory::getApplication();

		$mailClass = acymailing_get('class.mail');
		$status = $mailClass->saveForm();
		if($status){
			$app->enqueueMessage(JText::_( 'JOOMEXT_SUCC_SAVED' ), 'message');
		}else{
			$app->enqueueMessage(JText::_( 'ERROR_SAVING' ), 'error');
			if(!empty($mailClass->errors)){
				foreach($mailClass->errors as $oneError){
					$app->enqueueMessage($oneError, 'error');
				}
			}
		}
	}

	function edit(){
		if(!$this->checkAccess()) return;
		JRequest::setVar( 'layout', 'form'  );
		return parent::display();
	}

	function savepreview(){
		$this->store();
		return $this->preview();
	}

	function preview(){
		if(!$this->checkAccess()) return;
		JRequest::setVar( 'layout', 'preview'  );
		return parent::display();
	}

	function replacetags(){
		$this->store();
		return $this->edit();

	}

	function sendtest(){
		$this->_sendtest();
		return $this->preview();
	}

	function sendconfirm(){
		if(!$this->checkAccess()) return;
		JRequest::setVar( 'layout', 'sendconfirm'  );
		return parent::display();
	}

	function send(){
		if(!$this->isAllowed('newsletters','send')) return;
		JRequest::checkToken() or die( 'Invalid Token' );
		if(!$this->checkAccess()) return;

		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		$mailid = acymailing_getCID('mailid');
		if(empty($mailid)) exit;

		$time = time();
		$queueClass = acymailing_get('class.queue');
		$nbEmails = $queueClass->nbQueue($mailid);
		if($nbEmails > 0){
			$app->enqueueMessage(JText::sprintf('ALREADY_QUEUED',$nbEmails),'notice');
			return;
		}

		$queueClass->onlynew = JRequest::getInt('onlynew');
		$queueClass->mindelay = JRequest::getInt('mindelay');
		$totalSub = $queueClass->queue($mailid,$time);

		if(empty($totalSub)){
			$app->enqueueMessage(JText::_('NO_RECEIVER'),'notice');
			return;
		}

		$mailObject = new stdClass();
		$mailObject->senddate = $time;
		$mailObject->published = 1;
		$mailObject->mailid = $mailid;
		$mailObject->sentby = $user->id;
		$db = JFactory::getDBO();
		$db->updateObject(acymailing_table('mail'),$mailObject,'mailid');

		acymailing_display(JText::sprintf('ADDED_QUEUE',$totalSub));
		acymailing_display(JText::sprintf('AUTOSEND_CONFIRMATION',$totalSub));

		$config = acymailing_config();
		$redirecturl = $config->get('redirect_send');
		if(empty($redirecturl)) $redirecturl = acymailing_completeLink("archive&listid=".JRequest::getInt('listid'),false,true);
		$js = "setTimeout('redirect()',2000); function redirect(){window.top.location.href = '$redirecturl'; }";
		$doc = JFactory::getDocument();
		$doc->addScriptDeclaration( $js );
		return false;
	}

	function _sendtest(){
		JRequest::checkToken() or die( 'Invalid Token' );
		if(!$this->checkAccess()) return;

		$mailid = acymailing_getCID('mailid');

		$receiver_type = JRequest::getVar('receiver_type','','','string');

		if(empty($mailid) OR empty($receiver_type)) return false;

		$mailer = acymailing_get('helper.mailer');
		$mailer->forceVersion = JRequest::getVar('test_html',1,'','int');
		$mailer->autoAddUser = true;
		$mailer->checkConfirmField = false;

		$receivers = array();
		if($receiver_type == 'user'){
			$user = JFactory::getUser();
			$receivers[] = $user->email;
		}elseif($receiver_type == 'other'){
			$receiverEntry = JRequest::getVar('test_email','','','string');
			if(substr_count($receiverEntry,'@')>1){
				$receivers = explode(' ',trim(preg_replace('# +#',' ',str_replace(array(';',','),' ',$receiverEntry))));
			}else{
				$receivers[] = trim($receiverEntry);
			}
		}else{
			$gid = substr($receiver_type,strpos($receiver_type,'_')+1);
			if(empty($gid)) return false;
			$db = JFactory::getDBO();
			$db->setQuery('SELECT email from '.acymailing_table('users',false).' WHERE gid = '.intval($gid));
			$receivers = acymailing_loadResultArray($db);
		}

		if(empty($receivers)){
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('NO_SUBSCRIBER'), 'notice');
			return false;
		}

		$result = true;
		foreach($receivers as $receiver){
			$result = $mailer->sendOne($mailid,$receiver) && $result;
		}

		return $result;
	}

	function stats(){
		if(!$this->isAllowed('statistics','manage')) return;
		if(!$this->checkAccess(false)) return;
		JRequest::setVar( 'layout', 'stats'  );
		return parent::display();
	}

	function statsclick(){
		if(!$this->isAllowed('statistics','manage') || !$this->isAllowed('subscriber','view')) return;
		if(!$this->checkAccess(false)) return;
		JRequest::setVar( 'layout', 'statsclick'  );
		return parent::display();
	}

	function detailstats(){
		if(!$this->isAllowed('statistics','manage') || !$this->isAllowed('subscriber','view')) return;
		if(!$this->checkAccess(false)) return;
		JRequest::setVar( 'layout', 'detailstats'  );
		return parent::display();
	}
}
