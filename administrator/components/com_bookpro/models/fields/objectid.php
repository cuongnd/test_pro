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
class JFormFieldObjectId extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var        string
     * @since   1.6
     */
    protected $type = 'objectid';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     * @since   1.6
     */
    protected function getOptions()
    {
        $input=JFactory::getApplication()->input;
        $type=$this->form->getValue('type');
        if(!$type)
            $type=$input->get('type','tour','string');
        $options = array();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('object.*');
        $query->from('#__bookpro_'.$type.' AS object');
        $query->where(array('object.state=1'));
        $db->setQuery($query);
        $objects = $db->loadObjectList();
          
        $options[] = JHtmlSelect::option('',JText::_('COM_BOOKPRO_SELECT_'.strtoupper($type)));
        if(!empty($objects)){
            foreach($objects as $object){
                $options[] = JHtmlSelect::option($object->id,$object->title);
            }
        }
               
        return $options;
    }
}
