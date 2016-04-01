<?php
/**
 *
 * Product controller
 *
 * @package	VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: product.php 6521 2012-10-09 14:49:30Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if(!class_exists('VmController'))require(JPATH_VM_SITE.DS.'helpers'.DS.'vmcontroller.php');


/**
 * Product Controller
 *
 * @package    VirtueMart
 * @author
 */
class VirtuemartControllerProduct extends VmController {


	/**
	 * Shows the product add/edit screen
	 */
	public function edit($layout='edit') {
		parent::edit('product_edit');
	}

	/**
	 * We want to allow html so we need to overwrite some request data
	 *
	 * @author Max Milbers
	 */
	function save($data = 0){
		if($data===0)$data = JRequest::get('post');

		if(!class_exists('Permissions')) require(JPATH_VM_SITE.DS.'helpers'.DS.'permissions.php');
		if(Permissions::getInstance()->check('admin')){
			$data['product_desc'] = JRequest::getVar('product_desc','','post','STRING',2);
			$data['product_s_desc'] = JRequest::getVar('product_s_desc','','post','STRING',2);
		} else  {
			$data['product_desc'] = JRequest::getVar('product_desc','','post','STRING',2);
			$data['product_desc'] = JComponentHelper::filterText($data['product_desc']);
			$multix = Vmconfig::get('multix','none');
			if( $multix != 'none' ){
				unset($data['published']);
				unset($data['childs']);
			}

		}
		parent::save($data);
	}

	function saveproduct()
	{

		$model = VmModel::getModel('product');
		$app=JFactory::getApplication();
		$input=$app->input;
		$array_output=array();
		$src=$input->get('a_src','','string');
		$array_output['image']=$src;
		$virtuemart_product_id=$input->get('virtuemart_product_id','','int');
		$array_output['virtuemart_product_id']=$virtuemart_product_id;
		$table_media = VmTable::getInstance('medias','Table');
		$db=JFactory::getDbo();
		$table_media->virtuemart_media_id=0;
		$table_media->file_url=$src;
		$table_media->store();
		$virtuemart_media_id=$table_media->virtuemart_media_id;

		$query=$db->getQuery(true);
		$query->insert('#__virtuemart_product_medias')->columns('virtuemart_product_id,virtuemart_media_id')->values($virtuemart_product_id.','.$virtuemart_media_id);
		$db->setQuery($query);
		$db->query();
		echo $virtuemart_product_id;
		exit();

	}
	function getproduct()
	{

		$db = JFactory::getDbo ();
		if(!class_exists('VmTable'))require(JPATH_VM_SITE.DS.'helpers'.DS.'vmtable.php');
		$model_ratings=VmModel::getModel('ratings');
		$table_product=VmTable::getInstance('Products','Table');
		include(JPATH_COMPONENT_ADMINISTRATOR.'/helpers/simple_html_dom.php');


		$model_product=VmModel::getModel('product');
		$query = $db->getQuery ( true );
		$query->select ( 'virtuemart_product_id,product_name,content_ext' );
		$query->from ( '#__virtuemart_products_en_gb' );
		$query->where ( "ext_product_link <>'' AND da_kt=0" );
		//$query->where ( "virtuemart_product_id=68003" );
		$query->order ( 'virtuemart_product_id' );
		$db->setQuery ( $query );
		$items = $db->loadObjectList ();


		foreach ($items as $item)
		{
			if(trim($item->content_ext)=='')
				continue;
			$table_product->load($item->virtuemart_product_id);
			$virtuemart_product_id=$table_product->virtuemart_product_id;
			$table_product->product_price = Array (
					"0" => rand(100, 300)
			);

			//echo $item->content_ext;

			$str = $item->content_ext;

			$html = str_get_html($str);
			$table_product->product_desc= $html->find('span[class="listing-desc"]', 0)->innertext;

			//-----------save imagess
			$product_images=array();
			foreach($html->find('div[class="thumbnail"] a') as $img)
				$product_images[]= $img->href;
			$new_virtuemart_media_id=array();
			foreach ($product_images as $src)
			{

				$table_media = VmTable::getInstance('medias','Table');
				$table_media->virtuemart_media_id=0;
				$table_media->file_url=$src;
				$table_media->published=1;
				if(!$table_media->store())
				{
					echo "co loi luu media";
				}
				$new_virtuemart_media_id[]=$table_media->virtuemart_media_id;




			}
			if(count($new_virtuemart_media_id))
			{
				$query=$db->getQuery(true);
				$query->clear();
				$query->insert('#__virtuemart_product_medias(virtuemart_media_id,virtuemart_product_id)');
				foreach ($new_virtuemart_media_id as $virtuemart_media_id)
				{
					$query->values($virtuemart_media_id.','.$virtuemart_product_id);
				}
				$db->setQuery($query);
				$db->execute();
			}


			//-------------------------

			$product_ext=array();
			foreach($html->find('div[id="listing"] h2 img') as $img)
				$product_ext[]= $img->src;


			$fields_ext=array(
				'version',
				'rating',
				'compatibility',
				'reviews',
				'license',
				'type',
				'date_Added',
				'uses_updater'
			);
			$i=0;
			$fields_ext_value=array();

			foreach($html->find('div[class="fields_ext"] div[class="data"]') as $data)
			{
				$fields_ext_value[$fields_ext[$i]]=$data->innertext;
				$i++;
			}
			$table_product->compatibility=$fields_ext_value['compatibility'];
			$table_product->version=$fields_ext_value['version'];
			$table_product->reviews=$fields_ext_value['reviews'];
			$table_product->license=$fields_ext_value['license'];
			$table_product->type=$fields_ext_value['type'];


			$fields_orther=array(
					'link_download',
					'link_demo',
					'link_support',
					'link_document',

			);
			$i=0;
			$fields_orther_value=array();

			foreach($html->find('div[class="fields_buttons"] a') as $link)
			{
				$fields_orther_value[$fields_orther[$i]]=$link->href;
				$i++;
			}
			$table_product->link_download=$fields_orther_value['link_download'];
			$table_product->link_demo=$fields_orther_value['link_demo'];
			$table_product->link_support=$fields_orther_value['link_support'];
			$table_product->link_document=$fields_orther_value['link_document'];





			//-----------------
			$fields_dev=array(
					'developer',
					'website'

			);
			$i=0;
			$fields_dev_value=array();



			foreach($html->find('div[class="fields_dev"] div[class="data"] a') as $link)
			{
				$fields_dev_value[$fields_dev[$i]]=$link->href;
				$i++;
			}

			$table_product->developer=$fields_dev_value['developer'];
			$table_product->website=$fields_dev_value['website'];


			//----------------------



			$table_product->da_kt=1;


			//------------------------------

			foreach($html->find('div[class="reviews"] div[class="review"]') as $review)
			{
				$data_rating = array (
						'virtuemart_rating_review_id'=>0,
						'vote' => rand ( 3, 5 ),
						'counter' => 113,
						'published' => 1,
						'customer_name' => $review->find ( 'span[class="review-owner"] a', 0 )->innertext,
						'virtuemart_product_id' => $item->virtuemart_product_id,
						'created_by' => rand(10, 1000000)
				);
				$review->find ( 'div[id="reviewerprofile"]', 0 )->innertext='';
				$review->find ( 'div[class="owners-reply"]', 0 )->innertext='';
				$data_rating['comment']=$review->find( 'div[class="review-text"]', 0 )->innertext;

				if(!$model_ratings->saveRating($data_rating))
				{
					echo "loi";
					exit();
				}
			}
			//-----------------

			$model_product->store($table_product);

		}


		exit ();
	}

