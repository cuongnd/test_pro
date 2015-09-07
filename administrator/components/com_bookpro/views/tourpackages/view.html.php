<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 45 2012-07-12 10:42:37Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request');
AImporter::model('tours','packagetypes');
//import needed assets
AHtml::importIcons();

if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_tourpackages_list_');
}

class BookProViewTourPackages extends BookproJViewLegacy
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
    var $turnOnOrdering;
    
    
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
                
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $document->setTitle(JText::_('List of tour packages'));
        $model = new BookProModelTourPackages();
        $this->lists = array();
        $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
        $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'ordering', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
		$this->lists['tour_id'] = ARequest::getUserStateFromRequest('tour_id', 0, 'int');
		$this->lists['packagetype_id'] = ARequest::getUserStateFromRequest('packagetype_id', 0, 'int');
        $model->init($this->lists);
        
        $this->turnOnOrdering = $this->lists['order'] == 'ordering';
        $this->pagination = &$model->getPagination();
        $this->items = &$model->getData();
        $this->params = &JComponentHelper::getParams(OPTION);
        $this->selectable = JRequest::getCmd('task') == 'element';
		$this->assignRef('tours',$this->getTourBox($this->lists['tour_id']));
		$this->assignRef('packagetypes',$this->getPackageTypeBox($this->lists['packagetype_id']));
        parent::display($tpl);
        
       
    }
   function getTourBox($select){
    	$model = new BookProModelTours();
        $lists = $model->getData();
        if(!$select){
        	if($lists){
        		$select = $lists[0]->id; 
        	}
        }
        return JHtmlSelect::genericlist($lists, 'tour_id','onchange="this.form.submit()"','id','title',$select);
        //return AHtml::getFilterSelect(tour_id, 'Select From Tour', $lists, $select,true, '', 'id', 'title');
    }

	function getPackageTypeBox($select){
    	$model = new BookProModelPackageTypes();
        $lists = $model->getData();
        return AHtml::getFilterSelect(packagetype_id, 'Select From Package', $lists, $select,true, '', 'id', 'title');
    }
   
   
}

?>