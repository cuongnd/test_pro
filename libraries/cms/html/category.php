<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Utility class for categories
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       1.5
 */
abstract class JHtmlCategory
{
	/**
	 * Cached array of the category items.
	 *
	 * @var    array
	 * @since  1.5
	 */
	protected static $items = array();

	/**
	 * Returns an array of categories for the given extension.
	 *
	 * @param   string  $extension  The extension option e.g. com_something.
	 * @param   array   $config     An array of configuration options. By default, only
	 *                              published and unpublished categories are returned.
	 *
	 * @return  array
	 *
	 * @since   1.5
	 */
	public static function options($extension, $config = array('filter.published' => array(0, 1)))
	{
        $hash = md5($extension . '.' . serialize($config));
        $website_id=$config['website_id'];
        if (!isset(static::$items[$hash]))
        {
            $config = (array) $config;
            $db = JFactory::getDbo();
            $query = $db->getQuery(true)
                ->select('a.id, a.title, a.level, a.parent_id')
                ->from('#__categories AS a')
                ->where('a.alias!='.$db->quote('root'))
                ->where('website_id='.(int)$website_id)
            ;

            // Filter on extension.
            $query->where('(extension = ' . $db->quote($extension).' OR  extension='.$db->quote('system').')');

            // Filter on the published state
            if (isset($config['filter.published']))
            {
                if (is_numeric($config['filter.published']))
                {
                    $query->where('a.published = ' . (int) $config['filter.published']);
                }
                elseif (is_array($config['filter.published']))
                {
                    JArrayHelper::toInteger($config['filter.published']);
                    $query->where('a.published IN (' . implode(',', $config['filter.published']) . ')');
                }
            }

            $query->order('a.lft');

            $db->setQuery($query);

            $items = $db->loadObjectList();

            // Assemble the list options.
            static::$items[$hash] = array();
            foreach ($items as &$item)
            {
                $repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
                $item->title = str_repeat('- ', $repeat) . $item->title;
                static::$items[$hash][] = JHtml::_('select.option', $item->id, $item->title);
            }
            // Special "Add to root" option:

        }

        return static::$items[$hash];
	}

	/**
	 * Returns an array of categories for the given extension.
	 *
	 * @param   string  $extension  The extension option.
	 * @param   array   $config     An array of configuration options. By default, only published and unpublished categories are returned.
	 *
	 * @return  array   Categories for the extension
	 *
	 * @since   1.6
	 */
	public static function categories($extension, $config = array('filter.published' => array(0, 1)))
	{

		$hash = md5($extension . '.' . serialize($config));
        $website_id=$config['website_id'];
		if (!isset(static::$items[$hash]))
		{
			$config = (array) $config;
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('a.id, a.title, a.level, a.parent_id')
				->from('#__categories AS a')
                ->where('website_id='.(int)$website_id)
            ;

			// Filter on extension.
			$query->where('(extension = ' . $db->quote($extension).' OR  extension='.$db->quote('system').')');

			// Filter on the published state
			if (isset($config['filter.published']))
			{
				if (is_numeric($config['filter.published']))
				{
					$query->where('a.published = ' . (int) $config['filter.published']);
				}
				elseif (is_array($config['filter.published']))
				{
					JArrayHelper::toInteger($config['filter.published']);
					$query->where('a.published IN (' . implode(',', $config['filter.published']) . ')');
				}
			}

			$query->order('a.lft');

			$db->setQuery($query);

			$items = $db->loadObjectList();

			// Assemble the list options.
			static::$items[$hash] = array();

			foreach ($items as &$item)
			{
				$repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
				$item->title = str_repeat('- ', $repeat) . $item->title;
				static::$items[$hash][] = JHtml::_('select.option', $item->id, $item->title);
			}
			// Special "Add to root" option:

		}

		return static::$items[$hash];
	}
}
