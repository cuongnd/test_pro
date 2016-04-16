<?php
/**
*
* manufacturer_medias table ( for manufacturers)
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
* @version $Id: manufacturer_medias.php 3002 2011-04-08 12:35:45Z alatak $
*/

defined('_JEXEC') or die();

if(!class_exists('VmTableXarray'))require(JPATH_VM_ADMINISTRATOR.'/helpers/vmtablexarray.php');

/**
 * Calculator table class
 * The class is is used to manage the media in the shop.
 *
 * @author Max Milbers
 * @package		VirtueMart
 */
class TableManufacturer_medias extends VmTableXarray {


	/**
	 * @author Max Milbers
	 * @param $db A database connector object
	 */
	function __construct(&$db){
		parent::__construct('#__virtuemart_manufacturer_medias', 'id', $db);

		$this->setPrimaryKey('virtuemart_manufacturer_id');
		$this->setSecondaryKey('virtuemart_media_id');
		$this->setOrderable();

	}

}