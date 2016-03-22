<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Users component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class LevelsHelper
{
	/**
	 * @var    JObject  A cache for the available actions.
	 * @since  1.6
	 */
	protected static $actions;

    public function getListUserLevelByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__viewlevels')
            ->where(
                array(
                    'website_id='.(int)$website_id
                )
            );

        $db->setQuery($query);
        $listGroup=$db->loadObjectList();
        return $listGroup;
    }
}
