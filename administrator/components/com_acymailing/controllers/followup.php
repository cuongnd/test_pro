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

acymailing_get('controller.newsletter');
class FollowupController extends NewsletterController{
	var $aclCat = 'campaign';
	function listing(){
		$this->setRedirect(acymailing_completeLink('campaign',false,true));
	}
}
