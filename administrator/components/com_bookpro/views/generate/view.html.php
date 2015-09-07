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
AImporter::model('airports',"buses",'bustrip','seattemplates','agents');
AImporter::helper('bookpro', 'request');

class BookProViewGenerate extends BookproJViewLegacy
{
    function display($tpl = null)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $model = new BookProModelBusTrip();
        $model->setId(ARequest::getCid());
        $flight = &$model->getObject();
        $this->_displayForm($tpl, $flight);
	    }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    function _displayForm($tpl, $flight)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        
        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $flight->bind($data);
        }
        
        if (! $flight->id && ! $error) {
            $flight->init();
        }
        JFilterOutput::objectHTMLSafe($flight);
       
        /* @var $params JParameter */
        $airportFrombox=$this->getDestinationSelectBox($flight->from,'dest_id[]');
        $airlines=$this->getBusSelectBox($flight->bus_id);
        
        $this->assignRef("dfrom",$airportFrombox);
        $this->assignRef("bus",$airlines);
        $this->assignRef('obj', $flight);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }
	function getBusSelectBox($select, $field = 'bus_id')
    {
        $model = new BookProModelBuses();
        $lists = array( 'state' => null  , 'order' => 'ordering' , 'order_Dir' => 'ASC' );
        $model->init($lists);
        $fullList = $model->getData();
        return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_BUS'), $fullList, $select, false, 'class="validate-select"', 'id', 'title');
    }


    public static function treeReCurseCategories($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
    {
        if (@$children[$id] && $level <= $maxlevel)
        {
            foreach ($children[$id] as $v)
            {
                $id = $v->id;

                if ($type)
                {
                    $pre = '<sup>|_</sup>&#160;';
                    $spacer = '.&#160;&#160;&#160;&#160;&#160;&#160;';
                }
                else
                {
                    $pre = '- ';
                    $spacer = '&#160;&#160;';
                }

                if ($v->parent_id == 0)
                {
                    $txt = $v->title;
                }
                else
                {
                    $txt = $pre . $v->title;
                }

                $list[$id] = $v;
                $list[$id]->treename = $indent . $txt;
                $list[$id]->children = count(@$children[$id]);
                $list = static::treeReCurseCategories($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
            }
        }

        return $list;
    }


 	function getDestinationSelectBox($select, $field = 'dest_id[]')
    {

        $model = new BookProModelAirports();
        $fullList = $model->getFullList();

        $children = array();
        if(!empty($fullList)){

            $children = array();

            // First pass - collect children
            foreach ($fullList as $v)
            {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }
        $fullList=static::treeReCurseCategories(1,'' , array(),$children,99,0,0);

        return AHtml::getFilterSelect($field, 'Select Destination', $fullList, $select, false, '', 'id', 'treename');

    }
    function getSeatLayout(){
    	$model = new BookProModelSeattemplates();
    	$fullList = $model->getData();
    	return AHtml::getFilterSelect('seat_layout_id', 'Select Seat layout', $fullList, $select, false, '', 'id', 'title');
    
    }
    function getAgentSelectBox(){
    	$model = new BookProModelAgents();
    	$fullList = $model->getData();
    	return AHtml::getFilterSelect('agent_id', 'Select Agent', $fullList, $select, false, '', 'id', 'company');
    
    }
 
}

?>