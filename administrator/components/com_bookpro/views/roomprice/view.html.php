<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('tours', 'tourpackages', 'tourpackage','tour','roompricelogs');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','document','image','tour');
    AImporter::js('view-images');


    class BookProViewRoomPrice extends BookproJViewLegacy
    {
        var $lists;
        var $items;
        var $pagination;
        var $selectable;
        var $params;   
        var $tourpackage_id;

        function display($tpl = null)
        {        
            $mainframe = &JFactory::getApplication();
            $document = &JFactory::getDocument();
            $mainframe = &JFactory::getApplication();
            $document = &JFactory::getDocument();
            $model = new BookProModelRoomPrice();
            $model->setId(ARequest::getCid());
            $obj = &$model->getObject();
            $this->_displayForm($tpl, $obj); 
        }
        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            $tour_id = ARequest::getUserStateFromRequest('tour_id', '', 'int'); 
            $this->tourpackage_id = ARequest::getUserStateFromRequest('tourpackage_id', '', 'int'); 

            if($tour_id){   
                $modelTour = new BookProModelTour();        
                $modelTour->setId($tour_id);
                $this->tour = $modelTour->getObject();                  
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
            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);
            $model = new BookProModelRoomPriceLogs();
            $this->lists = array();
            $this->lists['limit'] = ARequest::getUserStateFromRequest('limit',0, 'int');
            $this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('filter_order', 'id', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('filter_order_Dir', 'DESC', 'word');
            $this->lists['tour_id'] = ARequest::getUserStateFromRequest('tour_id', '', 'int');  
            $this->lists['tourpackage_id'] = $this->tourpackage_id;
            $model->init($this->lists);
            $this->pagination = &$model->getPagination();
            $this->items = &$model->getData();    
            //$this->tourpackage_id               
            $tourpackage = TourHelper::getTourPackageById($this->tourpackage_id);
            $this->assignRef('tourpackagename', $tourpackage->packagetitle);
            //$this->assignRef('tourpackages', $this->getTourPackageSelect($obj->tourpackage_id,$this->lists['tour_id']));

            $this->assignRef("roomtypes",$this->getRoomTypeBox($this->tourpackage_id));
            if(count($this->items)>0)
            {
                for($i=0; $i<count($this->items); $i++)
                {
                    $item = &$this->items[$i];
                    /*$modelTourPackage = new BookProModelTourPackage();
                    $modelTourPackage->setId($item->tourpackage_id);   
                    $tourpackage = $modelTourPackage->getObject();  */    
                    $tourpackage = TourHelper::getTourPackageById($item->tourpackage_id);      

                    if($tourpackage){
                        $item->packagetitle = $tourpackage->packagetitle;
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
            parent::display($tpl);
        }

        function getTourPackageSelect($select,$tour_id){ 
            $model = new BookProModelTourPackages();
            $param=array('tour_id'=>$tour_id);
            $model->init($param);
            $list=$model->getData();
            //echo "<pre>";var_dump($list);exit();
            return AHtml::getFilterSelect('tourpackage_id', 'COM_BOOKPRO_SELECT_TOUR_PACKAGE', $list, $select, '', '', 'id', 'packagetitle');
        }     

        function getRoomTypeBox($tourpackage_id){
            AImporter::model('roomtypes');
            $model  = new BookProModelRoomTypes();  
            $list   = $model->getRoomTypesDataByPakageId($tourpackage_id);
            $return = '';
            if($list){ 
                $return .='<table><tr><td><strong>'.JText::_("COM_BOOKPRO_ROOM_TYPE").'</strong></td><td width="10%"></td><td><strong>'.JText::_("COM_BOOKPRO_ROOM_RATE_PRICE").'</strong></td></tr>';
                foreach($list as $key =>$value)
                {
                    $return .= '<tr>';
                    $return .= '<td>'.$value->title.'<input type="hidden" name="roomtype_id[]" value="'.$value->id.'"></td>';                                               
                    $return .= '<td></td>';
                    $return .= '<td><input type="text" class="text_area input-mini" type="text" size="60" maxlength="255" name="price[]" value=""></td>';
                    $return .= '</tr>';
                }
                $return .= '</table>';
            }        
            return $return;
        }


    }

?>