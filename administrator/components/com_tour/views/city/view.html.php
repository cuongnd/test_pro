<?php
defined ( '_JEXEC' ) or die ();
class TourViewCity extends JViewLegacy {
    protected $item;
    protected $form;
    public function display($tpl = null) {

        $this->item = $this->get ( 'Item' );

        $this->form = $this->get ( 'Form' );
        if (count ( $errors = $this->get ( 'Errors' ) )) {
            JError::raiseError ( 500, implode ( "\n", $errors ) );
            return false;
        }

        $this->addToolbar ();
        parent::display ( $tpl );
    }
    protected function addToolbar() {
        JFactory::getApplication ()->input->set ( 'hidemainmenu', true );
        JToolBarHelper::title(JText::_('Add city'), '');
        JToolBarHelper::apply('city.apply');
        JToolbarHelper::save ( 'city.save' );
        if (empty ( $this->item->id )) {
            JToolbarHelper::cancel ( 'city.cancel' );
        } else {
            JToolbarHelper::cancel ( 'city.cancel', 'JTOOLBAR_CLOSE' );
        }
    }
}