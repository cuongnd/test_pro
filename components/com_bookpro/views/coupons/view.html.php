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
    AImporter::helper('route', 'bookpro', 'request', 'hotel');
    //import needed assets
    AHtmlFrontEnd::importIcons();
    AImporter::model('hotels', 'hotel', 'coupons', 'registerhotels');
    if (! defined('SESSION_PREFIX')) {
        if (IS_ADMIN) {
            define('SESSION_PREFIX', 'bookpro_coupon_list_');
        } elseif (IS_SITE) {
            define('SESSION_PREFIX', 'bookpro_site_coupon_list_');
        }
    }

    class BookProViewCoupons extends JViewLegacy
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

            $document->setTitle(JText::_('COM_BOOKPRO_COUPON_LIST'));
            
            $model = new BookProModelCoupons();

            $this->lists = array();

            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit', $mainframe->getCfg('list_limit'), 'int');
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');

            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
            $this->lists['title'] = ARequest::getUserStateFromRequest('title', '', 'string');
            $this->lists['userid'] = HotelHelper::getCustomerIdByUserLogin();
            
            if(JRequest::getVar('hotel_id')){ 
                $this->lists['hotel_id'] =  JRequest::getVar('hotel_id');
            }else{
                $modulehotels   = new BookProModelRegisterHotels();
                $listshotels    = array('userid'=>HotelHelper::getCustomerIdByUserLogin()); 
                $modulehotels->init($listshotels);
                $hotels         = $modulehotels->getData(); 
                if($hotels){
                    $this->lists['hotel_id'] = $hotels[0]->id;       
                }
            }
                 
            
            $model->init($this->lists);

            $this->pagination = &$model->getPagination();

            $this->items = &$model->getData();

            $this->params = &JComponentHelper::getParams(OPTION);

            $this->selectable = JRequest::getCmd('task') == 'element';
            
            $this->hotels = HotelHelper::getHotelSelectBoxSearchBySupplier($this->lists['hotel_id'])?HotelHelper::getHotelSelectBoxSearchBySupplier($this->lists['hotel_id']):'';
             
            parent::display($tpl);
        }
    }

?>