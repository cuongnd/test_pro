<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	07 June 2012
 * @file name	:	helpers/link.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Helper Class for sending Emails (jblance)
 */
defined('_JEXEC') or die('Restricted access');

class LinkHelper {
	
	// Basic universal href link
	function GetHrefLink($link, $name, $title = '', $rel = 'nofollow', $class = '', $anker = '', $attr = '') {
		return '<a ' . ($class ? 'class="' . $class . '" ' : '') . 'href="' . $link . ($anker ? ('#' . $anker) : '') . '" title="' . $title . '"' . ($rel ? ' rel="' . $rel . '"' : '') . ($attr ? ' ' . $attr : '') . '>' . $name . '</a>';
	}
	
	function GetProfileLink($userid, $name = null, $title ='', $rel = 'nofollow', $class = '') {
		if(!$name){
			//$profile = JblanceHelper::getUser($userid);
			$profile = JFactory::getUser($userid);
			$name = htmlspecialchars($profile->name, ENT_COMPAT, 'UTF-8');
		}
		if($userid == 0){
			$uclass = 'jwho-guest';
		} 
		else {
			$uclass = 'jwho-user';
		}
		if($userid > 0){
			$link = self::GetProfileURL($userid);
			if(!empty ($link))
				return LinkHelper::GetHrefLink($link, $name, $title, $rel, $uclass);
		}
		return "<span class=\"{$uclass}\">{$name}</span>";
	}
	
	function GetProfileURL($userid, $xhtml = true) {
		$profile = JblanceHelper::getProfile();
		return $profile->getProfileURL($userid, '', $xhtml);
	}
	
}