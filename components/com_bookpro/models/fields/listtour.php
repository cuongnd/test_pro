<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
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
 * @subpackage  com_users
 * @since       1.6
 */
class JFormFieldListTour extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var        string
     * @since   1.6
     */
    protected $type = 'ListTour';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     * @since   1.6
     */
    protected function getOptions()
    {
        $options = array();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('tour.*');
        $query->from('#__bookpro_tour AS tour');
        $query->where(array('tour.state=1'));
        $db->setQuery($query);
        $tours = $db->loadObjectList();
          
        $options[] = JHtmlSelect::option('',JText::_('COM_BOOKPRO_SELECT_TOUR'));
        if(!empty($tours)){
            foreach($tours as $tour){
                $options[] = JHtmlSelect::option($tour->id,$tour->title);
            }
        }
               
        return $options;
    }
}
