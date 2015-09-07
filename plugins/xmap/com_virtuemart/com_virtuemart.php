<?php
/**
 * @author Guillermo Vargas, http://www.jooxmap.com
 * @email guille@vargas.co.cr
 * @version $Id: com_virtuemart.php 154 2011-04-09 23:44:12Z guilleva $
 * @package Xmap
 * @license GNU/GPL
 * @description Xmap plugin for Virtuemart component
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

/** Adds support for Virtuemart categories to Xmap */
class xmap_com_virtuemart
{
	protected static $categoryModel;
	protected static $productModel;
	protected static $initialized = false;

	static $urlBase;
	/*
	 * This function is called before a menu item is printed. We use it to set the
	 * proper uniqueid for the item and indicate whether the node is expandible or not
	 */

	static function prepareMenuItem($node, &$params)
	{
		$app = JFactory::getApplication();

		$link_query = parse_url($node->link);

		parse_str(html_entity_decode($link_query['query']), $link_vars);

		$catid  = JArrayHelper::getValue($link_vars, 'virtuemart_category_id', 0);
		$prodid = JArrayHelper::getValue($link_vars, 'virtuemart_product_id', 0);

		if (!$catid)
		{
			$menu       = $app->getMenu();
			$menuParams = $menu->getParams($node->id);
			$catid      = $menuParams->get('virtuemart_category_id', 0);
		}

		if (!$prodid)
		{
			$menu       = $app->getMenu();
			$menuParams = $menu->getParams($node->id);
			$prodid     = $menuParams->get('virtuemart_product_id', 0);
		}

		if ($prodid && $catid)
		{
			$node->uid        = 'com_virtuemartc' . $catid . 'p' . $prodid;
			$node->expandible = false;
		}
		elseif($catid)
		{
			$node->uid        = 'com_virtuemartc' . $catid;
			$node->expandible = true;
		}
	}

