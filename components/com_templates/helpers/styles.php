<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Templates component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 * @since       1.6
 */
class StylesHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 */
    public  function  getStylesByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('template_styles.*')
            ->from('#__template_styles AS template_styles')
            ->leftJoin('#__extensions AS extensions ON extensions.id=template_styles.extension_id')
            ->where('extensions.website_id='.(int)$website_id);

        $db->setQuery($query);
        return $db->loadObjectList();
    }

}
