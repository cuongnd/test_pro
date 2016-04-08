<?php

defined('_JEXEC') or  die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/*
* Best selling Products module for VirtueMart
* @version $Id: mod_virtuemart_category.php 1160 2008-01-14 20:35:19Z soeren_nb $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) John Syben (john@webme.co.nz)
* Conversion to Mambo and the rest:
* 	@copyright (C) 2004-2005 Soeren Eberhardt
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*----------------------------------------------------------------------
* This code creates a list of the bestselling products
* and displays it wherever you want
*----------------------------------------------------------------------
*/
/* Load  VM fonction */

require_once('helper.php');
JHtml::_('jquery.framework');
if (!class_exists('VmConfig')) require(JPATH_ROOT . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
vmJsApi::jQuery();
vmJsApi::cssSite();
$class_sfx = $params->get('class_sfx', '');
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$html_layout=$params->get('config_layout.on_browser.html_layout','');

require JModuleHelper::getLayoutPath('mod_virtuemart_category', $html_layout);


?>