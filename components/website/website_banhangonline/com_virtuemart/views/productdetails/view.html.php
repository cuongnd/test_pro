<?php



register_shutdown_function('VirtueMartViewProductdetails::display');
/**
 *
 * Product details view
 *
 * @package VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6477 2012-09-24 14:33:54Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// Load the view framework
if (! class_exists ( 'VmView' ))
	require (JPATH_VM_SITE . DS . 'helpers' . DS . 'vmview.php'); // Load the view framework
if (! class_exists ( 'ShopFunctions' ))
	require (JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctions.php');

/**
 * Product details
 *
 * @package VirtueMart
 * @author RolandD
 * @author Max Milbers
 */
class VirtueMartViewProductdetails extends VmView {

	/**
	 * Collect all data to show on the template
	 *
	 * @author RolandD, Max Milbers
	 */
	function display($tpl = null) {

        $layout=$this->getLayout();
        if($layout=='adminedit')
        {
            $product_model = VmModel::getModel('product');
            $this->item = $product_model->getItem();
            parent::display($tpl);
        }else {

            // TODO get plugins running
            // $dispatcher = JDispatcher::getInstance();
            // $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
            $show_prices = VmConfig::get('show_prices', 1);
            if ($show_prices == '1') {
                if (!class_exists('calculationHelper'))
                    require(JPATH_VM_SITE . DS . 'helpers' . DS . 'calculationh.php');
            }
            $this->assignRef('show_prices', $show_prices);

            $document = JFactory::getDocument();

            // add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
            vmJsApi::jPrice();

            $app = JFactory::getApplication();
            $pathway = $app->getPathway();
            $task = JRequest::getCmd('task');

            if (!class_exists('VmImage'))
                require(JPATH_VM_SITE . DS . 'helpers' . DS . 'image.php');

            // Load the product
            // $product = $this->get('product'); //Why it is sensefull to use this construction? Imho it makes it just harder
            $product_model = VmModel::getModel('product');
            $this->assignRef('product_model', $product_model);
            $virtuemart_product_idArray = JRequest::getVar('virtuemart_product_id', 0);
            if (is_array($virtuemart_product_idArray) and count($virtuemart_product_idArray) > 0) {
                $virtuemart_product_id = ( int )$virtuemart_product_idArray [0];
            } else {
                $virtuemart_product_id = ( int )$virtuemart_product_idArray;
            }

            $quantityArray = JRequest::getVar('quantity', array()); // is sanitized then
            JArrayHelper::toInteger($quantityArray);

            $quantity = 1;
            if (!empty ($quantityArray [0])) {
                $quantity = $quantityArray [0];
            }
            $onlyPublished = true;
            // set unpublished product when it's editable by its owner for preview
            if ($canEdit = ShopFunctions::can('edit', 'product')) {
                $onlyPublished = false;
            }

            $this->product = $product_model->getProduct($virtuemart_product_id, TRUE, TRUE, $onlyPublished, $quantity);

            $this->setLayout($tpl);

            parent::display();
        }
	}
    private function  downloadContentFromEnvatoForThisProduct(&$product)
    {
        //stop if not envato
        $virtuemart_product_id=$product->virtuemart_product_id;
        if($virtuemart_product_id<72686)
            return
        //stop if setbaiviet=1
        $setbaiviet=$product->setbaiviet;
        if($setbaiviet==1)
            return;


        //stop if has content
        $product_desc=$product->product_desc;
        $product_desc=JString::trim($product_desc);
        if($product_desc!='')
            return;

        $param=$product->param;
        $param=json_decode($param);
        $link=$param->link;

        //get content
        $html=JUtility::getCurl($link);

        require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
        $html = str_get_html($html);
        $user_html=$html->find('.user-html',0);
        $uri=JUri::getInstance($link);
        //get link demo
        $live_preview=$html->find('.live-preview',0)->href;
        if($live_preview!='')
        {

            $live_preview=$uri->getScheme().'://'.$uri->getHost().$live_preview;
            $live_preview_html=JUtility::getCurl($live_preview);
            $live_preview_html=str_get_html($live_preview_html);
            $live_preview=$live_preview_html->find('iframe',0)->src;
        }

        //fullscreen

        $fullscreen=$html->find('#fullscreen',0);
        if($fullscreen) $fullscreen=$fullscreen->find('a',0)->href;
        $fullscreen=$uri->getScheme().'://'.$uri->getHost().$fullscreen;

        $fullscreen=JUtility::getCurl($fullscreen);

        $fullscreen=str_get_html($fullscreen);
        if($fullscreen!='')
            $live_preview=$live_preview==''?$fullscreen->find('iframe',0)->src:$live_preview;

        //get Images
        $screenshots=$html->find('.screenshots',0)->href;

        if($screenshots!='')
        {
            $screenshots=$uri->getScheme().'://'.$uri->getHost().$screenshots;
            $screenshots=JUtility::getCurl($screenshots);

            $screenshots=str_get_html($screenshots);
            $screenshots=$screenshots->find('a',0)->href;
            $screenshots=JUtility::getCurl($screenshots);
            $screenshots=str_get_html($screenshots);

            if($screenshots!='')
            {
                $screenshots__list=$screenshots->find('.screenshots__list',0);
                if($screenshots__list!='')
                {
                    foreach($screenshots__list->find('a.screenshots__thumbnail img') as $img)
                    {

                        $src_image=$img->src;
                        $src_image=str_replace('.__thumbnail','',$src_image);
                        $fileName=basename($src_image);
                        $savePath='images/stories/virtuemart/product/big_image_product/'.$fileName;
                        //JUtility::saveImageFromUrl($src_image,JPATH_ROOT.'/'. $savePath);
                        ///save image to database
                        $db=JFactory::getDbo();
                        $query=$db->getQuery(true);
                        $query->insert('#__virtuemart_medias');
                        $query->columns('file_url,root_image');
                        $query->values($db->q($savePath).','.$db->q($src_image));
                        $db->setQuery($query);
                        $db->execute();
                        $virtuemart_media_id=$db->insertid();
                        if($virtuemart_media_id)
                        {
                            $query=$db->getQuery(true);
                            $query->insert('#__virtuemart_product_medias');
                            $query->columns('virtuemart_product_id,virtuemart_media_id');
                            $query->values($virtuemart_product_id.','.$virtuemart_media_id);
                            $db->setQuery($query);
                            $db->execute();
                            $product->virtuemart_media_id[]=$virtuemart_media_id;
                        }

                    }
                }
            }
        }

        //get tag
        $tags=array();
        foreach($html->find('.meta-attributes__table tr') as $tr);
        {
            $attr_name=$tr->find('.meta-attributes__attr-name',0)->innertext;
            if($attr_name=='Tags')
            {
                $meta_attributes=$tr->find('.meta-attributes__attr-detail',0);
                foreach($meta_attributes->find('a') as $tag_a)
                {
                    $tags[]=$tag_a->innertext;
                }
            }
        }
        $tags=implode(',',$tags);

        //save data
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->update('#__virtuemart_products_en_gb');
        $query->set('product_desc='.$db->q($user_html));
        $query->set('link_demo='.$db->q($live_preview));
        $query->set('metakey='.$db->q($tags));
        $query->set('setbaiviet=1');
        $query->where('virtuemart_product_id='.$virtuemart_product_id);
        $db->setQuery($query);
        $db->execute();
        $product->product_desc=$user_html;
        $product->link_demo=$live_preview;
        $product->linkdetail=$link;




    }
	private function showLastCategory($tpl) {
		$virtuemart_category_id = shopFunctionsF::getLastVisitedCategoryId ();
		$categoryLink = '';
		if ($virtuemart_category_id) {
			$categoryLink = '&virtuemart_category_id=' . $virtuemart_category_id;
		}
		$continue_link = JRoute::_ ( 'index.php?option=com_virtuemart&view=category' . $categoryLink, FALSE );

		$continue_link_html = '<a href="' . $continue_link . '" />' . JText::_ ( 'COM_VIRTUEMART_CONTINUE_SHOPPING' ) . '</a>';
		$this->assignRef ( 'continue_link_html', $continue_link_html );
		// Display it all
		parent::display ( $tpl );
	}
}

// pure php no closing tag