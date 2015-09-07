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
AImporter::model('cartransport', 'cardestination', 'cardestinations');
//import needed assets
//import custom icons
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_car_transports_list_');
}

class BookProViewCarTransports extends BookproJViewLegacy
{
    /**
     * Array containing browse table filters properties.
     * 
     * @var array
     */
    var $lists;
    var $turnOnOrdering;
        
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
        
        $document->setTitle(JText::_('Transport list'));
        
        $model = new BookProModelCarTransports();
        
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
        $this->lists['from'] = ARequest::getUserStateFromRequest('from', null, 'int');
        $this->lists['to'] = ARequest::getUserStateFromRequest('to', 0, 'int');
		
        $model->init($this->lists);
        
        $this->pagination = &$model->getPagination();
        $this->items = &$model->getData(); 
        if(count($this->items)>0)
        {
            for($i=0; $i<count($this->items) ;$i++ )
            {
               $item = &$this->items[$i];
               $modelCatdestinationFrom = new BookproModelCarDestination(); 
               $modelCatdestinationFrom->setId($item->from);
               $from = $modelCatdestinationFrom->getObject();
               if($from){
                    $item->from = $from->title;    
               }
               $modelCatdestinationTo = new BookproModelCarDestination(); 
               $modelCatdestinationTo->setId($item->to);
               $to = $modelCatdestinationTo->getObject();
               if($to){
                    $item->to = $to->title;    
               }     
            }
        }
        //var_dump($this->items); die;
        
        $this->params = &JComponentHelper::getParams(OPTION);
        
        $this->selectable = JRequest::getCmd('task') == 'element';
        $this->turnOnOrdering = ($this->lists['order'] == 'ordering');
        
        $frombox=$this->getDestinationSelectBox($this->lists['from'],'from');
        $tobox=$this->getDestinationSelectBox($this->lists['to'],'to');
        $this->assignRef("dfrom",$frombox);
        $this->assignRef("dto",$tobox);
        parent::display($tpl);
       
    }
  function getDestinationSelectBox($select, $field = 'from')
    {
        $model = new BookproModelCarDestinations();
        $lists = array();
        $model->init($lists);
        $fullList = $model->getData(); //var_dump($fullList); die;
        return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_DESTINATION'), $fullList, $select, false, '', 'id', 'title');
    }
  }

?>