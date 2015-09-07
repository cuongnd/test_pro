<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 84 2012-08-17 07:16:08Z quannv $
    **/
    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request', 'hotel');
    AImporter::model('registerhotels', 'coupon', 'hotel', 'registerhotels');


    class BookProViewCoupon extends JViewLegacy
    {

        function display($tpl = null)
        {
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $document = &JFactory::getDocument();
            /* @var $document JDocument */
            $model = new BookProModelCoupon();
            $model->setId(ARequest::getCid());
            $obj = &$model->getObject();
            $this->_displayForm($tpl, $obj);             
        }


        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            
            if($obj->hotel_id){
                $this->hotel_id = $obj->hotel_id;
            }else{
                if(JRequest::getVar('hotel_id')){ 
                    $this->hotel_id =  JRequest::getVar('hotel_id');
                }else{
                    $modulehotels   = new BookProModelRegisterHotels();
                    $listshotels    = array('userid'=>HotelHelper::getCustomerIdByUserLogin()); 
                    $modulehotels->init($listshotels);
                    $hotels         = $modulehotels->getData(); 
                    if($hotels){
                        $this->hotel_id = $hotels[0]->id;       
                    }
                }
            }
                        
            $error = JRequest::getInt('error');
            $data = JRequest::get('post');
            if ($error) {
                $obj->bind($data);

            }

            if (! $obj->id && ! $error) {
                $obj->init();
            } 
            
            JFilterOutput::objectHTMLSafe($obj);
            $document->setTitle($obj->title);
            $params = JComponentHelper::getParams(OPTION);
            /* @var $params JParameter */

            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);
            
            $this->assignRef('hotels', HotelHelper::getHotelSelectBoxBySupplier($this->hotel_id));
            
            parent::display($tpl);
        }

    }

?>