<?php
defined('_JEXEC') or  die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* @version $Id: mod_virtuemart_search.php 5171 2011-12-27 15:41:22Z alatak $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) 2011 Patrick Kohl
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*/
require_once('helper.php');

if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
vmJsApi::jQuery();
vmJsApi::cssSite();
if (!class_exists('ShopFunctions'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');
$categoryModel = VmModel::getModel('shortedCategory');
$categoryTree=$categoryModel->getShortCategoryTree();

$vendorModel = VmModel::getModel('vendor');
$vendors=$vendorModel->getVendors();
// Load the virtuemart main parse code
$button			 = $params->get('button', '');
$imagebutton	 = $params->get('imagebutton', '');
$button_pos		 = $params->get('button_pos', 'left');
$button_text	 = $params->get('button_text', JText::_('Search'));
$width			 = intval($params->get('width', 20));
$maxlength		 = $width > 20 ? $width : 20;
$text			 = $params->get('text', JText::_('search...'));
$set_Itemid		 = intval($params->get('set_itemid', 0));
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
if (!class_exists('VirtueMartCart'))
    require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');

$cart = VirtueMartCart::getCart();
$cart->array_search=json_decode($cart->array_search);
$category_id = $params->get('Parent_Category_id', 0);

if ( $params->get('filter_category', 0) ){
    $category_id = JRequest::getInt('virtuemart_category_id', 0);
} else {
    $category_id = 0 ;
}
$categoryModel = VmModel::getModel('shortedCategory');
$categoryTree=$categoryModel->getShortCategoryTree();
require(JModuleHelper::getLayoutPath('mod_virtuemart_search'));
?>
