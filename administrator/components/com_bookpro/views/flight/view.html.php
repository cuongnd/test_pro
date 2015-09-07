<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');


//import needed models
AImporter::model('airport','airlines','airports');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request');

//import needed assets
AHtml::importIcons();

class BookProViewFlight extends BookproJViewLegacy
{

   	
    function display($tpl = null)
    {
        /* @var $mainframe JApplication */
        $this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
        
               
 }

    /**
     * Prepare to display page.
     * 
     * @param string $tpl name of used template
     * @param TableCustomer $customer
     * @param JUser $user
     */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_('Flight'), 'flight');
		JToolBarHelper::apply('flight.apply');
		JToolBarHelper::save('flight.save');
		JToolBarHelper::cancel('flight.cancel');
	}
}

?>