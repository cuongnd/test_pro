<?php
/**
 *
 * Data module for shop raovat
 *
 * @package	VirtueMart
 * @subpackage raovat
 * @author RickG
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: raovat.php 8970 2015-09-06 23:19:17Z Milbo $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmModel'))require(JPATH_VM_SITE.DS.'helpers'.DS.'vmmodel.php');

/**
 * Model class for shop raovat
 *
 * @package	VirtueMart
 * @subpackage raovat
 */
class VirtueMartModelGianhang extends VmModel {


	/**
	 * constructs a VmModel
	 * setMainTable defines the maintable of the model
	 * @author Max Milbers
	 */
	function __construct() {
		parent::__construct();
		$this->setMainTable('raovat');
	}

	/**
	 * Retrieve the detail record for the current $id if the data has not already been loaded.
	 *
	 * @author Max Milbers
	 */
	function getItem($id=0) {
		return $this->getData($id);
	}


	/**
	 * Retireve a list of raovat from the database.
	 * This function is used in the backend for the raovat listing, therefore no asking if enabled or not
	 * @author Max Milbers
	 * @return object List of raovat objects
	 */
	function getItemList($search='') {
		//echo $this->getListQuery()->dump();
		$data=parent::getItems();
		return $data;
	}

	function getListQuery()
	{
		$db = JFactory::getDbo();
		$query=$db->getQuery(true);

		$query->select('raovat.*')
			->from('#__virtuemart_raovat AS raovat')
			->leftJoin('#__virtuemart_raovat_en_gb AS raovat_en_gb USING(virtuemart_raovat_id)')
		;
		$user = JFactory::getUser();
		$shared = '';
		if (vmAccess::manager()) {
			//$query->where('transferaddon.shared=1','OR');
		}
		$search=vRequest::getCmd('search', false);
		if (empty($search)) {
			$search = vRequest::getString('search', false);
		}
		// add filters
		if ($search) {
			$db = JFactory::getDBO();
			$search = '"%' . $db->escape($search, true) . '%"';
			$query->where('raovat.raovat_name LIKE '.$search);
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'raovat.virtuemart_raovat_id');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'raovat.ordering')
		{
			$orderCol = $db->quoteName('raovat.virtuemart_raovat_id') . ' ' . $orderDirn . ', ' . $db->quoteName('raovat.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Retireve a list of raovat from the database.
	 *
	 * This is written to get a list for selecting raovat. Therefore it asks for enabled
	 * @author Max Milbers
	 * @return object List of raovat objects
	 */

	function store(&$data){
		if(!vmAccess::manager('raovat')){
			vmWarn('Insufficient permissions to store raovat');
			return false;
		}
		return parent::store($data);
	}

	function remove($ids){
		if(!vmAccess::manager('raovat')){
			vmWarn('Insufficient permissions to remove raovat');
			return false;
		}
		return parent::remove($ids);
	}

}
// pure php no closing tag
