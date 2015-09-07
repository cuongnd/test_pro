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
include(ACYMAILING_BACK.'views'.DS.'data'.DS.'view.html.php');

class FrontdataViewFrontdata extends DataViewData
{

	var $ctrl='frontdata';

	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$doc->addStyleSheet( ACYMAILING_CSS.'frontendedition.css' );

		JHTML::_('behavior.tooltip');

		global $Itemid;
		$this->assignRef('Itemid',$Itemid);

		parent::display($tpl);
	}

}