	function saveJS(){
		$data = JRequest::get('get');
		JRequest::setVar($data['token'], '1', 'post');

		JSession::checkToken() or jexit( 'Invalid Token save' );
		$model = VmModel::getModel($this->_cname);
		$id = $model->store($data);

		$errors = $model->getErrors();
		if(empty($errors)) {
			$msg = JText::sprintf('COM_VIRTUEMART_STRING_SAVED',$this->mainLangKey);
			$type = 'save';
		}
		else $type = 'error';
		foreach($errors as $error){
			$msg = ($error).'<br />';
		}
		$json['msg'] = $msg;
		if ($id) {
			$json['product_id'] = $id;

			$json['ok'] = 1 ;
		} else {
			$json['ok'] = 0 ;

		}
		echo json_encode($json);
		jExit();

	}

	/**
	 * This task creates a child by a given product id
	 *
	 * @author Max Milbers
	 */
	public function createChild(){
		$app = Jfactory::getApplication();

		/* Load the view object */
		$view = $this->getView('product', 'html');

		$model = VmModel::getModel('product');

		//$cids = JRequest::getVar('cid');
		$cids = JRequest::getVar($this->_cidName, JRequest::getVar('virtuemart_product_id',array(),'', 'ARRAY'), '', 'ARRAY');
		//jimport( 'joomla.utilities.arrayhelper' );
		JArrayHelper::toInteger($cids);

		foreach($cids as $cid){
			if ($id=$model->createChild($cid)){
				$msg = JText::_('COM_VIRTUEMART_PRODUCT_CHILD_CREATED_SUCCESSFULLY');
				$redirect = 'index.php?option=com_virtuemart&view=product&task=edit&product_parent_id='.$cids[0].'&virtuemart_product_id='.$id;
			} else {
				$msg = JText::_('COM_VIRTUEMART_PRODUCT_NO_CHILD_CREATED_SUCCESSFULLY');
				$msgtype = 'error';
				$redirect = 'index.php?option=com_virtuemart&view=product';
			}
		}
		$app->redirect($redirect, $msg, $msgtype);

	}

