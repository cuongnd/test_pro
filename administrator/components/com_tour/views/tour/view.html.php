<?php
defined ( '_JEXEC' ) or die ();
class TourViewTour extends JViewLegacy {
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
        JToolbarHelper::title ( JText::_ ( 'Add tour' ), '' );
        JToolBarHelper::apply('tour.apply');

        JToolbarHelper::save ( 'tour.save' );
        if (empty ( $this->item->id )) {
            JToolbarHelper::cancel ( 'tour.cancel' );
        } else {
            JToolbarHelper::cancel ( 'tour.cancel', 'JTOOLBAR_CLOSE' );
        }
    }
}