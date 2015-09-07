<?php
/**
 * @version		$Id: item.php 1985 2013-06-25 16:58:55Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die ;

jimport('joomla.application.component.controller');

class K2ControllerPage extends K2Controller
{

	public function display()
	{
        JRequest::setVar('view', 'page');
        parent::display();
	}



}
