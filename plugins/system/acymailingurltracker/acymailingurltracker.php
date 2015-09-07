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

class plgSystemAcymailingurltracker extends JPlugin
{

	function plgSystemAcymailingurltracker(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('system', 'acymailingurltracker');
			$this->params = new JParameter( $plugin->params );
		}
	}

	function onAfterInitialise(){
		if(!JRequest::getCmd('acm')) return;

		if(!preg_match('#^[0-9]+_[0-9]+$#',JRequest::getCmd('acm'))) return;

		$helperFile = rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
		if(!file_exists($helperFile) || !include_once($helperFile)) return;

		$vals = explode('_',JRequest::getCmd('acm'));

		$urlClass = acymailing_get('class.url');
		$urlObject = $urlClass->getCurrentUrl();

		if(empty($urlObject->urlid)) return;

		$urlClickClass = acymailing_get('class.urlclick');
		$urlClickClass->addClick($urlObject->urlid,$vals[1],$vals[0]);

		if($urlObject->url == $urlObject->name){
			$this->urlid = $urlObject->urlid;
		}

		unset($_GET['acm']);
		unset($_REQUEST['acm']);


	}

	function onAfterRender(){
		if(empty($this->urlid)) return;

		$urlClass = acymailing_get('class.url');
		$urlClass->saveCurrentUrlName($this->urlid);
	}

}//endclass
