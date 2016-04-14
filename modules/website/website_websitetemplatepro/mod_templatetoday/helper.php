<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_login
 *
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @since       1.5
 */
class mod_templatetoday_helper
{

    public static function get_list_product_today()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('products_en_gb.*')
            ->from('#__webtempro_products AS products')
            ->where('products.product_special=1')
            ->innerJoin('#__webtempro_products_en_gb AS products_en_gb USING(id)')
            ->where('products_en_gb.image_url LIKE'.$query->q("%templatemonster%"))
        ;
        $db->setQuery($query,0,100);
        $list_product_today=$db->loadObjectList();
        return $list_product_today;
    }
}
