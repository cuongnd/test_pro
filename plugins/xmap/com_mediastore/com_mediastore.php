<?php
/**
 * @copyright	Copyright (c) 2012 Skyline Software (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die();

/**
 * Xmap - MediaStore Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	Skyline.MediaStore
 */
class Xmap_Com_MediaStore {

	static private $_initialized = false;

	/*
	 * This function is called before a menu item is printed. We use it to set the
	 * proper uniqueid for the item and indicate whether the node is expandible or not
	 */

	static function prepareMenuItem($node, &$params) {
		$link_query = parse_url($node->link);
		$db			= JFactory::getDbo();

		parse_str(html_entity_decode($link_query['query']), $link_vars);
		$view	= JArrayHelper::getValue($link_vars, 'view', '');

		if ($view == 'product') {
			$id = intval(JArrayHelper::getValue($link_vars, 'id', 0));
			if ($id) {
				$node->uid = 'com_mediastorei' . $id;
				$node->expandible = false;

				$query = $db->getQuery(true);

				$query->select('created, modified')
					->from('#__mediastore_products')
					->where('id = ' . $id)
				;
				$db->setQuery($query);

				if ($row = $db->loadObject()) {
					$node->modified	= $row->modified;
				}
			}
		} elseif ($view == 'category') {
			$catid = intval(JArrayHelper::getValue($link_vars, 'id', 0));
			$node->uid = 'com_mediastorec' . $catid;
			$node->expandible = true;
		}
	}

	static function getTree($xmap, $parent, &$params) {
		self::initialize($params);

		$link_query = parse_url($parent->link);
		parse_str(html_entity_decode($link_query['query']), $link_vars);
		$view = JArrayHelper::getValue($link_vars, 'view', 0);

		$menu = & JSite::getMenu();
		$menuparams = $menu->getParams($parent->id);

		if ($view == 'category') {
			$catid = intval(JArrayHelper::getValue($link_vars, 'id', 0));
		} elseif ($view == 'featured') {
			$catid = 0;
		} else { // Only expand category menu items
			return;
		}

		$include_products = JArrayHelper::getValue($params, 'include_products', 1, '');
		$include_products = ($include_products == 1
				|| ($include_products == 2 && $xmap->view == 'xml')
				|| ($include_products == 3 && $xmap->view == 'html')
				|| $xmap->view == 'navigator');
		$params['include_products'] = $include_products;

		$priority = JArrayHelper::getValue($params, 'cat_priority', $parent->priority, '');
		$changefreq = JArrayHelper::getValue($params, 'cat_changefreq', $parent->changefreq, '');
		if ($priority == '-1')
			$priority = $parent->priority;
		if ($changefreq == '-1')
			$changefreq = $parent->changefreq;

		$params['cat_priority'] = $priority;
		$params['cat_changefreq'] = $changefreq;

		$priority = JArrayHelper::getValue($params, 'product_priority', $parent->priority, '');
		$changefreq = JArrayHelper::getValue($params, 'product_changefreq', $parent->changefreq, '');
		if ($priority == '-1')
			$priority = $parent->priority;

		if ($changefreq == '-1')
			$changefreq = $parent->changefreq;

		$params['product_priority'] = $priority;
		$params['product_changefreq'] = $changefreq;


		$options = array();
		$options['countItems'] = false;
		$options['catid'] = rand();
		$categories = JCategories::getInstance('MediaStore', $options);
		$category = $categories->get($catid ? $catid : 'root', true);


		Xmap_Com_MediaStore::getCategoryTree($xmap, $parent, $params, $category);
	}

	static function getCategoryTree($xmap, $parent, &$params, $category) {
		$db = JFactory::getDBO();

		$children = $category->getChildren();
		$xmap->changeLevel(1);
		foreach ($children as $cat) {
			$node = new stdclass;
			$node->id = $parent->id;
			$node->uid = $parent->uid . 'c' . $cat->id;
			$node->name = $cat->title;
			$node->link = MediaStoreHelperRoute::getCategoryRoute($cat);
			$node->priority = $params['cat_priority'];
			$node->changefreq = $params['cat_changefreq'];
			$node->expandible = true;
			if ($xmap->printNode($node) !== FALSE) {
				Xmap_Com_MediaStore::getCategoryTree($xmap, $parent, $params, $cat);
			}
		}
		$xmap->changeLevel(-1);

		if ($params['include_products']) { //view=category&catid=...
			$productsModel = new MediaStoreModelCategory();
			$productsModel->getState(); // To force the populate state
			$productsModel->setState('list.limit', JArrayHelper::getValue($params, 'max_products', NULL));
			$productsModel->setState('list.start', 0);
			$productsModel->setState('list.ordering', 'ordering');
			$productsModel->setState('list.direction', 'ASC');
			$productsModel->setState('category.id', $category->id);
			$products = $productsModel->getItems();
			$xmap->changeLevel(1);
			foreach ($products as $product) {
				$node = new stdclass;
				$node->id = $parent->id;
				$node->uid = $parent->uid . 'i' . $product->id;
				$node->name = $product->title;
				$node->link = MediaStoreHelperRoute::getProductRoute($product->id, $category->id);
				$node->priority = $params['product_priority'];
				$node->changefreq = $params['product_changefreq'];
				$node->expandible = false;
				$xmap->printNode($node);
			}
			$xmap->changeLevel(-1);
		}
	}

	static public function initialize(&$params) {
		if (self::$_initialized) {
			return;
		}

		self::$_initialized = true;
		JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_mediastore/models');
		require_once(JPATH_SITE . '/components/com_mediastore/models/category.php');
		require_once(JPATH_SITE . '/administrator/components/com_mediastore/helpers/factory.php');
		require_once(JPATH_SITE . '/components/com_mediastore/helpers/route.php');
		require_once(JPATH_SITE . '/components/com_mediastore/helpers/query.php');
		require_once(JPATH_SITE . '/components/com_mediastore/helpers/permission.php');
	}
}