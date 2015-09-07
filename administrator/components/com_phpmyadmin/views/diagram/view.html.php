<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit an product.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_product
 * @since       1.6
 */
class phpmyadminViewdiagram extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');

		$this->form		= $this->get('Form');
		$this->tables=$this->getListTable('bookpro');
		$this->tablesAndField=$this->getListTableAndField('bookpro');

		$this->addToolbar();
        parent::display($tpl);

	}
	public function displayTable($tpl = null)
	{
		$this->table      = $this->get('table');
		parent::display($tpl);
	}
	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JToolbarHelper::apply('diagram.apply');
		JToolbarHelper::save('diagram.save');
		JToolbarHelper::save2new('diagram.save2new');
		JToolbarHelper::cancel('diagram.cancel');
		JToolbarHelper::custom('diagram.load', 'refresh.png', 'refresh_f2.png', 'Load', false);
		JToolbarHelper::custom('diagram.exportJSON', 'refresh.png', 'refresh_f2.png', 'Export graph as JSON', false);
		JToolbarHelper::custom('diagram.undo', 'refresh.png', 'refresh_f2.png', 'Undo', false);
		JToolbarHelper::custom('diagram.redo', 'refresh.png', 'refresh_f2.png', 'Redo', false);
		JToolbarHelper::custom('diagram.clear', 'refresh.png', 'refresh_f2.png', 'Clear', false);
		JToolbarHelper::custom('diagram.export_svg', 'refresh.png', 'refresh_f2.png', 'export as SVG', false);
		JToolbarHelper::custom('diagram.export_png', 'refresh.png', 'refresh_f2.png', 'export as PNG', false);
		JToolbarHelper::custom('diagram.print', 'refresh.png', 'refresh_f2.png', 'Print', false);
		JToolbarHelper::custom('diagram.zoom_in', 'refresh.png', 'refresh_f2.png', 'Zoom in', false);
		JToolbarHelper::custom('diagram.zoom_out', 'refresh.png', 'refresh_f2.png', 'Zoom Out', false);
/*		JToolbarHelper::custom('diagram.rebuild', 'refresh.png', 'refresh_f2.png', 'JToolbar_Rebuild', false);
		JToolbarHelper::custom('diagram.rebuild', 'refresh.png', 'refresh_f2.png', 'JToolbar_Rebuild', false);
		JToolbarHelper::custom('diagram.rebuild', 'refresh.png', 'refresh_f2.png', 'JToolbar_Rebuild', false);*/
	}
    /**
     * Returns an array of fields the table can be sorted by
     *
     * @return  array  Array containing the field name to sort by as the key and display text as value
     *
     * @since   3.0
     */
	function getListTable($fillter)
	{
		$db=JFactory::getDbo();
		$tables=$db->getTableList();
		foreach($tables as $key=>$table)
		{
			if(!strpos($table,$fillter))
			{
				unset($tables[$key]);
			}
		}
		return $tables;
	}
	function getListTableAndField($fillter)
	{
		$i=0;
		$db=JFactory::getDbo();
		$listTableAndField=array();
		$tables=$db->getTableList();
		foreach($tables as $key=>$table)
		{
			if(!strpos($table,$fillter))
			{
				unset($tables[$key]);
				continue;
			}
			$listTableAndField[$i]['table']=str_replace('ueb3c_','',$table);
			$fields=$db->getTableColumns($table);
			$j=0;
			foreach($fields as $field=>$type)
			{
				if($j>4)
				{
					//break;
				}
				$item=new stdClass();
				$item->column=$field;
				$item->type=$type;
				$listTableAndField[$i]['columns'][]=$item;
				$j++;
			}
			$i++;
		}
		return $listTableAndField;
	}
    protected function getSortFields()
    {
        return array(
            'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
            'a.state'        => JText::_('JSTATUS'),
            'a.title'        => JText::_('JGLOBAL_TITLE'),
            'category_title' => JText::_('JCATEGORY'),
            'access_level'   => JText::_('JGRID_HEADING_ACCESS'),
            'a.created_by'   => JText::_('JAUTHOR'),
            'language'       => JText::_('JGRID_HEADING_LANGUAGE'),
            'a.created'      => JText::_('JDATE'),
            'a.id'           => JText::_('JGRID_HEADING_ID'),
            'a.featured'     => JText::_('JFEATURED')
        );
    }
}
