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
AImporter::model('airports',"buses",'agents','seattemplates');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request');
//import needed assets
AHtml::importIcons();

class BookProViewBusTrip extends BookproJViewLegacy
{
    protected $form;

    protected $item;

    protected $state;


    function display($tpl = null)
    {
        $this->form		= $this->get('Form');
        $this->item		= $this->get('Item');
        $this->state	= $this->get('State');
        $this->addToolbar();
        parent::display($tpl);
    }
    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        JToolBarHelper::title('Edit bus route');
        JToolbarHelper::apply('bustrip.apply');
        JToolbarHelper::save('bustrip.save');
        JToolbarHelper::cancel('bustrip.cancel');
    }


    /**
     * Prepare to display page.
     *
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
    function geBustripByBustripParentId($parent_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('bustrip.*');
        $query->from('#__bookpro_bustrip AS bustrip');
        $query->where('bustrip.id='.$parent_id);
        $db->setQuery($query);
        return $db->loadObject();

    }
    function _displayForm($tpl, $flight)
    {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */
        $input=JFactory::getApplication()->input;


        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $flight->bind($data);
        }
        $layout=$this->getLayout();

        if (! $flight->id && ! $error) {
            $flight->init();
        }
        JFilterOutput::objectHTMLSafe($flight);

        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $this->dfrom=$this->getDestinationSelectBox($flight->from,'from',$bustrip->from);
        $this->dto=$this->getDestinationSelectBox($flight->to,'to',$bustrip->to);
        $airlines=$this->getBusSelectBox($flight->bus_id);
        $parentbox = $this->getParentBox($flight->parent_id);
        $listseattemplate = $this->getListBlockLayout($flight->seat_layout_id);
        $agentBox = $this->getAgentSelectBox($flight->agent_id);

        $this->assignRef("listseattemplate",$listseattemplate);


        $this->assignRef("bus",$airlines);
        $this->assignRef('obj', $flight);
        $this->assignRef("agents",$agentBox);
        $this->assignRef('parentbox', $parentbox);
        $this->assignRef('params', $params);
        parent::display($tpl);
    }

    function getAgentSelectBox($select,$field = 'agent_id'){
    	$model = new BookProModelAgents();


    	$fullList = $model->getData();
    	//echo '<pre>';    var_dump($fullList);exit();
    	return AHtml::getFilterSelect($field, 'Select Agent', $fullList, $select, false, '', 'id', 'company');

    }
 	function getListBlockLayout($select,$field = 'seat_layout_id'){
        $model = new BookProModelSeattemplates();
        $fullList = $model->getData();
        return AHtml::getFilterSelect($field, 'Select Seat layout', $fullList, $select, false, '', 'id', 'title');

    }
	function getBusSelectBox($select, $field = 'bus_id')
    {
        $model = new BookProModelBuses();
        $lists = array( 'state' => null  , 'order' => 'ordering' , 'order_Dir' => 'ASC' );
        $model->init($lists);
        $fullList = $model->getData();
        return AHtml::getFilterSelect($field, 'Select Bus', $fullList, $select, false, '', 'id', 'title');
    }
 	function getDestinationSelectBox($select, $field = 'from',$destination_parent_id=0)
    {
        $model = new BookProModelAirports();
        $model->set('filter.bus', 1);
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
        $fullList=JArrayHelper::pivot($fullList,'id');
        $option1=array();

        $option1[$destination_parent_id]=$fullList[$destination_parent_id];
        $option1[$destination_parent_id]->treename=$option1[$destination_parent_id]->title;
        $option2=static::treeReCurseCategories($destination_parent_id,'' , array(),$children,99,0,0);
        $option=array_merge_recursive($option1,$option2);
        return AHtml::getFilterSelect($field, 'Select Destination', $option, $select, false, '', 'id', 'treename');
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


    function getBusStopSelectBox($field = 'dest_id[]')
    {
    	$model = new BookProModelAirports();
    	$lists = array('limit' => null , 'limitstart' => null , 'bus'=>1 ,'state' => null , 'order' => 'ordering' , 'order_Dir' => 'ASC');
    	$model->init($lists);
    	$fullList = $model->getData();
    	return AHtml::getFilterSelect($field, 'Select Bus stop', $fullList, '', false, '', 'id', 'title');
    }
    function getParentBox($select){


    	$options = array();
    	$db		= JFactory::getDbo();
    	$query	= $db->getQuery(true);

    	$query->select('a.id as value, a.level,CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS `text`');

    	$query->from('#__bookpro_bustrip AS a');
    	$query->join('LEFT', $db->quoteName('#__bookpro_dest').' AS dest1 ON a.from =  dest1.id');
    	$query->join('LEFT', $db->quoteName('#__bookpro_dest').' AS dest2 ON a.to =  dest2.id');

    	//$query->where('a.state IN (0,1)');
    	$query->where('a.parent_id =0');
    	//$query->group('a.id, a.title, a.level, a.lft, a.rgt, a.parent_id');
    	//$query->order('a.lft ASC');

    	// Get the options.
    	$db->setQuery($query);

    	$options = $db->loadObjectList();

    	// Check for a database error.
    	if ($db->getErrorNum()) {
    		JError::raiseWarning(500, $db->getErrorMsg());
    	}

    	// Pad the option text with spaces using depth level as a multiplier.
    	for ($i = 0, $n = count($options); $i < $n; $i++)
    	{
    	// Translate ROOT

    	if ($options[$i]->level == 0) {
    	$options[$i]->text = JText::_('JGLOBAL_ROOT_PARENT');
    	}

    	$options[$i]->text = str_repeat('- ', $options[$i]->level).$options[$i]->text;
    	}
    	$options = array();
    	array_unshift($options, JHtml::_('select.option', 0, JText::_('JGLOBAL_ROOT')));

		return  JHtmlSelect::genericlist($options, 'parent_id','','value','text',$select);


    }
}

?>