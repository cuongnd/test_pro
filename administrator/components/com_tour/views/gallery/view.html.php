<?php
defined ( '_JEXEC' ) or die ();
class TourViewGallery extends JViewLegacy {
    protected $item;
    protected $form;
    protected $state;
    public function display($tpl = null) {

        $this->item = $this->get ( 'Item' );
        $this->form = $this->get ( 'Form' );
        $this->state 	= $this->get('State');
        $this->city_id	= JFactory::$application->input->get('city_id');
        if (count ( $errors = $this->get ( 'Errors' ) )) {
            JError::raiseError ( 500, implode ( "\n", $errors ) );
            return false;
        }

		if(!$this->city_id){
            $this->city_id	= JFactory::getApplication()->getUserState('com_tour.galleries.filter.city_id');
		}else{
			JFactory::getApplication()->setUserState('com_tour.galleries.filter.city_id',$this->city_id);
		}

        $this->addToolbar ();
        parent::display ( $tpl );
    }
    protected function addToolbar() {
        JFactory::getApplication ()->input->set ( 'hidemainmenu', true );
        JToolBarHelper::title(JText::_('Add gallery'), '');
        JToolBarHelper::apply('gallery.apply');
        JToolbarHelper::save ( 'gallery.save' );
        if (empty ( $this->item->id )) {
            JToolbarHelper::cancel ( 'gallery.cancel' );
        } else {
            JToolbarHelper::cancel ( 'gallery.cancel', 'JTOOLBAR_CLOSE' );
        }
    }
}