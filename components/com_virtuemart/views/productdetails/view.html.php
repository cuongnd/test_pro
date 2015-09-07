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
	require (JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');

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


		// TODO get plugins running
		// $dispatcher = JDispatcher::getInstance();
		// $limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$show_prices = VmConfig::get ( 'show_prices', 1 );
		if ($show_prices == '1') {
			if (! class_exists ( 'calculationHelper' ))
				require (JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'calculationh.php');
		}
		$this->assignRef ( 'show_prices', $show_prices );

		$document = JFactory::getDocument ();

		// add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
		vmJsApi::jPrice ();

		$app = JFactory::getApplication ();
		$pathway = $app->getPathway ();
		$task = JRequest::getCmd ( 'task' );

		if (! class_exists ( 'VmImage' ))
			require (JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'image.php');

			// Load the product
			// $product = $this->get('product'); //Why it is sensefull to use this construction? Imho it makes it just harder
		$product_model = VmModel::getModel ( 'product' );
		$this->assignRef ( 'product_model', $product_model );
		$virtuemart_product_idArray = JRequest::getVar ( 'virtuemart_product_id', 0 );
		if (is_array ( $virtuemart_product_idArray ) and count ( $virtuemart_product_idArray ) > 0) {
			$virtuemart_product_id = ( int ) $virtuemart_product_idArray [0];
		} else {
			$virtuemart_product_id = ( int ) $virtuemart_product_idArray;
		}

		$quantityArray = JRequest::getVar ( 'quantity', array () ); // is sanitized then
		JArrayHelper::toInteger ( $quantityArray );

		$quantity = 1;
		if (! empty ( $quantityArray [0] )) {
			$quantity = $quantityArray [0];
		}
		$onlyPublished = true;
		// set unpublished product when it's editable by its owner for preview
		if ($canEdit = ShopFunctions::can ( 'edit', 'product' )) {
			$onlyPublished = false;
		}

		$product = $product_model->getProduct ( $virtuemart_product_id, TRUE, TRUE, $onlyPublished, $quantity );
        //if this product is it of envato then download content
        $this->downloadContentFromEnvatoForThisProduct($product);
        $products=array($product);
        require_once JPATH_ROOT.'/modules/mod_virtuemart_product/helper.php';
        mod_virtuemart_product::addImageFromEnvato($products);
        $product=$products[0];
        if(trim($product->product_desc)!='')
        {
            require_once JPATH_ROOT.'/libraries/simplehtmldom_1_5/simple_html_dom.php';
            $product_desc = str_get_html($product->product_desc);
            foreach($product_desc->find('img') as $e)
            {
                if(!is_link($e->src))
                {
                    $e->src=JUri::root().$e->src;
                }

            }
            $product->product_desc=$product_desc;
        }

        if ($product && $canEdit) {
			if (! class_exists ( 'Permissions' ))
				require (JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'permissions.php');
			$vendor = Permissions::getInstance ()->isSuperVendor ();
			if ($product->virtuemart_vendor_id !== $vendor)
            {
				$product = null;
            }elseif (! $product->published)
				$app->enqueueMessage ( JText::_ ( 'COM_VIRTUEMART_ORDER_PRINT_PRODUCT_STATUS' ) . ' : ' . JText::_ ( 'COM_VIRTUEMART_UNPUBLISHED' ), 'warning' );
		}

		// vmSetStartTime('customs');
		// for($k=0;$k<count($product->customfields);$k++){
		// $custom = $product->customfields[$k];

		// vmTime('Customs','customs');
		// vmdebug('my second $product->customfields',$product->customfields);
		$last_category_id = shopFunctionsF::getLastVisitedCategoryId ();


		if (! empty ( $product->customfields )) {
			foreach ( $product->customfields as $k => $custom ) {
				if (! empty ( $custom->layout_pos )) {
					$product->customfieldsSorted [$custom->layout_pos] [] = $custom;
					unset ( $product->customfields [$k] );
				}
			}
			$product->customfieldsSorted ['normal'] = $product->customfields;
			unset ( $product->customfields );
		}

		$product->event = new stdClass ();
		$product->event->afterDisplayTitle = '';
		$product->event->beforeDisplayContent = '';
		$product->event->afterDisplayContent = '';

		if (VmConfig::get ( 'enable_content_plugin', 0 )) {

			// add content plugin //
			$dispatcher = & JDispatcher::getInstance ();
			JPluginHelper::importPlugin ( 'content' );
			$product->text = $product->product_desc;
			// jimport( 'joomla.html.parameter' );
			// $params = new JParameter('');
			$params = new JRegistry ();
			$product->event = new stdClass ();
			$results = $dispatcher->trigger ( 'onContentPrepare', array (
					'com_virtuemart.productdetails',
					&$product,
					&$params,
					0
			) );

			// More events for 3rd party content plugins
			// This do not disturb actual plugins, because we don't modify $product->text
			$res = $dispatcher->trigger ( 'onContentAfterTitle', array (
					'com_virtuemart.productdetails',
					&$product,
					&$params,
					0
			) );
			$product->event->afterDisplayTitle = trim ( implode ( "\n", $res ) );

			$res = $dispatcher->trigger ( 'onContentBeforeDisplay', array (
					'com_virtuemart.productdetails',
					&$product,
					&$params,
					0
			) );
			$product->event->beforeDisplayContent = trim ( implode ( "\n", $res ) );

			$res = $dispatcher->trigger ( 'onContentAfterDisplay', array (
					'com_virtuemart.productdetails',
					&$product,
					&$params,
					0
			) );
			$product->event->afterDisplayContent = trim ( implode ( "\n", $res ) );

			$product->product_desc = $product->text;
		}

        $xrefTable = $product_model->getTable ('product_fileupload');

        $product->virtuemart_media_file_Upload_id = $xrefTable->load ((int)$product->virtuemart_product_id);



        $product_model->addImages ( $product );
        require_once JPATH_BASE.'/administrator/components/com_virtuemart/helpers/product.php';
        $changeproduct=productHelper::saveFileUser($product->virtuemart_product_id);
        $product->download_free=$changeproduct->download_free;
        $product->link_download=$changeproduct->link_download;

		$this->assignRef ( 'product', $product );

		if (isset ( $product->min_order_level ) && ( int ) $product->min_order_level > 0) {
			$min_order_level = $product->min_order_level;
		} else {
			$min_order_level = 1;
		}
		$this->assignRef ( 'min_order_level', $min_order_level );
		if (isset ( $product->step_order_level ) && ( int ) $product->step_order_level > 0) {
			$step_order_level = $product->step_order_level;
		} else {
			$step_order_level = 1;
		}
		$this->assignRef ( 'step_order_level', $step_order_level );

		// Load the neighbours
		if (VmConfig::get ( 'product_navigation', 1 )) {
			$product->neighbours = $product_model->getNeighborProducts ( $product );
		}
		// Product vendor multiX
		if ($multix = Vmconfig::get ( 'multix', 'none' ) === 'admin') {
			$vendor_model = VmModel::getModel ( 'vendor' );
			$this->vendor = $vendor_model->getVendor ( $this->product->virtuemart_vendor_id );
			$this->vendor = $vendor_model->getVendor ( $this->product->virtuemart_vendor_id );
		} else
			$this->vendor = null;
			// echo 'multi'.$multix;
			// Load the category
		$category_model = VmModel::getModel ( 'category' );

		shopFunctionsF::setLastVisitedCategoryId ( $product->virtuemart_category_id );

		if ($category_model) {

			$category = $category_model->getCategory ( $product->virtuemart_category_id );

			$category_model->addImages ( $category, 1 );
			$this->assignRef ( 'category', $category );

			// Seems we dont need this anylonger, destroyed the breadcrumb
			if ($category->parents) {
				foreach ( $category->parents as $c ) {
					if (is_object ( $c ) and isset ( $c->category_name )) {
						$pathway->addItem ( strip_tags ( $c->category_name ), JRoute::_ ( 'index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $c->virtuemart_category_id, FALSE ) );
					} else {
						vmdebug ( 'Error, parent category has no name, breadcrumb maybe broken, category', $c );
					}
				}
			}

			$vendorId = JRequest::getInt ( 'virtuemart_vendor_id', null );
			$category->children = $category_model->getChildCategoryList ( $vendorId, $product->virtuemart_category_id );
			$category_model->addImages ( $category->children, 1 );
		}

		if (! empty ( $tpl )) {
			$format = $tpl;
		} else {
			$format = JRequest::getWord ( 'format', 'html' );
		}
		if ($format == 'html') {
			// Set Canonic link
			$document->addHeadLink ( $product->canonical, 'canonical', 'rel', '' );
		}

		$uri = JURI::getInstance ();
		// $pathway->addItem(JText::_('COM_VIRTUEMART_PRODUCT_DETAILS'), $uri->toString(array('path', 'query', 'fragment')));
		$pathway->addItem ( strip_tags ( $product->product_name ) );
		// Set the titles
		// $document->setTitle should be after the additem pathway
		if ($product->customtitle) {
			$document->setTitle ( strip_tags ( $product->customtitle ) );
		} else {
			$document->setTitle ( strip_tags ( ($category->category_name ? ($category->category_name . ' : ') : '') . $product->product_name ) );
		}
		$ratingModel = VmModel::getModel ( 'ratings' );
		$allowReview = $ratingModel->allowReview ( $product->virtuemart_product_id );
		$this->assignRef ( 'allowReview', $allowReview );

		$showReview = $ratingModel->showReview ( $product->virtuemart_product_id );
		$this->assignRef ( 'showReview', $showReview );

		if ($showReview) {

			$review = $ratingModel->getReviewByProduct ( $product->virtuemart_product_id );
			$this->assignRef ( 'review', $review );

			$rating_reviews = $ratingModel->getReviews ( $product->virtuemart_product_id );
			$this->assignRef ( 'rating_reviews', $rating_reviews );
		}

		$showRating = $ratingModel->showRating ( $product->virtuemart_product_id );
		$this->assignRef ( 'showRating', $showRating );

		if ($showRating) {
			$vote = $ratingModel->getVoteByProduct ( $product->virtuemart_product_id );
			$this->assignRef ( 'vote', $vote );

			$rating = $ratingModel->getRatingByProduct ( $product->virtuemart_product_id );
			$this->assignRef ( 'rating', $rating );
		}

		$allowRating = $ratingModel->allowRating ( $product->virtuemart_product_id );
		$this->assignRef ( 'allowRating', $allowRating );

		// Check for editing access
		$edit_link = $this->editLink ( 'product', $product->virtuemart_product_id, $product->created_by, 'edit', $product->virtuemart_vendor_id );
		$this->assignRef ( 'edit_link', $edit_link );

		// todo: atm same form for "call for price" and "ask a question". Title of the form should be different
		$askquestion_url = JRoute::_ ( 'index.php?option=com_virtuemart&view=productdetails&task=askquestion&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id . '&tmpl=component', FALSE );
		$this->assignRef ( 'askquestion_url', $askquestion_url );

		// Load the user details
		$user = JFactory::getUser ();
		$this->assignRef ( 'user', $user );

		// More reviews link
		$uri = JURI::getInstance ();
		$uri->setVar ( 'showall', 1 );
		$uristring = $uri->toString ();
		$this->assignRef ( 'more_reviews', $uristring );

		if ($product->metadesc) {
			$document->setDescription ( $product->metadesc );
		}
		if ($product->metakey) {
			$document->setMetaData ( 'keywords', $product->metakey );
		}

		if ($product->metarobot) {
			$document->setMetaData ( 'robots', $product->metarobot );
		}

		if ($app->getCfg ( 'MetaTitle' ) == '1') {
			$document->setMetaData ( 'title', $product->product_name ); // Maybe better product_name
		}
		if ($app->getCfg ( 'MetaAuthor' ) == '1') {
			$document->setMetaData ( 'author', $product->metaauthor );
		}

		$showBasePrice = Permissions::getInstance ()->check ( 'admin' ); // todo add config settings
		$this->assignRef ( 'showBasePrice', $showBasePrice );

		$productDisplayShipments = array ();
		$productDisplayPayments = array ();

		if (! class_exists ( 'vmPSPlugin' ))
			require (JPATH_VM_PLUGINS . DS . 'vmpsplugin.php');
		JPluginHelper::importPlugin ( 'vmshipment' );
		JPluginHelper::importPlugin ( 'vmpayment' );
		$dispatcher = JDispatcher::getInstance ();
		$returnValues = $dispatcher->trigger ( 'plgVmOnProductDisplayShipment', array (
				$product,
				&$productDisplayShipments
		) );
		$returnValues = $dispatcher->trigger ( 'plgVmOnProductDisplayPayment', array (
				$product,
				&$productDisplayPayments
		) );

		$this->assignRef ( 'productDisplayPayments', $productDisplayPayments );
		$this->assignRef ( 'productDisplayShipments', $productDisplayShipments );

		if (empty ( $category->category_template )) {
			$category->category_template = VmConfig::get ( 'categorytemplate' );
		}

		shopFunctionsF::setVmTemplate ( $this, $category->category_template, $product->product_template, $category->category_product_layout, $product->layout );

		shopFunctionsF::addProductToRecent ( $virtuemart_product_id );

		$currency = CurrencyDisplay::getInstance ();
		$this->assignRef ( 'currency', $currency );

		if (JRequest::getCmd ( 'layout', 'default' ) == 'notify')
			$this->setLayout ( 'notify' ); // Added by Seyi Awofadeju to catch notify layout

		if (JRequest::getCmd ( 'layout', 'default' ) == 'demo')
			$this->setLayout ( 'demo' ); // Added by Seyi Awofadeju to catch notify layout

		parent::display ($tpl );
	}
	function renderMailLayout($doVendor, $recipient) {
		$tpl = VmConfig::get ( 'order_mail_html' ) ? 'mail_html_notify' : 'mail_raw_notify';

		$this->doVendor = $doVendor;
		$this->fromPdf = false;
		$this->uselayout = $tpl;
		$this->subject = ! empty ( $this->subject ) ? $this->subject : JText::_ ( 'COM_VIRTUEMART_CART_NOTIFY_MAIL_SUBJECT' );
		$this->layoutName = $tpl;
		$this->setLayout ( $tpl );

		parent::display ();
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