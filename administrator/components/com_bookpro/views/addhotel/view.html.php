<?php
    defined('_JEXEC') or die('Restricted access');      
    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    //import needed models
    AImporter::model('hotels', 'packagehotels', 'tourpackage', 'packagehotel','itinerary');
    AImporter::helper('tour');

    class BookProViewAddHotel extends BookproJViewLegacy
    {
        protected  $tour_id;
        protected  $itinerary_id;
        
        function display($tpl = null)
        {    
            $this->itinerary_id = ARequest::getUserStateFromRequest('intinerary_id',null, 'int');
            $itineraryModel = new BookProModelItinerary();
            $itineraryModel->setId($this->itinerary_id);
            $itinerary = $itineraryModel->getObject();
            $this->tour_id = $itinerary->tour_id;
           
            $packagetypes = TourHelper::getPackages($this->tour_id);
            $data = '';          
            if($packagetypes){
                $data = '<div class="control-group inline">
                                    <label class="control-label" for="pickup">'.JText::_('COM_BOOKPRO_PACKAGE_TYPE').'</label>
                                    <div style="margin-top:5px;" class="controls">'.JText::_('COM_BOOKPRO_HOTEL').'</div>
                                   </div>';
                for($i=0; $i<count($packagetypes); $i++)
                {
                    $hotelselects = TourHelper::getArrayIdHotelsByTou_idAndItinerary_idAndPackagetype_id($this->tour_id, $this->itinerary_id, $packagetypes[$i]->id);
                    $hotelname = "hotel_id".$i."[]";             
                    $data .= '<div class="control-group inline">
                                <label for="pickup" class="control-label">'.$packagetypes[$i]->title.'</label>
                                <input type="hidden" name="packagetype_ids[]" value="'.$packagetypes[$i]->id.'"/>
                                <div class="controls">'.$this->getHotelBox($hotelselects, $hotelname).'</div>
                              </div>';  
                }
            }                
            $this->assignRef("data",$data);  
            $this->assignRef('id',$id);      
            parent::display($tpl);     
        } 
               
        function getHotelBox($hotelsselects,$name){
            $model = new BookProModelHotels();       
            $lists = $model->getData();   
            return AHtml::bootrapCheckBoxList($lists, $name, '', $hotelsselects, 'id', 'title');   
        }              
        
    }      

?>

