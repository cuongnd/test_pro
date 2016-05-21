<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * websitetemplatepro component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.6
 */
class listwebsitetemplateHelper
{
	public static $extension = 'com_websitetemplatepro';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string    The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		// No submenu for this component.
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions()
	{
		// Log usage of deprecated function
		JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');
        require_once JPATH_ROOT.DS.'components/website/website_websitetemplatepro/com_websitetemplatepro/helpers/websitetemplatepro.php';
		// Get list of actions
		$result = JHelperwebsitetemplatepro::getActions('com_websitetemplatepro');

		return $result;
	}

    public static function get_list_template_website_by_category_id_include_children($category_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('id,parent_id,ordering')
            ->from('#__webtempro_categories AS categories')
            ;
        $db->setQuery($query);
        $list_category=$db->loadObjectList();
        $children_category = array();
        foreach ($list_category as $category) {
            $pt = $category->parent_id;
            $pt = ($pt == '' || $pt == $category->id) ? 'list_root' : $pt;
            $list = @$children_category[$pt] ? $children_category[$pt] : array();
            array_push($list, $category);
            $children_category[$pt] = $list;
        }
        $list_root_category = $children_category['list_root'];
        $list_children_category_include_parent_category=array();
        $list_children_category_include_parent_category[]=$category_id;
        $get_list_children_category_include_parent_category=function($function_callback,$category_id,$children_category,&$list_category=array()){
            if(count($children_category[$category_id])){
                foreach($children_category[$category_id] as $category){
                    $list_category[]=$category->id;
                    $category_id1=$category->id;
                    $function_callback($function_callback,$category_id1,$children_category,$list_category);
                }
            }
        };
        $get_list_children_category_include_parent_category($get_list_children_category_include_parent_category,$category_id,$children_category,$list_children_category_include_parent_category);
        $query=$db->getQuery(true);
        $query->select('product.id,products_en_gb.image_url,products_en_gb.product_name,products_en_gb.price_monter,products_en_gb.cmstype')
            ->from('#__webtempro_products AS product')
            ->innerJoin('#__webtempro_products_en_gb AS products_en_gb ON products_en_gb.id=product.id')
            ->where('product.category_id IN ('.implode(',',$list_children_category_include_parent_category).')')
            ->leftJoin('#__website AS website ON website.id=product.website_id')
            ->select('product.website_id')
            ->leftJoin('#__domain_website AS domain_website ON domain_website.website_id=website.id AND domain_website.domain NOT LIKE '.$query->q('%admin%'))
            ->select('domain_website.domain AS link_demo')
            ;
        $db->setQuery($query);
        $list_template_website=$db->loadObjectList();
        return $list_template_website;


    }


}
