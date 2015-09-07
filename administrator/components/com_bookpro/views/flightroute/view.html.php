<?php
class BookproViewflightroute extends BookproJViewLegacy{
	function display(){
		 $this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->addToolbar();
		parent::display($tpl);
        
	}
	
	protected function addToolbar() {
		JFactory::getApplication ()->input->set ( 'hidemainmenu', true );
		JToolbarHelper::title ( JText::_ ( 'COM_FOLIO_MANAGER_FLIGHTROUTER' ), '' );
		JToolbarHelper::save ( 'flightroute.save' );
		JToolbarHelper::apply ( 'flightroute.apply' );
		if (empty ( $this->item->id )) {
			JToolbarHelper::cancel ( 'flightroute.cancel' );
		} else {
			JToolbarHelper::cancel ( 'flightroute.cancel', 'JTOOLBAR_CLOSE' );
		}
	}
}
