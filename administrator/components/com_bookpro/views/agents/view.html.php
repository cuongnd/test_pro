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
AImporter::helper('route', 'bookpro', 'request');
//import needed assets
//AImporter::js('view-agents');
//import custom icons
AHtml::importIcons();


class BookProViewAgents extends BookproJViewLegacy
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
                
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $document->setTitle(JText::_('List of customers'));
        
        $model = new BookProModelAgents();
        
        $this->lists = array();
        
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        
        $this->lists['firstname'] = ARequest::getUserStateFromRequest('firstname', '', 'string');
        //$this->lists['city'] = ARequest::getUserStateFromRequest('city', '', 'string');
        //$this->lists['country'] = ARequest::getUserStateFromRequest('country', '', 'string');
        //$this->lists['company'] = ARequest::getUserStateFromRequest('company', '', 'string');
        
        //$this->lists['state'][CUSTOMER_STATE_ACTIVE] = ARequest::getUserStateFromRequest('filter_state_' . CUSTOMER_STATE_ACTIVE, CUSTOMER_STATE_ACTIVE, 'int', true);
        //$this->lists['state'][CUSTOMER_STATE_BLOCK] = ARequest::getUserStateFromRequest('filter_state_' . CUSTOMER_STATE_BLOCK, CUSTOMER_STATE_BLOCK, 'int', true);
        //$this->lists['state'][CUSTOMER_STATE_DELETED] = ARequest::getUserStateFromRequest('filter_state_' . CUSTOMER_STATE_DELETED, CUSTOMER_STATE_DELETED, 'int', true);
        
        $model->init($this->lists);
        
        $this->pagination = &$model->getPagination();
        
        $this->items = &$model->getData();
        
        $this->params = &JComponentHelper::getParams(OPTION);
        
        $this->selectable = JRequest::getCmd('task') == 'element';
        
        parent::display($tpl);
    }
}
?>