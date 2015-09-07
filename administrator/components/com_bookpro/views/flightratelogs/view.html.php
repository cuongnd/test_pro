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
    AImporter::model('flights','flightratelogs');
    //import custom icons
    AHtml::importIcons();



    class BookProViewFlightRateLogs extends BookproJViewLegacy
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
        function display($tpl = null)
        {
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */

            $document = &JFactory::getDocument();
            /* @var $document JDocument */
            $document->setTitle(JText::_('List of roomrates'));
            $model = new BookProModelRoomRateLogs();
            $this->lists = array();
            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
            //$this->lists['hotel_id'] = ARequest::getUserStateFromRequest('hotel_id', '', 'int');
            $model->init($this->lists);

            $this->pagination = &$model->getPagination();
            $this->items = &$model->getData();
            if(count($this->items)>0)
            {
                for($i=0; $i<count($this->items); $i++)
                {
                    $item = &$this->items[$i];;
                    $modelRoom = new BookProModelFlight;
                    $modelRoom->setId($item->flight_id);
                    $room = $modelRoom->getObject();
                    if($room){
                        $item->flight_id = $room->room_type;
                    }
                    
                    $startdate='';
                    if($item->startdate !='0000-00-00 00:00:00')
                        $startdate=JFactory::getDate($item->startdate)->format('d F Y');
                        $item->startdate = $startdate;
                        
                     $enddate='';
                    if($item->enddate !='0000-00-00 00:00:00')
                        $enddate=JFactory::getDate($item->enddate)->format('d F Y');
                        $item->enddate = $enddate;
                        

                }
            }
            $this->params = &JComponentHelper::getParams(OPTION);
            $this->selectable = JRequest::getCmd('task') == 'element';
            parent::display($tpl);


        }                                                             
    }

?>