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

class autonewsHelper{

	var $messages = array();
	var $mailClass = null;
	var $dispatcher = null;
	var $time;

	function generate(){
		$db = JFactory::getDBO();
		$this->time = time();
		$query = 'SELECT `mailid` FROM '.acymailing_table('mail').' WHERE `type` = \'autonews\' AND `published`= 1 AND `senddate` < '.$this->time;
		$db->setQuery($query);

		$autonews = acymailing_loadResultArray($db);


		if(empty($autonews)) return false;

		$this->mailClass = acymailing_get('class.mail');

		JPluginHelper::importPlugin('acymailing');
		$this->dispatcher = JDispatcher::getInstance();

		foreach($autonews as $mailid){
			$oneAutonews = $this->mailClass->get($mailid);
			if(empty($oneAutonews->params['lastgenerateddate'])) $oneAutonews->params['lastgenerateddate'] = $this->time-$oneAutonews->frequency;
			if(!$this->_generatingStatus($oneAutonews)) continue;
			$this->_generateAutoNews($oneAutonews);
		}

		return true;
	}


	function _generateAutoNews($newNewsletter){

		$newNewsletter->senddate = $this->time;
		$newNewsletter->type = 'news';
		if(!empty($newNewsletter->params['generate'])) $newNewsletter->published = 2;
		else $newNewsletter->published = 0;

		$notification = $newNewsletter->params['generateto'];

		$mailidModel = $newNewsletter->mailid;
		unset($newNewsletter->mailid);
		unset($newNewsletter->username);
		unset($newNewsletter->name);
		unset($newNewsletter->email);

		$issueNb = $newNewsletter->params['issuenb'];
		$newNewsletter->body = str_replace('{issuenb}',$issueNb,$newNewsletter->body);
		$newNewsletter->altbody = str_replace('{issuenb}',$issueNb,$newNewsletter->altbody);
		$newNewsletter->subject = str_replace('{issuenb}',$issueNb,$newNewsletter->subject);

		unset($newNewsletter->template);
		$newNewsletter->mailid = $this->mailClass->save($newNewsletter);

		$this->dispatcher->trigger('acymailing_replacetags',array(&$newNewsletter,false));
		$this->mailClass->save($newNewsletter);

		$query = 'INSERT IGNORE INTO '.acymailing_table('listmail').' (mailid,listid) SELECT '.$newNewsletter->mailid.', b.`listid` FROM '.acymailing_table('listmail').' as b';
		$query .= ' WHERE b.mailid = '.$mailidModel;

		$db = JFactory::getDBO();
		$db->setQuery($query);
		$db->query();

		$this->messages[] = JText::sprintf('NEWSLETTER_GENERATED',$newNewsletter->mailid,'<b><i>'.$newNewsletter->subject.'</i></b>');

		if(!empty($notification)){
			$app = JFactory::getApplication();
			$mailer = acymailing_get('helper.mailer');
			$mailer->report = $app->isAdmin();
			$mailer->autoAddUser = true;
			$mailer->checkConfirmField = false;
			$mailer->addParam('mailid',$newNewsletter->mailid);
			$mailer->addParam('subject',$newNewsletter->subject);
			$mailer->addParam('body',acymailing_absoluteURL($newNewsletter->body));
			$mailer->addParam('issuenb',$issueNb);
			$mailer->forceTemplate = $newNewsletter->tempid;
			$mailer->sendOne('notification_autonews',$notification);
		}

	}


	function _generatingStatus(&$oneAutonews){
		$results = $this->dispatcher->trigger('acymailing_generateautonews',array(&$oneAutonews));
		$return = true;
		foreach($results as $oneResult){
			if(isset($oneResult->status) && !$oneResult->status){
				$return = false;
				$this->messages[] = JText::sprintf('NEWSLETTER_NOT_GENERATED',$oneAutonews->mailid,$oneResult->message);
				break;
			}
		}

		$newMail = new stdClass();
		$newMail->mailid = $oneAutonews->mailid;
		if($oneAutonews->frequency >= 2592000 AND $oneAutonews->frequency%2592000 == 0){
			$newMail->senddate = mktime(date("H",$oneAutonews->senddate),date("i",$oneAutonews->senddate),date("s",$oneAutonews->senddate),date("n",$oneAutonews->senddate)+($oneAutonews->frequency/2592000),date("j",$oneAutonews->senddate),date("Y",$oneAutonews->senddate));
		}else{
			$newMail->senddate = $oneAutonews->senddate + $oneAutonews->frequency;
		}

		$newMail->params = $oneAutonews->params;
		if($newMail->senddate < $this->time OR $newMail->senddate > $this->time+2*$oneAutonews->frequency) $newMail->senddate = $this->time + $oneAutonews->frequency;

		if($return){
			$newMail->params['lastgenerateddate'] = $this->time;
			$newMail->params['issuenb'] = empty($newMail->params['issuenb']) ? 1 : $newMail->params['issuenb']+1;
		}

		$this->mailClass->save($newMail);

		return $return;
	}

}//endclass
