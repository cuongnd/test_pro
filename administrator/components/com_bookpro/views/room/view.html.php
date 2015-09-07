<?php

    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('room','hotels','hotel','facilities');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','document','image');
    AImporter::js('view-images');
    class BookProViewRoom extends BookproJViewLegacy
    {

        function display($tpl = null)
        {   

            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $document = &JFactory::getDocument();
            /* @var $document JDocument */
            $model = new BookProModelRoom();
            $model->setId(ARequest::getCid());
            $obj = &$model->getObject();
            $this->_displayForm($tpl, $obj);    
        }


        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            /* @var $document JDocument */ 
            if($obj->hotel_id){
                $this->hotel_id = $obj->hotel_id;
            }else{
                $this->hotel_id = ARequest::getUserStateFromRequest('hotel_id', '', 'int');
            }

            if($this->hotel_id){   
                $modelHotel = new BookProModelHotel();        
                $modelHotel->setId($this->hotel_id);
                $this->hotel = $modelHotel->getObject();          
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
            $this->assignRef('hotels', $this->getHotelSelect($obj->hotel_id)); 
            $this->assignRef('roomlabels', $this->getRoomlabelSelect($obj->roomlabel_id));
            $this->assignRef('child_price', $this->getChildPrice($obj->child_price));
            $this->assignRef('adult_price', $this->getAdultPrice($obj->adult_price));
            AImporter::helper('facility');
            $facilities=FacilityHelper::getListFacilitiesSelectedByRoomid($obj->id);
            $this->assignRef('facilities', $facilities);

            $adults = explode(',', $obj->adult_price);  
            if($obj->adult_price){   
                $adultnumber = count($adults);
            }else{
                $adultnumber = 0;
            }                           

            $this->assignRef('adultnumber', $adultnumber);

            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);      
             parent::display($tpl);
        }
        function getHotelSelect($select){

            $model = new BookProModelHotels();  
            $param=array();
            //$param['state']='1';
            $model->init($param);
            $list=$model->getData();
            return AHtml::getFilterSelect('hotel_id', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $list, $select, $autoSubmit, '', 'id', 'title');
        }

        function getRoomlabelSelect($select){
            AImporter::model('roomlabels');
            $model = new BookProModelRoomlabels();
            $list=$model->getData();//getItems();
            return AHtml::getFilterSelect('roomlabel_id', JText::_('COM_BOOKPRO_ROOM_LABEL'), $list, $select, $autoSubmit, '', 'id', 'title');
        }    

        function getChildPrice($child_price)
        {
            $arr =  explode(',', $child_price);
            $return='';
            if($child_price){
                for($i=0; $i<count($arr); $i++){
                    $stt = $i+1; 
                    $return .='<div class="control-group" id="input_text"><div id="title_name" class="control-label">'.JText::_("COM_BOOKPRO_CHILD_").' '.$stt.JText::_("COM_BOOKPRO_PRICE").'</div><div id="input_tx" class="controls"><input type="text" name="child_price[]" class="textbox2" value="'.$arr[$i].'" /></div><div class="clear"></div></div>'; 
                }
            }
            return $return;
        }

        function getAdultPrice($adult_price)
        {
            $arr =  explode(',', $adult_price);
            $return='';
            if($adult_price){
                for($j=0; $j<count($arr); $j++){
                    $stt = $j+1; 
                    $return .='<div class="control-group divAtt'.$stt.'" id="input_text"><div id="title_name" class="control-label">'.JText::_("COM_BOOKPRO_ADULT_").' '.$stt.' '.JText::_("COM_BOOKPRO_PRICE").'</div><div id="input_tx" class="controls"><input type="text" name="adult_price[]" class="textbox2" value="'.$arr[$j].'"/><span class="attachB" onClick="removeAtt(\''.$stt.'\')" style="color: blue; cursor: pointer; margin-left: 10px;">'.JText::_('COM_BOOKPRO__TO_REMOVE_ADULT').'</span></div><div class="clear"></div></div>'; 
                }
            }
            return $return;
        }
        function getFacilities($facilities, $hotel_id){
            $model = new BookProModelFacilities();       
            $list=$model->getListQueryByFtypeandhotels(array('0'=>$hotel_id), 1);     
            return AHtml::bootrapCheckBoxList($list,'facility[]','',$facilities,'id', 'title');      
        }        

    }

?>