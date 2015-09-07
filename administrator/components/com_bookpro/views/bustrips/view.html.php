<?php
/**
 * @package     Joomla.Administrator
 * @subpackage
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of Bustrips.
 *
 * @package     Joomla.Administrator
 * @subpackage
 * @since       1.6
 */
class BookproViewBustrips extends BookproJViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;
	protected $context;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $input=JFactory::getApplication()->input;
		$this->items		= $this->get('Items');
        //echo $this->get('DBO')->getQuery()->dump();
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
        $this->dfrom=$this->getDestinationSelectBox($this->state->get('bustrip_filter_from'),'bustrip_filter_from',JText::_('COM_BOOKPRO_SELECT_FROM'));
        $this->dto=$this->getDestinationSelectBox($this->state->get('bustrip_filter_to'),'bustrip_filter_to',JText::_('COM_BOOKPRO_SELECT_TO'));
        $this->fromCountries=$this->getListCountries($this->state->get('bustrip_filter_from_country_id'),'bustrip_filter_from_country_id',JText::_('Select From Country'));
        $this->toCountries=$this->getListCountries($this->state->get('bustrip_filter_to_country_id'),'bustrip_filter_to_country_id',JText::_('Select To Country'));

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Check if there are no matching items
		if (!count($this->items)){
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_Bustrips_MSG_MANAGE_NO_Bustrips'),
				'warning'
			);
		}

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

        $layout = new JLayoutFile('toolbar.newbustrip');
        $bar = JToolBar::getInstance('toolbar');
        JToolbarHelper::title('Bus trip manager');
        JToolbarHelper::addNew('bustrip.add');
        $bar->appendButton('Custom', $layout->render(array()), 'new');
        JToolbarHelper::publish('bustrips.publish', 'JTOOLBAR_PUBLISH', true);
        JToolbarHelper::unpublish('bustrips.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        JToolbarHelper::editList('bustrip.edit');
        JToolBarHelper::deleteList('', 'bustrips.delete', 'Delete');
        JToolbarHelper::custom('bustrips.duplicate', 'copy.png', 'copy_f2.png', 'JTOOLBAR_DUPLICATE', true);
        AImporter::helper('bustrips');

        JHtmlSidebar::addFilter(
            JText::_('JOPTION_SELECT_PUBLISHED'),
            'bustrip_filter_state',
            JHtml::_('select.options', BustripsHelper::getStateOptions(), 'value', 'text', $this->state->get('bustrip_filter_state'))
        );

        JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
        $this->sidebar = JHtmlSidebar::render();
    }

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.from' => JText::_('From'),
			'a.to' => JText::_('To'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
    function getDestinationSelectBox($select, $field = 'from',$text)
    {
        $model_airports=&JModelLegacy::getInstance('airports', 'BookproModel');
        $model_airports->set('list.start', 0);
        $model_airports->set('list.limit', 100);
        $app=JFactory::getApplication();
        $app->setUserState('airport_filter_bus',1);
        $fullList = $model_airports->getItems();
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
        return AHtml::getFilterSelect($field, $text, $option, $select, true, '', 'id', 'treename');
    }
    function getListCountries($select, $field = 'bustrip_filter_from_country_id',$text)
    {
        $modelCountry=&JModelLegacy::getInstance('countries', 'BookproModel');

        $modelCountry->set('list.start', 0);
        $modelCountry->set('list.limit', 100);
        $fullList = $modelCountry->getItems();

        return AHtml::getFilterSelect($field, $text, $fullList, $select, true, '', 'id', 'country_name');
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
