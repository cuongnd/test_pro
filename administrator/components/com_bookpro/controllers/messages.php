<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

class BookproControllerMessages extends JControllerAdmin {

    public function getModel($name = 'message', $prefix = 'BookproModel', $config = array('ignore_request' => true)) {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function delete() {
        // Check for request forgeries
        JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

        // Get items to remove from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');

        $db = JFactory::getDbo();
        foreach ($cid as $id) {
            $query = $db->getQuery(true);
            $query->delete('#__bookpro_messages');
            $query->where('parent_id=' . $id);
            $db->setQuery($query);
            $db->execute();
        }


        if (!is_array($cid) || count($cid) < 1) {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        } else {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            jimport('joomla.utilities.arrayhelper');
            JArrayHelper::toInteger($cid);

            // Remove the items.
            if ($model->delete($cid)) {
                $this->setMessage(JText::plural($this->text_prefix . '_N_ITEMS_DELETED', count($cid)));
            } else {
                $this->setMessage($model->getError());
            }
        }
        // Invoke the postDelete method to allow for the child class to access the model.
        $this->postDeleteHook($model, $cid);

        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
    }

}