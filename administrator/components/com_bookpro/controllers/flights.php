<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class BookproControllerFlights extends JControllerAdmin {


    public function getModel($name = 'flight', $prefix = 'BookproModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
    public function featured()
    {
    	// Check for request forgeries
    	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
    
    	$user   = JFactory::getUser();
    	$ids    = $this->input->get('cid', array(), 'array');
    
    	$values = array('featured' => 1, 'unfeatured' => 0);
    	$task   = $this->getTask();
    	$value  = JArrayHelper::getValue($values, $task, 0, 'int');
    
    	if (empty($ids))
    	{
    		JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
    	}
    	else
    	{
    		// Get the model.
    		$model = $this->getModel();
    		// Publish the items.
    		if (!$model->featured($ids,$value))
    		{
    			JError::raiseWarning(500, $model->getError());
    		}
    	}
    
    	$this->setRedirect('index.php?option=com_bookpro&view=flights');
    }

}