	/**
	* This task creates a child by a given product id
	*
	* @author Max Milbers
	*/
	public function createVariant(){

		$data = JRequest::get('get');
		// JRequest::setVar($data['token'], '1', 'post');
		JSession::checkToken() or JSession::checkToken('get') or jexit('Invalid Token, in ' . JRequest::getWord('task'));

		$app = Jfactory::getApplication();

		/* Load the view object */
		$view = $this->getView('product', 'html');

		$model = VmModel::getModel('product');

		//$cids = JRequest::getVar('cid');
		$cid = JRequest::getInt('virtuemart_product_id',0);

		if(empty($cid)){
			$msg = JText::_('COM_VIRTUEMART_PRODUCT_NO_CHILD_CREATED_SUCCESSFULLY');
// 			$redirect = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$cid;
		} else {
			;
			if ($id=$model->createChild($cid)){
				$msgtype='message';
				$msg = JText::_('COM_VIRTUEMART_PRODUCT_CHILD_CREATED_SUCCESSFULLY');
				$this->redirectPath .= '&task=edit&virtuemart_product_id='.$cid;
			} else {
				$msg = JText::_('COM_VIRTUEMART_PRODUCT_NO_CHILD_CREATED_SUCCESSFULLY');
				$msgtype = 'error';
			}
// 			vmdebug('$redirect '.$redirect);
			$this->setRedirect(null, $msg, $msgtype);
		}

	}

	public function massxref_sgrps(){

		$this->massxref('massxref');
	}

	public function massxref_sgrps_exe(){

		$db = JFactory::getDbo();
		$virtuemart_shoppergroup_ids = JRequest::getVar('virtuemart_shoppergroup_id',array(),'', 'ARRAY');
		JArrayHelper::toInteger($virtuemart_shoppergroup_ids);

		$session = JFactory::getSession();
		$cids = unserialize($session->get('vm_product_ids', array(), 'vm'));

		$productModel = VmModel::getModel('product');
		foreach($cids as $cid){
			$data = array('virtuemart_product_id' => $cid, 'virtuemart_shoppergroup_id' => $virtuemart_shoppergroup_ids);
			$data = $productModel->updateXrefAndChildTables ($data, 'product_shoppergroups');
		}
		$q = 'SELECT `shopper_group_name` FROM `#__virtuemart_shoppergroups` ';
		$q .= ' WHERE `virtuemart_shoppergroup_id` IN ('. implode(',', $virtuemart_shoppergroup_ids). ')';
		$db->setQuery($q);
		$names = $db->loadColumn();
		if (jRequest::getWord('format') == "json") $this->setRedirect(null, implode(',', $names) );
		// this is always done in json, no need to go in another task
	}

	public function massxref_cats(){
		$this->massxref('massxref');
	}
	// mass add
	public function massxref_cats_add(){
		$this->massxref_cats_exe(true);
	}

	//mass add or replace
	public function massxref_cats_exe($add=false){

		$db = JFactory::getDbo();
		$virtuemart_cat_ids = JRequest::getVar('cid',array(),'', 'ARRAY');
		JArrayHelper::toInteger($virtuemart_cat_ids);

		$session = JFactory::getSession();
		$cids = unserialize($session->get('vm_product_ids', array(), 'vm'));
		$productModel = VmModel::getModel('product');
		foreach($cids as $cid){
			// get old categries
			if ($add) {
				$q = 'SELECT `virtuemart_category_id` FROM `#__virtuemart_product_categories` ';
				$q .= ' WHERE `virtuemart_product_id` ='.(int)$cid;
				$db->setQuery($q);
				if (!$old_ids = $db->loadColumn()) $old_ids = array();
				$cat_ids = array_merge( $old_ids, $virtuemart_cat_ids);
				$cat_ids = array_unique($cat_ids, SORT_NUMERIC) ;
			} else $cat_ids = $virtuemart_cat_ids ;
			$data = array('virtuemart_product_id' => $cid, 'virtuemart_category_id' => $cat_ids );
			$data = $productModel->updateXrefAndChildTables ($data, 'product_categories',TRUE);
		}
		$q = 'SELECT `category_name` FROM `#__virtuemart_categories_' . VMLANG . '` ';
		$q .= ' WHERE `virtuemart_category_id` IN ('. implode(',', $virtuemart_cat_ids). ')';
		$db->setQuery($q);
		if ($results = $db->loadColumn()) $msg = (string)$add.' '.implode(',', $results+$cat_ids);
		else $msg = 'no results';
		if (jRequest::getWord('format') == "json") $this->setRedirect(null, $msg );
		// this is always done in json, no need to go in another task
	}

