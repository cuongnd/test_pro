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
class phpmyadminViewShape extends JViewLegacy
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
		JToolbarHelper::apply('shape.apply');
		JToolbarHelper::save('shape.save');
		JToolbarHelper::save2new('shape.save2new');
		JToolbarHelper::cancel('shape.cancel');
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
