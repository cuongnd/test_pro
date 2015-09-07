<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 26 2012-07-08 16:07:54Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request');
//import needed assets
AHtml::importIcons();

AImporter::model('agents');
if (! defined('SESSION_PREFIX')) {
	if (IS_ADMIN) {
		define('SESSION_PREFIX', 'bookpro_buses_list_');
	} 
}


class BookProViewBuses extends BookproJViewLegacy
{
    
    var $lists;
    var $items;
    var $pagination;
    var $selectable;
    var $params;
    
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
                
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $document->setTitle(JText::_('List of bus'));
        
        $model = new BookProModelBuses();
        
        $this->lists = array();
        
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
       
        $this->lists['agent_id'] = ARequest::getUserStateFromRequest('agent_id', 0 , 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['title'] = ARequest::getUserStateFromRequest('title', '' , 'string');    
        $model->init($this->lists);
        
        $this->pagination = &$model->getPagination();
        
        $this->items = &$model->getData();
        
        $this->params = &JComponentHelper::getParams(OPTION);
        
        $this->selectable = JRequest::getCmd('task') == 'element';
        
        parent::display($tpl);
    }
    
    function getAgenSelectBox($select, $field = 'agent_id')
    {
        AImporter::model('agents');
        $model = new BookProModelAgents();
   	$lists = array();
    	$model->init($lists);
    	$fullList = $model->getData();
            var_dump($fullList);
    	return AHtml::getFilterSelect($field, 'Agent', $fullList, $select, true, '', 'id', 'company');
    }
    
    
}

?>