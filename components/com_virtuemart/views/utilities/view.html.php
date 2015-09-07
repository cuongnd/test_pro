<?php
/**
 *
 * Description
 *
 * @package	VirtueMart
 * @subpackage
 * @author
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6564 2012-10-19 11:45:27Z kkmediaproduction $
 */

# Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

# Load the view framework
if(!class_exists('VmView'))require(JPATH_VM_SITE.DS.'helpers'.DS.'vmview.php');

/**
 * Default HTML View class for the VirtueMart Component
 * @todo Find out how to use the front-end models instead of the backend models
 */
class virtuemartViewutilities extends VmView {

	public function display($tpl = null) {


		parent::display($tpl);

	}
}
# pure php no closing tag