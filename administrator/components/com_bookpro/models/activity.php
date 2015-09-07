<?php


    defined('_JEXEC') or die;

    jimport('joomla.application.component.modeladmin');

    class BookProModelActivity extends JModelAdmin
    {
        /**
        * (non-PHPdoc)
        * @see JModelForm::getForm()
        */
        public function getForm($data = array(), $loadData = true)
        {

            $form = $this->loadForm('com_bookpro.activity', 'activity', array('control' => 'jform', 'load_data' => $loadData));
            
            if (empty($form))
                return false;
            return $form;
        }

        /**
        * (non-PHPdoc)
        * @see JModelForm::loadFormData()
        */
        protected function loadFormData()
        {
            $data = JFactory::getApplication()->getUserState('com_bookpro.edit.activity.data', array());
            if (empty($data))
                $data = $this->getItem();
            return $data;
        }
        public function getItem($pk = null)
        {
            if ($item = parent::getItem($pk))
            {
                // Convert the metadata field to an array.
                $registry = new JRegistry;
                $registry->loadString($item->metadata);
                $item->metadata = $registry->toArray();

                // Convert the images field to an array.
                $registry = new JRegistry;
                $registry->loadString($item->images);
                $item->images = $registry->toArray();

                if (!empty($item->id))
                {
                    $item->tags = new JHelperTags;
                    $item->tags->getTagIds($item->id, 'com_bookpro.activity');
                    $item->metadata['tags'] = $item->tags;
                }
            }

            return $item;
        }

        public function publish(&$pks, $value = 1)
        {
            $user = JFactory::getUser();
            $table = $this->getTable();
            $pks = (array) $pks;

            // Attempt to change the state of the records.
            if (!$table->publish($pks, $value, $user->get('id')))
            {
                $this->setError($table->getError());

                return false;
            }

            return true;
        }
        function unpublish($cids){
            return $this->state('state', $cids, 0, 1);
        }


}