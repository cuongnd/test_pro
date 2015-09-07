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


class SendViewSend extends acymailingView
{
	function display($tpl = null)
	{
		$function = $this->getLayout();
		if(method_exists($this,$function)) $this->$function();

		parent::display($tpl);
	}

	function sendconfirm(){

		$mailid = acymailing_getCID('mailid');
		$mailClass = acymailing_get('class.mail');
		$listmailClass = acymailing_get('class.listmail');
		$queueClass = acymailing_get('class.queue');
		$mail = $mailClass->get($mailid);

		$values = new stdClass();
		$values->nbqueue = $queueClass->nbQueue($mailid);

		if(empty($values->nbqueue)){
			$lists = $listmailClass->getReceivers($mailid);
			$this->assignRef('lists',$lists);

			$db = JFactory::getDBO();
			$db->setQuery('SELECT count(subid) FROM `#__acymailing_userstats` WHERE `mailid` = '.intval($mailid));
			$values->alreadySent = $db->loadResult();
		}

		$this->assignRef('values',$values);
		$this->assignRef('mail',$mail);
	}

	function scheduleconfirm(){
		$mailid = acymailing_getCID('mailid');
		$listmailClass = acymailing_get('class.listmail');
		$mailClass = acymailing_get('class.mail');
		$this->assign('lists',$listmailClass->getReceivers($mailid));
		$this->assign('mail',$mailClass->get($mailid));
	}

	function addqueue(){
		$subid = JRequest::getInt('subid');
		if(empty($subid)) exit;

		$subscriberClass = acymailing_get('class.subscriber');
		$subscriber = $subscriberClass->getFull($subid);

		$db = JFactory::getDBO();
		$db->setQuery("SELECT `mailid`, `subject`,`alias`, `type` FROM `#__acymailing_mail` WHERE `type` NOT IN ('notification','autonews') OR `alias` = 'confirmation' AND `published` = 1 ORDER BY `type`,`subject` ASC ");
		$allEmails = $db->loadObjectList();

		$emailsToDisplay = array();
		$typeNews = '';
		foreach($allEmails as $oneMail){
			if($oneMail->type != $typeNews){
				if(!empty($typeNews)) $emailsToDisplay[] = JHTML::_('select.option',  '</OPTGROUP>');
				$typeNews = $oneMail->type;
				if($oneMail->type == 'notification'){
					$label = JText::_('NOTIFICATIONS');
				}elseif($oneMail->type == 'news'){
					$label = JText::_('NEWSLETTERS');
				}elseif($oneMail->type == 'followup'){
					$label = JText::_('FOLLOWUP');
				}elseif($oneMail->type == 'welcome'){
					$label = JText::_('MSG_WELCOME');
				}elseif($oneMail->type == 'unsub'){
					$label = JText::_('MSG_UNSUB');
				}else{
					$label = $oneMail->type;
				}
				$emailsToDisplay[] = JHTML::_('select.option',  '<OPTGROUP>', $label );
			}
			$emailsToDisplay[] = JHTML::_('select.option', $oneMail->mailid, $oneMail->subject.' ('.$oneMail->mailid.' : '.$oneMail->alias.')' );
		}
		$emailsToDisplay[] = JHTML::_('select.option',  '</OPTGROUP>');

		$emaildrop = JHTML::_('select.genericlist',  $emailsToDisplay, 'mailid', 'class="inputbox" size="1"', 'value', 'text',JRequest::getInt('mailid'));


		$this->assignRef('subscriber',$subscriber);
		$this->assignRef('emaildrop',$emaildrop);
	}

}
