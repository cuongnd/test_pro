<?php

defined ( '_JEXEC' ) or die ();
class TourViewManager extends JViewLegacy {

    public function display($tpl=null){
        $this->addToolbar();
        parent::display($tpl);
    }


    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('Manager'), '');



    }

}