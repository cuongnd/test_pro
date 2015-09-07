<?php
defined ( '_JEXEC' ) or die ();
class TourViewBrochure extends JViewLegacy {
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
        JToolBarHelper::title(JText::_('Add Brochure'), '');
        JToolBarHelper::apply('brochure.apply');
        JToolbarHelper::save ( 'brochure.save' );
        if (empty ( $this->item->id )) {
            JToolbarHelper::cancel ( 'brochure.cancel' );
        } else {
            JToolbarHelper::cancel ( 'brochure.cancel', 'JTOOLBAR_CLOSE' );
        }
    }
}