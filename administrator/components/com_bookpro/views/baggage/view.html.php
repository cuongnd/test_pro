 <?php
 /**
  * @package 	Bookpro
  * @author 		Nguyen Dinh Cuong
  * @link 		http://ibookingonline.com
  * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
  * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
  * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
  **/

/**
* @version		$Id:baggage.php 1 2014-04-21 04:54:12Z  $
* @package		Bookpro
* @subpackage 	Views
* @copyright	Copyright (C) 2014, . All rights reserved.
* @license #
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

 
class BookproViewBaggage  extends BookproJViewLegacy {

	
	protected $form;
	
	protected $item;
	
	protected $state;
	
	
	/**
	 *  Displays the list view
 	 * @param string $tpl   
     */
	public function display($tpl = null) 
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		parent::display($tpl);	
	}	
}
?>