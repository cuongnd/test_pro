<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 47 2012-07-13 09:43:14Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed JoomLIB helpers
AImporter::helper('route', 'bookpro', 'request');
AImporter::model('buses','airports','agents');
//import needed assets
//import custom icons
AHtml::importIcons();
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_bustrips_list_');
}

class BookProViewBusTrips extends BookproJViewLegacy
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
        $app=JFactory::getApplication();
		$document = &JFactory::getDocument();
		$document->setTitle(JText::_('BusTrip list'));
        $this->state		= $this->get('State');
        $this->items		= $this->get('items');
        $this->pagination		= $this->get('pagination');
        $this->dfrom=$this->getDestinationSelectBox($this->state->get('filter.from'),'filter.from',JText::_('COM_BOOKPRO_SELECT_FROM'));
        $this->dto=$this->getDestinationSelectBox($this->state->get('filter.to'),'filter.to',JText::_('COM_BOOKPRO_SELECT_TO'));
        $this->addToolbar();
		parent::display($tpl);

	}
    protected function addToolbar()
    {
        JToolBarHelper::title('List bustrip');
        JToolbarHelper::addNew('bustrips.add');
        JToolbarHelper::editList('bustrips.edit');
        JToolbarHelper::publish('bustrips.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('bustrips.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::trash('bustrips.trash');
    }

    function getDestinationSelectBox($select, $field = 'from',$text)
	{
		$model = new BookProModelAirports();
        $model->set('list.start', 0);
        $model->set('list.limit', 100);

		$fullList = $model->getItems();
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

        $option=static::treeReCurseCategories(1,'' , array(),$children,99,0,0);
        return AHtml::getFilterSelect($field, 'Select Destination', $option, $select, true, '', 'id', 'treename');
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

}

?>