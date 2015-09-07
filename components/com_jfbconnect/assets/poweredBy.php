<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
jimport('sourcecoast.utilities');

//Powered By
$showPoweredBy = $params->get('showPoweredByLink');
$showJfbcPoweredBy = (($showPoweredBy == '2' && JFBCFactory::config()->get('show_powered_by_link')) || ($showPoweredBy == '1'));

if($showJfbcPoweredBy)
{
    //Affiliate ID
    $link = SCSocialUtilities::getAffiliateLink(JFBCFactory::config()->get('affiliate_id'), EXT_JFBCONNECT);

    SCStringUtilities::loadLanguage('com_jfbconnect');

    echo '<div class="powered-by">'.JText::_('COM_JFBCONNECT_POWERED_BY').' <a target="_blank" href="'.$link.'" title="Facebook for Joomla">JFBConnect</a></div>';
}