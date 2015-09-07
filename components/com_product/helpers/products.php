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

    public function getListArticleByWebsiteId($website_id=0)
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

}
