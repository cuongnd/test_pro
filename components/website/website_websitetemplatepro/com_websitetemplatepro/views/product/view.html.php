<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View to edit a plugin.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.5
 */
class websitetemplateproViewproduct extends JViewLegacy
{
	protected $item;

	protected $form;

	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $layout=$this->getLayout();

		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
        if($layout=='frontend')
        {

        }else{
            $this->addToolbar();
            $this->form		= $this->get('Form');
        }

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}


		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{

		$canDo = JHelperContent::getActions('com_websitetemplatepro');

		JToolbarHelper::title(JText::sprintf('product', JText::_($this->item->name)), 'power-cord plugin');

		// If not checked out, can save the item.
        JToolbarHelper::apply('product.apply');
        JToolbarHelper::save('product.save');
		JToolbarHelper::cancel('product.cancel', 'JTOOLBAR_CLOSE');
		JToolbarHelper::divider();
		// Get the help information for the plugin item.

		$lang = JFactory::getLanguage();

		$help = $this->get('Help');
		if ($lang->hasKey($help->url))
		{
			$debug = $lang->setDebug(false);
			$url = JText::_($help->url);
			$lang->setDebug($debug);
		}
		else
		{
			$url = null;
		}
		JToolbarHelper::help($help->key, false, $url);
	}
}