	/** Get the content tree for this kind of content */
	static function getTree($xmap, $parent, &$params)
	{
		self::initialize();

		$app  = JFactory::getApplication();
		$menu = $app->getMenu();

		$link_query = parse_url($parent->link);

		parse_str(html_entity_decode($link_query['query']), $link_vars);

		$catid            = intval(JArrayHelper::getValue($link_vars, 'virtuemart_category_id', 0));

		$params['Itemid'] = intval(JArrayHelper::getValue($link_vars, 'Itemid', $parent->id));

		$view = JArrayHelper::getValue($link_vars, 'view', '');

		// we currently support only categories
		if (!in_array($view, array('categories','category')))
		{
			return true;
		}

		$include_products = JArrayHelper::getValue($params, 'include_products', 1);
		$include_products = ( $include_products == 1
			|| ( $include_products == 2 && $xmap->view == 'xml')
			|| ( $include_products == 3 && $xmap->view == 'html'));

		$params['include_products']          = $include_products;
		$params['include_product_images']    = (JArrayHelper::getValue($params, 'include_product_images', 1) && $xmap->view == 'xml');
		$params['product_image_license_url'] = trim(JArrayHelper::getValue($params, 'product_image_license_url', ''));

		$priority   = JArrayHelper::getValue($params, 'cat_priority', $parent->priority);
		$changefreq = JArrayHelper::getValue($params, 'cat_changefreq', $parent->changefreq);

		if ($priority == '-1')
		{
			$priority = $parent->priority;
		}

		if ($changefreq == '-1')
		{
			$changefreq = $parent->changefreq;
		}

		$params['cat_priority']   = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority   = JArrayHelper::getValue($params, 'prod_priority', $parent->priority);
		$changefreq = JArrayHelper::getValue($params, 'prod_changefreq', $parent->changefreq);

		if ($priority == '-1')
		{
			$priority = $parent->priority;
		}

		if ($changefreq == '-1')
		{
			$changefreq = $parent->changefreq;
		}

		$params['prod_priority']   = $priority;
		$params['prod_changefreq'] = $changefreq;
        xmap_com_virtuemart::getCategoryTree($xmap, $parent, $params, $catid,array(),0,20);

		return true;
	}
	/** Virtuemart support */
	static function getCategoryTree($xmap, $parent, &$params, $catid=0,$categoryTree=array(),$level=0,$maxLevel=999)
	{


        if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');


        $vm_product_type='virtuemart-product';
        $query=$db->getQuery(true);
        $query->from('#__virtuemart_products_'.VMLANG.' AS pl');
        $query->select('pl.virtuemart_product_id AS id,pl.product_name AS name,pl.slug AS slug');
        $query->leftJoin('#__virtuemart_product_categories as pc USING(virtuemart_product_id)');
        $query->select('pc.virtuemart_category_id');
        $query->group('pl.virtuemart_product_id');
        //dang start
        $limit=10000;
        $array_setLimit=array(
            'start'=>0

        );
        $db->setQuery($query,0,10000);
        $products=$db->loadObjectList();
        if(count($products)){
            foreach ($products as $row)
            {

                $query=$db->getQuery(true);
                $query->select('count(*) AS total');
                $query->from('#__xmap_links');
                $query->where('object_id='.$row->virtuemart_product_id);
                $query->where('type='.$db->q($vm_product_type));
                $db->setQuery($query);

                $total=$db->loadResult();
                if(!$total)
                {
                    $link       = 'index.php?option=com_virtuemart&amp;view=productdetails&amp;virtuemart_product_id=' . $row->virtuemart_product_id . '&amp;virtuemart_category_id=' . $row-> 	virtuemart_category_id .'&amp;slug='.$row->slug;
                    $routerSite=JRouterSite::getInstance(0);
                    $uri=$routerSite->build($link);
                    print_r()
                    $link = JRouterSite::_($link);
                    $link=str_replace('test_pro/','',$link);
                    $link='http://websitetemplatepro.com'.$link;
                    $query=$db->getQuery(true);
                    $query->insert('#__xmap_links');
                    $query->columns('type,object_id,link');
                    $query->values($db->q($vm_product_type).','.$row->virtuemart_product_id.','.$db->q($link));
                    $db->setQuery($query);
                    $db->execute();
                }

            }
        }
        echo "da xet xong";
        die;

	}
    public static  function getProductsByCatId($catId)
    {
        if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__virtuemart_products_'.VMLANG.' AS pl');
        $query->select('pl.virtuemart_product_id AS id,pl.product_name AS name,pl.slug AS slug');
        $query->leftJoin('#__virtuemart_product_categories AS pc USING(virtuemart_product_id)');
        $query->select('pc.virtuemart_category_id AS cat_id');
        $query->group('pl.virtuemart_product_id');
        $query->where('pc.virtuemart_category_id='.$catId);
        $db->setQuery($query);
        //$logFile='com_xmap_flow1.txt';
        //JLog::addLogger(array('text_file' =>$logFile,'text_file_path'=>'logs'),JLog::ALL);
        //JLog::add($query->dump(),JLog::INFO );
        $categoryProductTree=$db->loadObjectList();
        return $categoryProductTree;
    }
	static protected function initialize()
	{
		if (self::$initialized) return;

		$app = JFactory::getApplication ();

		if (!class_exists( 'VmConfig' ))
		{
			require(JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php');
			VmConfig::loadConfig();
		}

		JTable::addIncludePath(JPATH_VM_ADMINISTRATOR . '/tables');

		VmConfig::set ('llimit_init_FE', 9000);

		$app->setUserState('com_virtuemart.htmlc-1.limit',9000);
		$app->setUserState('com_virtuemart.htmlc0.limit',9000);
		$app->setUserState('com_virtuemart.xmlc0.limit' ,9000);

		if (!class_exists('VirtueMartModelShortedCategory')) require(JPATH_VM_ADMINISTRATOR . '/models/shortedcategory.php');
		self::$categoryModel = new VirtueMartModelShortedCategory();

		if (!class_exists('VirtueMartModelShortedProduct')) require(JPATH_VM_ADMINISTRATOR  . '/models/shortedproduct.php');
		self::$productModel = new VirtueMartModelShortedProduct();
	}
}
