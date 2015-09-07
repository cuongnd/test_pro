<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 47 2012-07-13 09:43:14Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request', 'controller');
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_customer_list_');
	}
}

class BookProViewCustomers extends BookproJViewLegacy
{
    /**
     * Array containing browse table filters properties.
     *
     * @var array
     */
    var $lists;

    /**
     * Array containig browse table subjects items to display.
     *
     * @var array
     */
    var $items;

    /**
     * Standard Joomla! browse tables pagination object.
     *
     * @var JPagination
     */
    var $pagination;

    /**
     * Standard Joomla! user object.
     *
     * @var JUser
     */
    var $user;

    /**
     * Sign if table is used to popup selecting customers.
     *
     * @var boolean
     */
    var $selectable;

    /**
     * Standard Joomla! object to working with component parameters.
     *
     * @var $params JParameter
     */
    var $params;

    /**
     * Prepare to display page.
     *
     * @param string $tpl name of used template
     */
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */



        $model = new BookProModelCustomers();

        $this->lists = array();

        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['group_id'] = ARequest::getUserStateFromRequest('group_id', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'created', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['firstname'] = ARequest::getUserStateFromRequest('firstname', null, 'string');
        $this->lists['group_id'] = ARequest::getUserStateFromRequest('group_id', null, 'int');
        $this->lists['country_id'] = ARequest::getUserStateFromRequest('country_id', null, 'int');

        $this->assignRef('groups', BookProHelper::getSelectBoxGroups($this->lists['group_id']));

        $model->init($this->lists);

        $this->pagination = $model->getPagination();

        $this->items = $model->getData();
       	$this->countries=BookProHelper::getCountryList('country_id', $this->lists['country_id']);

        $this->selectable = JRequest::getCmd('task') == 'element';
        $this->displayTitleBar($this->lists['group_id']);

        parent::display($tpl);
    }
    function displayTitleBar($group_id){
    	$document = JFactory::getDocument();
    	/* @var $document JDocument */
    	$config=AFactory::getConfig();
    	if($group_id==$config->customersUsergroup){
    		JToolBarHelper::title(JText::_('COM_BOOKPRO_CUSTOMERS'), 'user.png');
    		$document->setTitle(JText::_('COM_BOOKPRO_CUSTOMERS'));
    	}
    	if($group_id==$config->agentUsergroup){
    		JToolBarHelper::title(JText::_('COM_BOOKPRO_AGENTS'), 'user.png');
    		$document->setTitle(JText::_('COM_BOOKPRO_AGENTS'));
    	}
    	if($group_id==$config->supplierUsergroup){
    		JToolBarHelper::title(JText::_('COM_BOOKPRO_SUPPLIERS'), 'user.png');
    		$document->setTitle(JText::_('COM_BOOKPRO_SUPPLIERS'));
    	}


    }
}
?>