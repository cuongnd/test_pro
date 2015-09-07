<?php
/**
 * @version		$Id: view.html.php 1992 2013-07-04 16:36:38Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.application.component.view');

class K2ViewPage extends K2View
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$document = JFactory::getDocument();
		parent::display($tpl);
	}

}
