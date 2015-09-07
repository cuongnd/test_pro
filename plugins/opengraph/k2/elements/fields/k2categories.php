<?php
/**
 * @version        $Id: categories.php 1618 2012-09-21 11:23:08Z lefteris.kavadas $
 * @package        K2
 * @author        JoomlaWorks http://www.joomlaworks.net
 * @copyright    Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldK2Categories extends JFormFieldList
{

    protected function getOptions()
    {
        // Really need to add a tree view to this, but not today..
        $db = JFactory::getDBO();

        $query = "SELECT `name` AS text, `id` AS 'value'  FROM #__k2_categories  WHERE trash = 0 ORDER BY parent, ordering";
        $db->setQuery($query);
        $mitems = $db->loadObjectList();
        return $mitems;
    }
}

