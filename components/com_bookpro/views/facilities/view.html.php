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
    AImporter::helper('route', 'bookpro', 'request','hotel');
    AImporter::model('hotels', 'hotel', 'roomlabel1','room','rooms', 'registerhotels');
    //import custom icons

    class BookProViewFacilities extends JViewLegacy

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
                                       
            $this->items        = $this->get('Items');
            $this->pagination    = $this->get('Pagination');
            $this->state        = $this->get('State');
            AImporter::model('hotels');
            $hotels = $this->getHotelSelect( $this->state->get('filter.hote_id'));
            
            $this->assignRef('hotels',$hotels );  
            parent::display($tpl);


        }
        function getHotelSelect($select){

            $modelhotel = new BookProModelRegisterHotels();
            $param=array('userid'=>HotelHelper::getCustomerIdByUserLogin());
            $modelhotel->init($param);
            $hotels = $modelhotel->getData();
            return AHtmlFrontEnd::getFilterSelect('search_hotel_id', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $hotels, $select, $autoSubmit, '', 'id', 'title');
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