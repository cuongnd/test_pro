<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

jimport('joomla.filesystem.folder');

/**
 * Form Field to display a list of the layouts for module display from the module or template overrides.
 *
 * @package     Joomla.Legacy
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldScreenSize extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'ScreenSize';

	/**
	 * Method to get the field input for module layouts.
	 *
	 * @return  string  The field input.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Get the client id.
		$clientId = $this->element['client_id'];

		if (is_null($clientId) && $this->form instanceof JForm)
		{
			$clientId = $this->form->getValue('client_id');
		}
		$clientId = (int) $clientId;

		$client = JApplicationHelper::getClientInfo($clientId);

		// Get the module.
		$module = (string) $this->element['module'];

		if (empty($module) && ($this->form instanceof JForm))
		{
			$module = $this->form->getValue('module');
		}
		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
		$listScreenSize=UtilityHelper::getListScreenSize();
		$option=array();
		foreach($listScreenSize as $screenSize)
		{
			$option[] = JHtmlSelect::option($screenSize,$screenSize,'id','title');

		}

		return JHTML::_('select.genericlist', $option, $this->name, '', 'id', 'title', $this->value);
	}
}
