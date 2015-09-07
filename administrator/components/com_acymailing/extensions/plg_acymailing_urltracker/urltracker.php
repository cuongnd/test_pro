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

class plgAcymailingUrltracker extends JPlugin
{

	function plgAcymailingUrltracker(&$subject, $config){
		parent::__construct($subject, $config);
		if(!isset($this->params)){
			$plugin = JPluginHelper::getPlugin('acymailing', 'urltracker');
			$this->params = new JParameter( $plugin->params );
		}
	}

	function acymailing_replaceusertags(&$email,&$user,$send=true){

		if(!$email->sendHTML OR empty($user->subid) OR !acymailing_level(1)) return;

		$urlClass = acymailing_get('class.url');
		if($urlClass === null) return;
		$urls = array();

		if(!preg_match_all('#href[ ]*=[ ]*"(?!mailto:|\#|ymsgr:|callto:|file:|ftp:|webcal:|skype:)([^"]+)"#Ui',$email->body,$results)) return;

		foreach($results[1] as $i => $url){
			if(isset($urls[$results[0][$i]])) continue;

			$isFile = false;
			$extension = strtolower(substr($url,strrpos($url,'.')+1));
			if(in_array($extension,array('zip','png','gif','jpeg','jpg','doc','pdf','gz','docx','xls','xlsx'))){
				$isFile = true;
			}
			if(strpos($url,'?')){
				$isFile = false;
			}

			if(strpos($url,'utm_source') === false && !$isFile && ($this->params->get('trackingsystem','acymailing') == 'googleanalytics' || $this->params->get('trackingsystem','acymailing') == 'googleacy')){
				$args = array();
				$args[] = 'utm_source=newsletter_'.@$email->mailid;
				$args[] = 'utm_medium=email';
				$args[] = 'utm_campaign='.@$email->alias;
				if(strpos($url,'?')){ $mytracker = $url.'&'.implode('&',$args); }
				else{ $mytracker = $url.'?'.implode('&',$args); }
				$urls[$results[0][$i]] = str_replace($results[1][$i],$mytracker,$results[0][$i]);
				$url = $mytracker;
			}

			if($this->params->get('trackingsystem','acymailing') == 'acymailing' || $this->params->get('trackingsystem','acymailing') == 'googleacy'){
				if(strpos($url,ACYMAILING_LIVE) === false || $isFile){
					if(preg_match('#subid|passw|modify|\{|%7B#i',$url)) continue;
					$mytracker = $urlClass->getUrl($url,$email->mailid,$user->subid);
				}else{
					$extraParam = 'acm='.$user->subid.'_'.$email->mailid;
					$mytracker = strpos($url,'?') ? $url.'&'.$extraParam : $url.'?'.$extraParam;
				}

				if(empty($mytracker)) continue;
				$urls[$results[0][$i]] = str_replace($results[1][$i],$mytracker,$results[0][$i]);
			}
		}

		$email->body = str_replace(array_keys($urls),$urls,$email->body);

	}//endfct

	function onAcyDisplayTriggers(&$triggers){
		$triggers['clickurl'] = JText::_('ON_USER_CLICK');
	}

	 function onAcyDisplayFilters(&$type,$context="massactions"){

		if($this->params->get('displayfilter_'.$context,true) == false) return;


	 	$db = JFactory::getDBO();
	 	$db->setQuery('SELECT urlid, name FROM #__acymailing_url WHERE SUBSTRING(`name`,1,230) != SUBSTRING(`url`,1,230) ORDER BY name ASC');
	 	$allurls = $db->loadObjectList();

	 	if(empty($allurls)) return;

		$type['clickstats'] = JText::_('CLICK_STATISTICS');

		$return = '<div id="filter__num__clickstats">'.JText::_('CLICKED_LINK').' : '.JHTML::_('select.genericlist',  $allurls, "filter[__num__][clickstats][urlid]", 'onchange="countresults(__num__)" class="inputbox" size="1"', 'urlid', 'name').'</div>';

	 	return $return;
	 }

	 function onAcyProcessFilterCount_clickstats(&$query,$filter,$num){
		$this->onAcyProcessFilter_clickstats($query,$filter,$num);
		return JText::sprintf('SELECTED_USERS',$query->count());
	}

	function onAcyProcessFilter_clickstats(&$query,$filter,$num){
		$alias = 'url'.$num;
		$query->join[$alias] = '#__acymailing_urlclick as '.$alias.' on sub.subid = '.$alias.'.subid';
		$query->where[] = $alias.'.urlid = '.intval($filter['urlid']);
	}

}//endclass
