<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/9/2015
 * Time: 2:57 PM
 */
defined('_JEXEC') or die;

class BookproViewExcursion extends BookproJViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    /**
     * (non-PHPdoc)
     * @see JViewLegacy::display()
     */
    public function display($tpl = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->addToolbar();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('Edit Image'), 'excursion');
        JToolBarHelper::apply('excursion.apply');
        JToolBarHelper::save('excursion.save');
        JToolBarHelper::cancel('excursion.cancel');
    }

}