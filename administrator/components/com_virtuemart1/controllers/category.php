<?php
/**
*
* Category controller
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 6071 2012-06-06 15:33:04Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

if(!class_exists('VmController'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmcontroller.php');

/**
 * Category Controller
 *
 * @package    VirtueMart
 * @subpackage Category
 * @author jseros, Max Milbers
 */
class VirtuemartControllerCategory extends VmController {

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){

		$data = JRequest::get('post');

		$data['category_name'] = JRequest::getVar('category_name','','post','STRING',JREQUEST_ALLOWHTML);
		$data['category_description'] = JRequest::getVar('category_description','','post','STRING',JREQUEST_ALLOWHTML);

		parent::save($data);
	}
    //get Image From Template Monter

	/**
	* Save the category order
	*
	* @author jseros
	*/
	public function orderUp()
	{
		// Check token
		JSession::checkToken() or jexit( 'Invalid Token' );

		//capturing virtuemart_category_id
		$id = 0;
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( null, JText::_('COM_VIRTUEMART_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = VmModel::getModel('category');

		if ($model->orderCategory($id, -1)) {
			$msg = JText::_('COM_VIRTUEMART_ITEM_MOVED_UP');
		} else {
			$msg = $model->getError();
		}

		$this->setRedirect( null, $msg );
	}


	/**
	* Save the category order
	*
	* @author jseros
	*/
	public function orderDown()
	{
		// Check token
		JSession::checkToken() or jexit( 'Invalid Token' );

		//capturing virtuemart_category_id
		$id = 0;
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$this->setRedirect( null, JText::_('COM_VIRTUEMART_NO_ITEMS_SELECTED') );
			return false;
		}

		//getting the model
		$model = VmModel::getModel('category');

		if ($model->orderCategory($id, 1)) {
			$msg = JText::_('COM_VIRTUEMART_ITEM_MOVED_DOWN');
		} else {
			$msg = $model->getError();
		}

		$this->setRedirect( null, $msg );
	}
	function ajax_showfrom_addcategory()
	{
		$app=JFactory::getApplication();
		$input=$app->input;
		$category_name=$input->get('category_name','','string');

		$data=array(
				"vmlang" =>"en-GB",
				"category_name" =>$category_name,
				"published" =>"1",
				"shared" =>"1",
				"category_description" =>$category_name,
				"ordering" =>"0",
				"category_parent_id" =>"13919",
				"products_per_row" =>"0",
				"limit_list_step" =>"0",
				"limit_list_initial" =>"0",
				"category_template" =>"0",
				"category_layout" =>"0",
				"category_product_layout" =>"0",
				"customtitle" =>"",
				"metakey" =>"",
				"metadesc" =>"",
				"metarobot" =>"",
				"metaauthor" =>"",
				"virtuemart_vendor_id" =>"1",
				"searchMedia" =>"",
				"media_published" =>"1",
				"file_title" =>"",
				"file_description" =>"",
				"file_meta" =>"",
				"file_url" =>"images/stories/virtuemart/category/",
				"file_url_thumb" =>"",
				"media_roles" =>"file_is_displayable",
				"media_action" =>"0",
				"file_is_category_image" =>"1",
				"active_media_id" =>"0",
				"virtuemart_category_id" =>"0",
				"task" =>"apply",
				"option" =>"com_virtuemart",
				"boxchecked" =>"0",
				"controller" =>"category",
				"view" =>"category",
		);
		$model = VmModel::getModel('category');
		$cat=$model->store($data);
		echo $cat;

		exit();
	}
	function saveproduct()
	{

		$model = VmModel::getModel('product');
		$app=JFactory::getApplication();
		$input=$app->input;
		$product_name=$input->get('product_name','','string');
		$category_id=$input->get('category_id','','int');
		$product_link=$input->get('product_link','','string');
		$data = array (
				"vmlang" => "en-GB",
				"published" => "1",
				"product_sku" => $product_name,
				"product_name" => $product_name,
				"slug" => $product_name,
				"ext_product_link"=>$product_link,
				"product_url" => "",
				"product_special" => "0",
				"virtuemart_vendor_id" => "",
				"virtuemart_manufacturer_id" => "0",
				"categories" => Array (
						"0" => $category_id
				),

				"ordering" => "1",
				"layout" => "0",
				"product_price" => Array (
						"0" => rand(100, 300)
				),
				"product_currency" => Array (
						"0" => "147"
				),

				"product_tax_id" => Array (
						"0" => "0"
				),

				"product_discount_id" => Array (
						"0" => "0"
				),

				"product_price_publish_up" => Array (
						"0" => "0"
				),

				"product_price_publish_down" => Array (
						"0" => "0"
				),

				"price_quantity_start" => Array (
						"0" => ""
				),

				"price_quantity_end" => Array (
						"0" => ""
				),

				"price_shoppergroup_id" => Array (
						"0" => ""
				),



				"use_desired_price" => Array (
						"0" => "0"
				),

				"product_override_price" => Array (
						"0" => "0.00000"
				),

				"override" => Array (
						"0" => "0"
				),

				"intnotes" => "",
				"product_s_desc" => "",
				"product_desc" => "",
				"customtitle" => "",
				"metakey" => "",
				"metadesc" => "",
				"metarobot" => "",
				"metaauthor" => "",
				"product_in_stock" => "0",
				"product_ordered" => "0",
				"low_stock_notification" => "0",
				"step_order_level" => "",
				"min_order_level" => "0",
				"max_order_level" => "0",
				"product_available_date" => "2014-03-24 00:00:00",
				"product_availability" => "",
				"image" => "",
				"customer_email_type" => "customer",
				"notification_template" => "1",
				"notify_number" => "",
				"product_length" => "0.0000",
				"product_lwh_uom" => "M",
				"product_width" => "0.0000",
				"product_height" => "0.0000",
				"product_weight" => "0.0000",
				"product_weight_uom" => "KG",
				"product_packaging" => "0",
				"product_unit" => "KG",
				"product_box" => "",
				"searchMedia" => "",
				"media_published" => "1",
				"file_title" => "",
				"file_description" => "",
				"file_meta" => "",
				"file_url" => "images/stories/virtuemart/product/",
				"file_url_thumb" => "",
				"media_roles" => "file_is_displayable",
				"media_action" => "0",
				"file_is_product_image" => "1",
				"active_media_id" => "0",
				"save_customfields" => "1",
				"search" => "",
				"customlist" => "3",
				"task" => "apply",
				"option" => "com_virtuemart",
				"boxchecked" => "0",
				"controller" => "product",
				"view" => "product",
				"cb7a8387fd84d1445996cb92524726a0" => "1",
				"virtuemart_product_id" => "0",
				"product_parent_id" => "0"
		);
		$product= $model->store($data);
		echo json_encode($product);
		exit();

	}
	function getproduct()
	{

		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( 'virtuemart_product_id,ext_product_link,content_ext' );
		$query->from ( '#__virtuemart_products_en_gb' );
		$query->where ( "ext_product_link !='' AND da_kt=1" );
		$query->order ( 'virtuemart_product_id' );
		$db->setQuery ( $query );
		$item = $db->loadObject ();

		$query = $db->getQuery ( true );
		$query->update ( '#__virtuemart_products_en_gb' )->set ( 'da_kt=0')->where ( 'virtuemart_product_id=' . $item->virtuemart_product_id );
		$db->setQuery ( $query );
		$db->query();
		echo $item->content_ext;
		exit ();
	}
	function ajax_showfrom_addsubcategory()
	{

		$app=JFactory::getApplication();
		$input=$app->input;
		$category_name=$input->get('category_name','','string');
		$category_id=$input->get('category_id','','int');
		$link=$input->get('a_link','','string');

		$data=array(
				"vmlang" =>"en-GB",
				"category_name" =>$category_name,
				"published" =>"1",
				"shared" =>"1",
				"category_description" =>$category_name,
				"ordering" =>"0",
				"category_parent_id" =>$category_id,
				"products_per_row" =>"0",
				"limit_list_step" =>"0",
				"limit_list_initial" =>"0",
				"category_template" =>"0",
				"category_layout" =>"0",
				"category_product_layout" =>"0",
				"customtitle" =>"",
				"metakey" =>"",
				"metadesc" =>"",
				"metarobot" =>"",
				"metaauthor" =>"",
				"virtuemart_vendor_id" =>"1",
				"searchMedia" =>"",
				"media_published" =>"1",
				"file_title" =>"",
				"file_description" =>"",
				"file_meta" =>"",
				"file_url" =>"images/stories/virtuemart/category/",
				"file_url_thumb" =>"",
				"media_roles" =>"file_is_displayable",
				"media_action" =>"0",
				"file_is_category_image" =>"1",
				"active_media_id" =>"0",
				"virtuemart_category_id" =>"0",
				"task" =>"apply",
				"option" =>"com_virtuemart",
				"boxchecked" =>"0",
				"controller" =>"category",
				"view" =>"category",
				'link'=>$link
		);
		$model = VmModel::getModel('category');
		$cat=$model->store($data);
		echo $cat;

		exit();
	}

	/**
	* Save the categories order
	*/
	public function saveOrder()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit( 'Invalid Token' );

		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );	//is sanitized
		JArrayHelper::toInteger($cid);

		$model = VmModel::getModel('category');

		$order	= JRequest::getVar('order', array(), 'post', 'array');
		JArrayHelper::toInteger($order);

		if ($model->setOrder($cid,$order)) {
			$msg = JText::_('COM_VIRTUEMART_NEW_ORDERING_SAVED');
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect(null, $msg );
	}

}
