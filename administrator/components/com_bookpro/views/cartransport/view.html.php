<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 81 2012-08-11 01:16:36Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');


    //import needed models
    AImporter::model('cardestination', 'cardestinations', 'cartransportcars','cars');
    //import needed JoomLIB helpers
    AImporter::helper('bookpro', 'request');
    //import needed assets
    AHtml::importIcons();

    class BookProViewCarTransport extends BookproJViewLegacy
    {


        function display($tpl = null)
        {
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $document = &JFactory::getDocument();
            /* @var $document JDocument */
            $model = new BookProModelCarTransport();
            $model->setId(ARequest::getCid());

            $obj = &$model->getObject();


            $this->_displayForm($tpl, $obj);


        }

        /**
        * Prepare to display page.
        * 
        * @param string $tpl name of used template
        * @param TableCustomer $customer
        * @param JUser $user
        */
        function _displayForm($tpl, $obj)
        {
            $document = &JFactory::getDocument();
            /* @var $document JDocument */

            $error = JRequest::getInt('error');
            $data = JRequest::get('post');
            if ($error) {
                $obj->bind($data);
            }

            if (! $obj->id && ! $error) {
                $obj->init();
            }
            JFilterOutput::objectHTMLSafe($obj);

            $params = JComponentHelper::getParams(OPTION);
            /* @var $params JParameter */
            $frombox=$this->getDestinationSelectBox($obj->from,'from');
            $fobox=$this->getDestinationSelectBox($obj->to,'to');

            $this->assignRef("dfrom",$frombox);
            $this->assignRef("dto",$fobox);
            $this->assignRef('obj', $obj);
            $this->assign('cars',$this->getCarBox($obj->id));
            $this->assignRef('params', $params);  
            parent::display($tpl);
        }

        function getDestinationSelectBox($select, $field = 'from')
        {
            $model = new BookproModelCarDestinations();
            $lists = array();
            $model->init($lists);
            $fullList = $model->getData();                 
            return AHtml::getFilterSelect($field, 'Select Destination', $fullList, $select, false, '', 'id', 'title');  
        }

        function getCarBox($transport_id)
        {
            $cartransportcars=array();
            if($transport_id){
                $lists = array('car_transport_id'=>$transport_id);
                $cartransportcarsModel = new BookProModelCarTransportCars();     
                $cartransportcarsModel->init($lists);
                $cartransportcars = $cartransportcarsModel->getData();       
            }

           

            $model = new BookproModelCars();
            $lists = array();
            $model->init($lists);
            $fullList = $model->getData();

            $return='<table>';
            if(count($fullList)>0)
                for($i=0; $i<count($fullList); $i++ )    
                {
                    $checked=$price='';
                    $price='';
                    $car = $fullList[$i];               
                    if(count($cartransportcars)>0)
                        foreach($cartransportcars as $key => $value)
                        {
                            if($car->id == $value->car_id){
                                $checked='checked="checked"';
                                $price = $value->car_price;
                            }       
                    }   
                    if($checked){
                        $return .= '<tr><td><label class="checkbox" style="float:left;">
                        <input type="checkbox" name="car_id[]" class="car_id" value="'.$car->id.'" checked="checked"> '.$car->name.' 
                        </label></td>                                                               
                        <td><input type="text" name="car_price[]" class="car_price" value="'.$price.'"> '.JText::_('Price').'</td></tr>';                                                    
                    }else{
                        $return .= '<tr><td><label class="checkbox" style="float:left;">
                        <input type="checkbox" name="car_id[]" class="car_id" value="'.$car->id.'"> '.$car->name.' 
                        </label></td>                                                               
                        <td><input type="text" name="car_price[]" class="car_price" value=""> '.JText::_('Price').'</td></tr>';
                    } 
            }  
            $return.='</table>';
                         
            return $return;
        }

    }

?>