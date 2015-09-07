<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/9/2015
 * Time: 2:57 PM
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class BookProModelTourPrice extends JModelAdmin {

    /**
     * (non-PHPdoc)
     * @see JModelForm::getForm()
     */
    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm('com_bookpro.tourprice', 'tourprice', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
            return false;
        return $form;
    }

    /**
     * (non-PHPdoc)
     * @see JModelForm::loadFormData()
     */
    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_bookpro.edit.tourprice.data', array());
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

    public function featured($pks, $value = 0) {
        // Sanitize the ids.
        $pks = (array) $pks;
        JArrayHelper::toInteger($pks);
        if (empty($pks)) {
            $this->setError(JText::_('COM_CONTENT_NO_ITEM_SELECTED'));
            return false;
        }
        try {
            $db = $this->getDbo();
            $query = $db->getQuery(true)
                    ->update($db->quoteName('#__bookpro_tourprice'))
                    ->set('featured = ' . (int) $value)
                    ->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            $db->execute();
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            return false;
        }
        $this->cleanCache();
        return true;
    }

}