	/**
	 *
	 */
	public function massxref($layoutName){

		JSession::checkToken() or jexit('Invalid Token, in ' . JRequest::getWord('task'));

		$cids = JRequest::getVar('virtuemart_product_id',array(),'', 'ARRAY');
		JArrayHelper::toInteger($cids);
		if(empty($cids)){
			$session = JFactory::getSession();
			$cids = unserialize($session->get('vm_product_ids', '', 'vm'));
		} else {
			$session = JFactory::getSession();
			$session->set('vm_product_ids', serialize($cids),'vm');
		}

		if(!empty($cids)){
			$q = 'SELECT `product_name` FROM `#__virtuemart_products_' . VMLANG . '` ';
			$q .= ' WHERE `virtuemart_product_id` IN (' . implode(',', $cids) . ')';

			$db = JFactory::getDbo();
			$db->setQuery($q);

			$productNames = $db->loadColumn();
			vmInfo('COM_VIRTUEMART_PRODUCT_XREF_NAMES',implode(', ',$productNames));
		}

		$this->display();
	}

	/**
	 * Clone a product
	 *
	 * @author RolandD, Max Milbers
	 */
	public function CloneProduct() {
		// $mainframe = Jfactory::getApplication();

		/* Load the view object */
		$view = $this->getView('product', 'html');

		$model = VmModel::getModel('product');
		$msgtype = '';
		//$cids = JRequest::getInt('virtuemart_product_id',0);
		$cids = JRequest::getVar($this->_cidName, JRequest::getVar('virtuemart_product_id',array(),'', 'ARRAY'), '', 'ARRAY');
		//jimport( 'joomla.utilities.arrayhelper' );
		JArrayHelper::toInteger($cids);

		foreach($cids as $cid){
			if ($model->createClone($cid)) {
				$msg = JText::_('COM_VIRTUEMART_PRODUCT_CLONED_SUCCESSFULLY');
			} else {
				$msg = JText::_('COM_VIRTUEMART_PRODUCT_NOT_CLONED_SUCCESSFULLY');
				$msgtype = 'error';
			}
		}
		jRequest::setVar('task',null);
		$this->display();
		// $mainframe->redirect('index.php?option=com_virtuemart&view=product', $msg, $msgtype);
	}


	/**
	 * Get a list of related products, categories
	 * or customfields
	 * @author RolandD
	 * Kohl Patrick
	 */
	public function getData() {

		/* Create the view object. */
		$view = $this->getView('product', 'json');

		/* Now display the view. */
		$view->display(NULL);
	}

	/**
	 * Add a product rating
	 * @author RolandD
	 */
	public function addRating() {
		$mainframe = Jfactory::getApplication();

		/* Get the product ID */
		// 		$cids = array();
		$cids = JRequest::getVar($this->_cidName, JRequest::getVar('virtuemart_product_id',array(),'', 'ARRAY'), '', 'ARRAY');
		jimport( 'joomla.utilities.arrayhelper' );
		JArrayHelper::toInteger($cids);
		// 		if (!is_array($cids)) $cids = array($cids);

		$mainframe->redirect('index.php?option=com_virtuemart&view=ratings&task=add&virtuemart_product_id='.$cids[0]);
	}


	public function ajax_notifyUsers(){

		//vmdebug('updatestatus');

		$virtuemart_product_id = (int)JRequest::getVar('virtuemart_product_id', 0);
		$subject = JRequest::getVar('subject', '');
		$mailbody = JRequest::getVar('mailbody',  '');
		$max_number = (int)JRequest::getVar('max_number', '');

		$waitinglist = VmModel::getModel('Waitinglist');
		$waitinglist->notifyList($virtuemart_product_id,$subject,$mailbody,$max_number);
		exit;
	}

	public function ajax_waitinglist() {

		$virtuemart_product_id = (int)JRequest::getVar('virtuemart_product_id', 0);

		$waitinglistmodel = VmModel::getModel('waitinglist');
		$waitinglist = $waitinglistmodel->getWaitingusers($virtuemart_product_id);

		if(empty($waitinglist)) $waitinglist = array();

		echo json_encode($waitinglist);
		exit;

		/*
		$result = array();
		foreach($waitinglist as $wait) array_push($result,array("virtuemart_user_id"=>$wait->virtuemart_user_id,"notify_email"=>$wait->notify_email,'name'=>$wait->name,'username'=>$wait->username));

		echo json_encode($result);
		exit;
		*/
	}


}
// pure php no closing tag
