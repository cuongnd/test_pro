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
class productViewCpanel extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{


        productHelper::addSubmenu('product');
        $this->sidebar = JHtmlSidebar::render();
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
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo		= $this->canDo;
			JToolbarHelper::title(JText::_('com_product_PAGE_' . ($checkedOut ? 'VIEW_product' : ($isNew ? 'ADD_product' : 'EDIT_product'))), 'pencil-2 product-add');

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_product', 'core.create')) > 0))
		{
			JToolbarHelper::apply('product.apply');
			JToolbarHelper::save('product.save');
			JToolbarHelper::save2new('product.save2new');
			JToolbarHelper::cancel('product.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('product.apply');
					JToolbarHelper::save('product.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('product.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('product.save2copy');
			}

			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_product.product', $this->item->id);
			}

			JToolbarHelper::cancel('product.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_product_product_MANAGER_EDIT');
	}
}
