<?php
    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('room','hotels','hotel','registerhotels','facilities');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request','document','image','hotel');
    AImporter::js('view-images');


    class BookProViewRoom extends JViewLegacy
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

            /*$this->assignRef('roomlabels', $this->getRoomlabelSelect($obj->roomlabel_id));  */
            $this->assignRef('child_price', $this->getChildPrice($obj->child_price));
            $this->assignRef('adult_price', $this->getAdultPrice($obj->adult_price));
            $adults = explode(',', $obj->adult_price);  
            if($obj->adult_price){   
                $adultnumber = count($adults);
            }else{
                $adultnumber = 0;
            }                           
            AImporter::helper('facility');
            $facilities=FacilityHelper::getListFacilitiesSelectedByRoomid($obj->id);
            
            $this->assignRef('facilities', $facilities);

            $this->assignRef('adultnumber', $adultnumber);

            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);      
            $this->assignRef('hotels', HotelHelper::getHotelSelectBoxBySupplier($this->hotel_id));

            parent::display($tpl);
        }


        /*        function getRoomlabelSelect($select){
        AImporter::model('roomlabels');
        $model = new BookProModelRoomlabels();
        $list=$model->getData();//getItems();
        return AHtmlFrontEnd::getFilterSelect('roomlabel_id', JText::_('COM_BOOKPRO_ROOM_LABLE'), $list, $select, $autoSubmit, '', 'id', 'title');
        } */   

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
            return AHtmlFrontEnd::bootrapCheckBoxList($list,'facility[]','',$facilities,'id', 'title');
        } 
    }

?>