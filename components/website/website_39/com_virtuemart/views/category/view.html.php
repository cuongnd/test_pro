<?php

/**
 *
 * Handle the category view
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6504 2012-10-05 09:40:59Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView')) require(JPATH_VM_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Handle the category view
 *
 * @package VirtueMart
 * @author RolandD
 * @todo set meta data
 * @todo add full path to breadcrumb
 */
class VirtuemartViewCategory extends VmView
{

    public function display($tpl = null)
    {
		$layout=$this->getLayout();
		if($layout=='manager')
        {
            $app=JFactory::getApplication();
            $input=$app->input;
            $virtuemart_category_id=$input->get('virtuemart_category_id',0,'int');
            if($virtuemart_category_id) {
                require_once JPATH_VM_SITE . DS . 'helpers/vatgia.php';

                vm_vatgia_helper::import_product_vatgia_by_virtuemart_category_id($virtuemart_category_id);
            }
            $productModel = VmModel::getModel('product');
            $this->items = $productModel->getItemList();
            $this->pagination = $productModel->getPagination();
           parent::display($tpl);
        }else {
            $show_prices = VmConfig::get('show_prices', 1);
            if ($show_prices == '1') {
                if (!class_exists('calculationHelper')) require(JPATH_VM_SITE . DS . 'helpers' . DS . 'calculationh.php');
            }

            $input = JFactory::getApplication()->input;
            if (!class_exists('CurrencyDisplay')) require(JPATH_VM_SITE . DS . 'helpers' . DS . 'currencydisplay.php');
            $this->assignRef('show_prices', $show_prices);

            // add javascript for price and cart, need even for quantity buttons, so we need it almost anywhere
            vmJsApi::jPrice();

            $document = JFactory::getDocument();

            $app = JFactory::getApplication();
            $pathway = $app->getPathway();


            $categoryModel = VmModel::getModel('shortedcategory');

            $productModel = VmModel::getModel('product');
            $categoryId = JRequest::getInt('virtuemart_category_id', ShopFunctionsF::getLastVisitedCategoryId());

            $virtuemart_manufacturer_id = JRequest::getInt('virtuemart_manufacturer_id', 0);

            $this->setCanonicalLink($tpl, $document, $categoryId);
            // $vendorId = JRequest::getInt('virtuemart_vendor_id', 1);
            $vendorId = JRequest::getInt('virtuemart_vendor_id', null);
            // Load the products in the given category
            $fromSearch = $input->get('from_search', 0, 'int');
            if ($fromSearch) {
                $keyword = $input->get('keyword', '', 'string');
                $sorting = $input->get('sorting', '', 'string');
                $type = $input->get('type', 0, 'int');
                $downloadFree = $input->get('download_free', 0, 'int');
                $priceRatesSlider = $input->get('price_rates_slider', '', 'string');
                $categoryIds = array();
                $category_id = $input->get('category_id', 0, 'int');
                $category_extension_id = $input->get('category_extension_id', 0, 'int');
                $website_template_id = $input->get('website_template_id', 0, 'int');
                $cms_template_id = $input->get('cms_template_id', 0, 'int');
                $e_commerce_templates_id = $input->get('e_commerce_templates_id', 0, 'int');
                $flash_media_id = $input->get('flash_media_id', 0, 'int');
                if ($category_id && $type == 0)
                    $categoryIds[] = $category_id;

                if ($category_extension_id && $type == 1)
                    $categoryIds[] = $category_extension_id;
                $vendor_id = $input->get('vendor_id', 0, 'int');
                //gan cac gia tri tim kiem vao cart
                if (!class_exists('VirtueMartCart'))
                    require(JPATH_VM_SITE . DS . 'helpers' . DS . 'cart.php');
                $cart = VirtueMartCart::getCart();
                $array_search = array();
                $array_search['category_id'] = $category_id;
                $array_search['category_extension_id'] = $category_extension_id;
                $array_search['website_template_id'] = $website_template_id;
                $array_search['cms_template_id'] = $cms_template_id;
                $array_search['e_commerce_templates_id'] = $e_commerce_templates_id;
                $array_search['flash_media_id'] = $flash_media_id;
                $array_search['vendor_id'] = $vendor_id;
                $array_search['keyword'] = $keyword;
                $array_search['sorting'] = $sorting;
                $array_search['type'] = $type;
                $array_search['download_free'] = $downloadFree;
                $array_search['price_rates_slider'] = $priceRatesSlider;
                $cart->array_search = json_encode($array_search);
                $cart->setCartIntoSession();
                $array_sorting = array(
                    'price_lh' => 'pp.product_price'
                , 'price_hl' => 'pp.product_price DESC'
                , 'alpha' => 'pp.product_price'
                , 'popular' => 'pp.product_price'
                , 'stars_lh' => 'pp.product_price'
                , 'stars_hl' => 'pp.product_price'
                );
                $xrefTable = $productModel->getTable('product_categories');

                $products = $productModel->searchProductsInCategory($keyword, $categoryIds, $vendor_id, $array_sorting[$sorting], $downloadFree, $vendor_id);

                foreach ($products as $key => $product) {

                    $product->virtuemart_category_id = $xrefTable->load((int)$product->virtuemart_product_id);
                    $unset = true;
                    if ($website_template_id && in_array($website_template_id, $product->virtuemart_category_id)) {
                        $unset = false;
                    }
                    if ($cms_template_id && in_array($cms_template_id, $product->virtuemart_category_id)) {
                        $unset = false;
                    }
                    if ($e_commerce_templates_id && in_array($e_commerce_templates_id, $product->virtuemart_category_id)) {
                        $unset = false;
                    }
                    if ($flash_media_id && in_array($flash_media_id, $product->virtuemart_category_id)) {
                        $unset = false;
                    }
                    $tempTotal = $website_template_id + $cms_template_id + $e_commerce_templates_id + $flash_media_id;
                    if (!$tempTotal) {
                        $unset = false;
                    }
                    if ($unset) {
                        unset($products[$key]);
                    }

                }
            } else {
                require_once JPATH_VM_SITE . DS . 'helpers/vatgia.php';
                vm_vatgia_helper::import_product_vatgia_by_virtuemart_category_id($categoryId);

                $products = $productModel->getProductsInCategory($categoryId, $vendorId);

            }
            require_once JPATH_ROOT . '/modules/mod_virtuemart_product/helper.php';
            //mod_virtuemart_product::addImageFromEnvato($products);

//        $list_products=array();
            if (!class_exists('Img2Thumb')) require_once JPATH_BASE . '/administrator/components/com_virtuemart/helpers/img2thumb.php';
            if (!class_exists('productHelper')) require_once JPATH_BASE . '/administrator/components/com_virtuemart/helpers/product.php';

            $file_url_folder_thumb = JPATH_BASE . '/' . VmConfig::get('forSale_path_thumb');
            $db = JFactory::getDbo();
            if (!class_exists('JUserHelper')) {

                require JPATH_BASE . '/libraries/joomla/user/helper.php';
            }
            $xrefTable = $productModel->getTable('product_fileupload');
            $xrefTable_media = $productModel->getTable('product_medias');


            $this->assignRef('products', $products);
            $currency = CurrencyDisplay::getInstance();
            $this->assignRef('currency', $currency);

            // Add feed links
            if ($products && VmConfig::get('feed_cat_published', 0) == 1) {
                $link = '&format=feed&limitstart=';
                $attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
                $document->addHeadLink(JRoute::_($link . '&type=rss', FALSE), 'alternate', 'rel', $attribs);
                $attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
                $document->addHeadLink(JRoute::_($link . '&type=atom', FALSE), 'alternate', 'rel', $attribs);
            }
            $showBasePrice = Permissions::getInstance()->check('admin'); //todo add config settings
            $this->assignRef('showBasePrice', $showBasePrice);
            //set this after the $categoryId definition
            $paginationAction = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $categoryId, FALSE);

            $this->assignRef('paginationAction', $paginationAction);

            shopFunctionsF::setLastVisitedCategoryId($categoryId);
            shopFunctionsF::setLastVisitedManuId($virtuemart_manufacturer_id);


            if ($categoryId !== -1) {
                $vendorId = JRequest::getInt('virtuemart_vendor_id', 1);

                $category = $categoryModel->getCategory($categoryId);

            }
            //get menu_item_id product detail
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $menu_active = $menu->getActive();
            $configviewlayout = $menu_active->configviewlayout;
            $this->menu_item_product_detail = $configviewlayout->get('menu_item_product_detail', 0);

            //
            $perRow = empty($category->products_per_row) ? VmConfig::get('products_per_row', 3) : $category->products_per_row;
            $this->assignRef('perRow', $perRow);

            $pagination = $productModel->getPagination($perRow);

            $this->assignRef('vmPagination', $pagination);

            if (!empty($category)) {

                if ((!empty($categoryId) and $categoryId !== -1) and (empty($category->slug) or !$category->published)) {

                    if (empty($category->slug)) {
                        vmInfo(JText::_('COM_VIRTUEMART_CAT_NOT_FOUND'));
                    } else {
                        if ($category->virtuemart_id !== 0 and !$category->published) {
                            vmInfo('COM_VIRTUEMART_CAT_NOT_PUBL', $category->category_name, $categoryId);
                            //return false;
                        }
                    }

                    $categoryLink = '';
                    if ($category->category_parent_id) {
                        $categoryLink = '&view=category&virtuemart_category_id=' . $category->category_parent_id;
                    } else {
                        $last_category_id = shopFunctionsF::getLastVisitedCategoryId();
                        if (!$last_category_id or $categoryId == $last_category_id) {
                            $last_category_id = JRequest::getInt('virtuemart_category_id', false);
                        }
                        if ($last_category_id and $categoryId != $last_category_id) {
                            $categoryLink = '&view=category&virtuemart_category_id=' . $last_category_id;
                        }
                    }
                    $app->redirect(JRoute::_('index.php?option=com_virtuemart' . $categoryLink . '&error=404', FALSE));

                    return;
                }

                //No redirect here, category id = 0 means show ALL categories! note by Max Milbers
                /*		if(empty($category->virtuemart_vendor_id) && $search == null ) {
                            $app -> enqueueMessage(JText::_('COM_VIRTUEMART_CATEGORY_NOT_FOUND'));
                            $app -> redirect( 'index.php');
                        }*/

                $cache = JFactory::getCache('com_virtuemart', 'callback');
                //$category->children=$cache->call(array('VirtueMartModeldshortedCategory', 'getChildCategoryList'), $vendorId, $categoryId);
                $category->children = VirtueMartModelShortedCategory::getChildCategoryList($vendorId, $categoryId);


                if (VmConfig::get('enable_content_plugin', 0)) {

                    // add content plugin //
                    $dispatcher = JDispatcher::getInstance();
                    JPluginHelper::importPlugin('content');
                    $params = new JRegistry;
                    $category->event = new stdClass;
                    $category->catid = $category->virtuemart_category_id;
                    $category->id = null;
                    $category->title = $category->category_name;
                    $category->text = $category->category_description;
                    $results = $dispatcher->trigger('onContentPrepare', array('com_virtuemart.category', &$category, &$params, 0));
                    // More events for 3rd party content plugins
                    // This do not disturb actual plugins, because we don't modify $product->text
                    $res = $dispatcher->trigger('onContentAfterTitle', array('com_virtuemart.category', &$category, &$params, 0));
                    $category->event->afterDisplayTitle = trim(implode("\n", $res));

                    $res = $dispatcher->trigger('onContentBeforeDisplay', array('com_virtuemart.category', &$category, &$params, 0));
                    $category->event->beforeDisplayContent = trim(implode("\n", $res));

                    $res = $dispatcher->trigger('onContentAfterDisplay', array('com_virtuemart.category', &$category, &$params, 0));
                    $category->event->afterDisplayContent = trim(implode("\n", $res));
                    $category->category_description = $category->text;
                    $category->category_name = $category->title;
                }


                $rule = 160;
                $posCommas = strpos($category->metakey, ',', $rule);
                $metaKey = substr($category->metakey, 0, $posCommas);

                $document->setDescription($metaKey);

                $nextPos = $posCommas + $rule;
                $characterCommas = strpos($category->metakey, ',', $nextPos);
                $metaKey = substr($category->metakey, $nextPos, $characterCommas);


                if ($category->metakey) {

                    $document->setMetaData('keywords', $metaKey);
                }
                $keyWordContent = substr($category->metakey, $characterCommas + 1);
                $this->assignRef('keyWordContent', $keyWordContent);

                if ($category->metarobot) {
                    $document->setMetaData('robots', $category->metarobot);
                }


                if ($app->getCfg('MetaAuthor') == '1') {
                    $document->setMetaData('author', $category->metaauthor);
                }

                if (empty($category->category_template)) {
                    $category->category_template = VmConfig::get('categorytemplate');
                }

                shopFunctionsF::setVmTemplate($this, $category->category_template, 0, $category->category_layout);
            } else {
                //Backward compatibility
                if (!isset($category)) {
                    $category = new stdClass();
                    $category->category_name = '';
                    $category->category_description = '';
                    $category->haschildren = false;
                }
            }

            $this->assignRef('category', $category);

            // Check for editing access
            //$edit_link = $this->editLink('category',$category->virtuemart_category_id,$category->created_by);

            $this->assignRef('edit_link', $edit_link);
            // Set the titles

            if (!empty($category->customtitle)) {
                $title = strip_tags($category->customtitle);
            } elseif (!empty($category->category_name)) {
                $title = strip_tags($category->category_name);
            } else {
                $title = $this->setTitleByJMenu($app);
            }

            if (JRequest::getInt('error')) {
                $title .= ' ' . JText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
            }
            if (!empty($keyword)) {
                $title .= ' (' . $keyword . ')';
            }

            if ($virtuemart_manufacturer_id and !empty($products[0])) $title .= ' ' . $products[0]->mf_name;
            $document->setTitle($title);
            // Override Category name when viewing manufacturers products !IMPORTANT AFTER page title.
            if (JRequest::getInt('virtuemart_manufacturer_id') and !empty($products[0]) and isset($category->category_name)) $category->category_name = $products[0]->mf_name;

            if ($app->getCfg('MetaTitle') == '1') {
                $document->setMetaData('title', $title);
            }

            // set search and keyword
            if ($keyword = vmRequest::uword('keyword', '0', ' ,-,+,.,_')) {
                $pathway->addItem($keyword);
                //$title .=' ('.$keyword.')';
            }
            parent::display($tpl);
        }

    }



    public function setTitleByJMenu($app)
    {
        $menus = $app->getMenu();
        $menu = $menus->getActive();
        $title = 'VirtueMart Category View';
        if ($menu) $title = $menu->title;
        // $title = $this->params->get('page_title', '');
        // Check for empty title and add site name if param is set
        if (empty($title)) {
            $title = $app->getCfg('sitename');
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        } elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }
        return $title;
    }

    public function setCanonicalLink($tpl, $document, $categoryId)
    {
        // Set Canonic link
        if (!empty($tpl)) {
            $format = $tpl;
        } else {
            $format = JRequest::getWord('format', 'html');
        }
        if ($format == 'html') {

            $document->addHeadLink(JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $categoryId, FALSE), 'canonical', 'rel', '');

        }
    }

    /*
     * generate custom fields list to display as search in FE
     */
    public function getSearchCustom()
    {

        $emptyOption = array('virtuemart_custom_id' => 0, 'custom_title' => JText::_('COM_VIRTUEMART_LIST_EMPTY_OPTION'));
        $this->_db = JFactory::getDBO();
        $this->_db->setQuery('SELECT `virtuemart_custom_id`, `custom_title` FROM `#__virtuemart_customs` WHERE `field_type` ="P"');
        $this->options = $this->_db->loadAssocList();

        if ($this->custom_parent_id = JRequest::getInt('custom_parent_id', 0)) {
            $this->_db->setQuery('SELECT `virtuemart_custom_id`, `custom_title` FROM `#__virtuemart_customs` WHERE custom_parent_id=' . $this->custom_parent_id);
            $this->selected = $this->_db->loadObjectList();
            $this->searchCustomValues = '';
            foreach ($this->selected as $selected) {
                $this->_db->setQuery('SELECT `custom_value` as virtuemart_custom_id,`custom_value` as custom_title FROM `#__virtuemart_product_customfields` WHERE virtuemart_custom_id=' . $selected->virtuemart_custom_id);
                $valueOptions = $this->_db->loadAssocList();
                $valueOptions = array_merge(array($emptyOption), $valueOptions);
                $this->searchCustomValues .= JText::_($selected->custom_title) . ' ' . JHTML::_('select.genericlist', $valueOptions, 'customfields[' . $selected->virtuemart_custom_id . ']', 'class="inputbox"', 'virtuemart_custom_id', 'custom_title', 0);
            }
        }

        // add search for declared plugins
        JPluginHelper::importPlugin('vmcustom');
        $dispatcher = JDispatcher::getInstance();
        $plgDisplay = $dispatcher->trigger('plgVmSelectSearchableCustom', array(&$this->options, &$this->searchCustomValues, $this->custom_parent_id));

        if (!empty($this->options)) {
            $this->options = array_merge(array($emptyOption), $this->options);
            // render List of available groups
           // vmJsApi::chosenDropDowns();
            $this->searchCustomList = JText::_('COM_VIRTUEMART_SET_PRODUCT_TYPE') . ' ' . JHTML::_('select.genericlist', $this->options, 'custom_parent_id', 'class="inputbox vm-chzn-select"', 'virtuemart_custom_id', 'custom_title', $this->custom_parent_id);
        } else {
            $this->searchCustomList = '';
        }

        $this->assignRef('searchcustom', $this->searchCustomList);
        $this->assignRef('searchcustomvalues', $this->searchCustomValues);
    }
}


//no closing tag