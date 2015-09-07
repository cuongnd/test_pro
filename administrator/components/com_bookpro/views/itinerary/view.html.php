<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 91 2012-08-24 16:29:55Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');

    //import needed Joomla! libraries
    jimport('joomla.application.component.view');
    AImporter::model('airport',"country",'airports','tours');
    AImporter::helper('bookpro', 'request','tour');
    AHtml::importIcons();

    class BookProViewItinerary extends BookproJViewLegacy
    {


        function display($tpl = null)
        {
            $mainframe = &JFactory::getApplication();
            /* @var $mainframe JApplication */
            $document = &JFactory::getDocument();
            /* @var $document JDocument */
            $model = new BookProModelItinerary();
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
                $tour_id=JRequest::getInt('tour_id',null);
                if($tour_id)
                    $obj->tour_id=$tour_id;
            }
            JFilterOutput::objectHTMLSafe($obj);
            $document->setTitle($obj->title);
            $params = JComponentHelper::getParams(OPTION);
            /* @var $params JParameter */

            $this->assignRef('meal', $this->getmeals($obj->meal));
            $this->assignRef("tours",$this->getTourBox($obj->tour_id));
            
            $this->assignRef("dests",$this->getDestBox($obj->dest_id));
            $this->assignRef('obj', $obj);
            $this->assignRef('params', $params);
            parent::display($tpl);
        }

        function getTourBox($select){
            $model = new BookProModelTours();
            $lists = $model->getData();
            return AHtml::getFilterSelect('tour_id', 'Select Tour', $lists, $select, false, '', 'id', 'title');
        }
        function getDestBox($select){
            $model = new BookProModelAirports();
            
            
            $lists = $model->getDestinationParents();
           
            
            //var_dump($lists);
            //return JHtml::_('select.genericlist',$lists,'dest_id','','id','title',$select,false);
            
            return AHtml::getFilterSelect('dest_id', JText::_('Select Dest'), $lists, $select, false, 'id="dest_id" class="validate-select required"', 'id', 'title');
           // return AHtml::bootrapCheckBoxList($lists,'dests[]','',$dest,'id', 'title');
        }
        function getmeals($meals){
            $meals=strtolower($meals);
            $meals=explode(';',$meals);
            $lists=TourHelper::getMealList();
            return AHtml::bootrapCheckBoxList($lists,'meal[]','',$meals,'id', 'title');

        }

        function getAirlineBox($select){
            $model = new BookProModelAirlines();
            $lists = $model->getItems();
            return AHtml::getFilterSelect('airline_id', 'Select Airline', $lists, $select, false, '', 'id', 'title');
        }

    }

?>