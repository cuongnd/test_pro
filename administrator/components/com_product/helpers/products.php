<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Categories helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class productsHelper
{
	/**
	 * Configure the Submenu links.
	 *
	 * @param   string  $extension  The extension being used for the categories.
	 *
	 * @return  void
	 *
	 * @since
     * */

    public function getListproductByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('c.*')
            ->from('#__product AS c')
            ->leftJoin('#__categories AS cat ON cat.id=c.catid')
            ->where('cat.website_id='.(int)$website_id)
            ->where('cat.extension!='.$db->quote('system'))
            ->group('c.id')
        ;
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function createTableProduct()
    {
        $maxRecord=100000;
        $db=JFactory::getDbo();
        $website=JFactory::getWebsite();
        $query="SELECT * FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` LIKE 'test_pro' AND `TABLE_NAME` LIKE '%ueb3c_product%' AND `TABLE_ROWS` <= ".$maxRecord." ORDER BY `TABLE_ROWS` DESC ";
        $db->setQuery($query);
        $listTable=$db->loadObjectList();
        if(count($listTable))
            return reset($listTable)->TABLE_NAME;
        $query="CREATE TABLE IF NOT EXISTS #__product_".$website->id." LIKE #__product";
        $db->setQuery($query);
        $db->execute();
    }

}
