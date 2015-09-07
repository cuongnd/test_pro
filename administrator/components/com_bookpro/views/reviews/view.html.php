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
AImporter::helper('route','bookpro', 'request','image');
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_review_list_');
}

class BookProViewReviews extends BookproJViewLegacy
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
        
        $document->setTitle(JText::_('List of Destination'));
        
        $model = new BookProModelReviews();
        
        $this->lists = array();
        
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['title']=ARequest::getUserStateFromRequest('title', '', 'string');
        //$this->lists['customer_id'] = ARequest::getUserStateFromRequest('customer_id', null, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'lft', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'ASC', 'word');
      
        $model->init($this->lists);
        $this->pagination = &$model->getPagination();
        $this->items = &$model->getData();
        $this->selectable = JRequest::getCmd('task') == 'element';
        $this->turnOnOrdering = ($this->lists['order'] == 'ordering');
       
        parent::display($tpl);
    }
  
}
?>