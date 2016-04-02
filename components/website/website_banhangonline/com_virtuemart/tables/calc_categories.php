<?php
/**
*
* virtuemart_calc_categories table ( to map calc rules to shoppergroups)
*
* @package	VirtueMart
* @subpackage Calculation tool
* @author Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: calc_categories.php 3002 2011-04-08 12:35:45Z alatak $
*/

defined('_JEXEC') or die();

/**
 *
 * The class is an xref table
 *
 * @author Max Milbers
 * @package		VirtueMart
 */

if(!class_exists('VmTableXarray'))require(JPATH_VM_SITE.'/helpers/vmtablexarray.php');

class TableCalc_categories extends VmTableXarray {

	/**
	 * @author Max Milbers
	 * @param JDataBase $db database connector object
	 */
	function __construct(&$db){
		parent::__construct('#__virtuemart_calc_categories', 'id', $db);

		$this->setPrimaryKey('virtuemart_calc_id');
		$this->setSecondaryKey('virtuemart_category_id','calc_categories');

	}


}