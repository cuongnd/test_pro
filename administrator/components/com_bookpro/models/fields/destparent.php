<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class JFormFieldDestParent extends JFormFieldList
{
    /**
     * A flexible category list that respects access controls
     *
     * @var        string
     * @since   1.6
     */
    public $type = 'destparent';

    /**
     * Method to get a list of categories that respects access controls and can be used for
     * either category assignment or parent category assignment in edit screens.
     * Use the parent element to indicate that the field will be used for assigning parent categories.
     *
     * @return  array  The field option objects.
     *
     * @since   1.6
     */
    protected function getOptions()
    {
        $options = array();
        $published = $this->element['published'] ? $this->element['published'] : array(0, 1);
        $name = (string) $this->element['name'];

        // Let's get the id for the current item, either category or content item.
        $jinput = JFactory::getApplication()->input;
        // Load the category options for a given extension.

        // For categories the old category is the category id or 0 for new category.
        if ($this->element['parent'] || $jinput->get('option') == 'com_bookpro')
        {
            $oldCat = $jinput->get('id', 0);
            $oldParent = $this->form->getValue($name, 0);
        }
        else
            // For items the old category is the category they are in when opened or 0 if new.
        {
            $oldCat = $this->form->getValue($name, 0);
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('a.id AS value, a.title AS text, a.level')
            ->from('#__bookpro_dest AS a')
            ->join('LEFT', $db->quoteName('#__bookpro_dest') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

       
        
        // If parent isn't explicitly stated but we are in com_categories assume we want parents
        if ($oldCat != 0 && ($this->element['parent'] == true || $jinput->get('option') == 'com_bookpro'))
        {
            // Prevent parenting to children of this item.
            // To rearrange parents and children move the children up, not the parents down.
            $query->join('LEFT', $db->quoteName('#__bookpro_dest') . ' AS p ON p.id = ' . (int) $oldCat)
                ->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');

            $rowQuery = $db->getQuery(true);
            $rowQuery->select('a.id AS value, a.title AS text, a.level, a.parent_id')
                ->from('#__bookpro_dest AS a')
                ->where('a.id = ' . (int) $oldCat);
            $db->setQuery($rowQuery);
            $row = $db->loadObject();
            echo $db->replacePrefix($query);
        }

        // Filter language
        if (!empty($this->element['language']))
        {

            $query->where('a.language = ' . $db->quote($this->element['language']));
        }

        

        $query->group('a.id, a.title, a.level, a.lft, a.rgt, a.parent_id')
            ->order('a.lft ASC');

        // Get the options.
        $db->setQuery($query);
        

        try
        {
            $options = $db->loadObjectList();
        }
        catch (RuntimeException $e)
        {
            JError::raiseWarning(500, $e->getMessage);
        }

        // Pad the option text with spaces using depth level as a multiplier.
        for ($i = 0, $n = count($options); $i < $n; $i++)
        {
            // Translate ROOT
            if ($this->element['parent'] == true || $jinput->get('option') == 'com_bookpro')
            {
                if ($options[$i]->level == 0)
                {
                    $options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
                }
            }
            if ($options[$i]->published == 1)
            {
                $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
            }
            else
            {
                $options[$i]->text = str_repeat('- ', $options[$i]->level) . '[' . $options[$i]->text . ']';
            }
        }

        

        
        if (($this->element['parent'] == true || $jinput->get('option') == 'com_bookpro')
            && (isset($row) && !isset($options[0]))
            && isset($this->element['show_root'])
        )
        {
            if ($row->parent_id == '1')
            {
                $parent = new stdClass;
                $parent->text = JText::_('JGLOBAL_ROOT_PARENT');
                array_unshift($options, $parent);
            }
            array_unshift($options, JHtml::_('select.option', '0', JText::_('JGLOBAL_ROOT')));
        }

        // Merge any additional options in the XML definition.
        $options = array_merge(parent::getOptions(), $options);

        return $options;
    }
}
