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
    AImporter::model('hotels', 'hotel', 'roomlabel1');
    //import custom icons
    AHtml::importIcons();

    if(! defined('SESSION_PREFIX')){
        if (IS_ADMIN) {
            define('SESSION_PREFIX', 'bookpro_room_list_');
        } 
    }

    class BookProViewRooms extends BookproJViewLegacy

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
            $document->setTitle(JText::_('COM_BOOKPRO_LIST_OF_ROOMS'));
            $model = new BookProModelRooms();
            $this->lists = array();
            $this->lists['limit']       = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
            $this->lists['limitstart']  = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order']       = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
            $this->lists['order_Dir']   = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
            $this->lists['hotel_id']    = ARequest::getUserStateFromRequest('hotel_id', '', 'int');
            $this->lists['title']       = ARequest::getUserStateFromRequest('title', '', 'string');
            $model->init($this->lists);           
            $this->pagination = &$model->getPagination();
            $this->items = $model->getData();
            $this->params = &JComponentHelper::getParams(OPTION);
            $this->selectable = JRequest::getCmd('task') == 'element';
            //$this->assignRef('hotels', $this->getHotelSelectBox($this->lists['hotel_id']));

            if($this->lists['hotel_id']){   
                $modelHotel = new BookProModelHotel();        
                $modelHotel->setId($this->lists['hotel_id']);
                $this->hotel = $modelHotel->getObject();          
            }

            parent::display($tpl);


        }
        function getHotelSelectBox($select, $field = 'hotel_id', $autoSubmit = true)
        {
            $model = new BookProModelHotels();
            $lists = array('limit' => null , 'limitstart' => null , 'state' => null , 'access' => null , 'order' => 'ordering' , 'order_Dir' => 'ASC' , 'search' => null );
            $model->init($lists);
            $fullList = $model->getData();
            return AHtml::getFilterSelect($field, 'COM_BOOKPRO_SELECT_HOTEL', $fullList, $select, $autoSubmit, '', 'id', 'title');
        }

        function getNameHotelById($id)
        {
            if($id){
                $model = new BookProModelHotel();
                $model->setId($id);
                $obj = &$model->getObject();
                return $obj->title;
            }
            return '';
        }

        function getNameRoomLabelById($id)
        {                  
            if($id){
                $model = new BookProModelRoomlabel1();
                $model->setId($id);
                $obj = &$model->getObject();  
                return $obj->title;
            }
            return '';
        }

    }

?>