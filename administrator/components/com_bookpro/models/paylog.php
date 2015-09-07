<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class BookProModelPayLog extends JModelAdmin {

    /**
     * (non-PHPdoc)
     * @see JModelForm::getForm()
     */
    
  

    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm('com_bookpro.paylog', 'paylog', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
            return false;
        return $form;
    }

    /**
     * (non-PHPdoc)
     * @see JModelForm::loadFormData()
     */
    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_bookpro.edit.paylog.data', array());
        if (empty($data))
            $data = $this->getItem();
        return $data;
    }

    public function publish(&$pks, $value = 1) {
        $user = JFactory::getUser();
        $table = $this->getTable();
        $pks = (array) $pks;

        // Attempt to change the state of the records.
        if (!$table->publish($pks, $value, $user->get('id'))) {
            $this->setError($table->getError());

            return false;
        }

        return true;
    }

    function unpublish($cids) {
        return $this->state('state', $cids, 0, 1);
    }


}