<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: config.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');

class BookProControllerConfig extends AController
{
    /**
     * Main model
     * 
     * @var BookingModelConfig
     */
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        if (! class_exists('BookProModelConfig'))
            AImporter::model('config');
        $this->_model = new BookProModelConfig();
        $this->_controllerName = CONTROLLER_CONFIG;
    }

    /**
     * Save component configuration.
     * 
     * @param boolean $apply true/false ... (save and stay on edit page)/(save and go to controll panel)
     * @return void
     */
    function save($apply = false)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $this->_model->store(JRequest::get('post')) ? $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message') : $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        $apply ? ARequest::redirectView(VIEW_CONFIG) : ARequest::redirectMain();
    }

    /**
     * Cancel edit operation.
     * 
     * @return void 
     */
    function cancel()
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $mainframe->enqueueMessage(JText::_('Configuration cancelled'), 'message');
        ARequest::redirectMain();
    }
}

?>