<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_components
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * component controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_components
 * @since       1.6
 */
class componentsControllercomponent extends JControllerForm
{
    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   3.2
     */
    protected function allowEdit($data = array(), $key = 'id')
    {
        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user = JFactory::getUser();
        $userId = $user->get('id');
        // Check general edit permission first.
        if ($user->authorise('core.edit', 'com_components.component.' . $recordId))
        {
            return true;
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }

}
