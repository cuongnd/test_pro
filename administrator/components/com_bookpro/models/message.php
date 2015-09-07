<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class BookProModelMessage extends JModelAdmin {

    /**
     * (non-PHPdoc)
     * @see JModelForm::getForm()
     */
    public function getForm($data = array(), $loadData = true) {

        $form = $this->loadForm('com_bookpro.message', 'message', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
            return false;
        return $form;
    }

    /**
     * (non-PHPdoc)
     * @see JModelForm::loadFormData()
     */
    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_bookpro.edit.message.data', array());
        if (empty($data))
            $data = $this->getItem();
        return $data;
    }
    /*
     * Load message informantion
    */
    public function loadMessage($id) {
    	$db = JFactory::getDbo ();
    	$query = $db->getQuery ( true );
    	$query->select ( 't.*,u.name,u.email' )->from ( '#__bookpro_messages AS t' )->
    	leftJoin('#__users AS u ON u.id=t.cid_from')->
    	where('t.id='.$id);
    	$db->setQuery ( $query );
    	$message = $db->loadObject();
    	return $message;
    }
    public function loadEmailcid_to($id){
    	$db = JFactory::getDbo ();
    	$query = $db->getQuery ( true );
    	$query->select ( 'u.name,u.email' )->from ( '#__bookpro_messages AS t' )->
    	leftJoin('#__users AS u ON u.id=t.cid_to')->
    	where('t.id='.$id);
    	$db->setQuery ( $query );
    	$message = $db->loadObject();
    	return $message;
    }
    /*
     * Load messages reply 
    */
    public function loadMessagemsg($id){
    	$db = JFactory::getDbo ();
    	$query = $db->getQuery ( true );
    	$query->select ( 'message' )->from ( '#__bookpro_messages' )->
    	where('id='.$id);
    	$db->setQuery ( $query );
    	$message = $db->loadObject();
    	return $message;
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

    /*
     * override message save method
     * 1. New message
     * 2. Reply message
     * 
     * ->Send email
     */
    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   12.2
     */
    
    public function save($data)
    {
    	$dispatcher = JEventDispatcher::getInstance();
    	$table = $this->getTable();
    
    	if ((!empty($data['tags']) && $data['tags'][0] != ''))
    	{
    		$table->newTags = $data['tags'];
    	}
       
    	$key = $table->getKeyName();
    	$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
    	$isNew = true;

     
    	// Include the content plugins for the on save events.
    	JPluginHelper::importPlugin('content');
    
    	// Allow an exception to be thrown.
    	try
    	{
    		// Load the row if saving an existing record.
    		if ($pk > 0)
    		{
    			$table->load($pk);
    			$isNew = false;
    		}
    
    		// Bind the data.
    		if (!$table->bind($data))
    		{
    			$this->setError($table->getError());
    			return false;
    		}
    	
    		// Prepare the row for saving
    		$this->prepareTable($table);
    
    		// Check the data.
    		if (!$table->check())
    		{
    			$this->setError($table->getError());
    			return false;
    		}
    
    		// Trigger the onContentBeforeSave event.
    		$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, $table, $isNew));
    
    		if (in_array(false, $result, true))
    		{
    			$this->setError($table->getError());
    			return false;
    		}
    
    		// Store the data.
    		if (!$table->store())
    		{
    			$this->setError($table->getError());
    			return false;
    		}

            // Clean the cache.
    		$this->cleanCache();
    
    		// Trigger the onContentAfterSave event.
    		$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, $table, $isNew));
    	}
    	catch (Exception $e)
    	{
    		$this->setError($e->getMessage());
    
    		return false;
    	}
    
    	$pkName = $table->getKeyName();
    
    	if (isset($table->$pkName))
    	{
    		$this->setState($this->getName() . '.id', $table->$pkName);
    	}
    	$this->setState($this->getName() . '.new', $isNew);
    	/*
    	if($table->parent_id){ 
    		//New reply message
    		//To send reply message
    		echo "ok";
    	}
    	else  echo "!ok";
    	die();
    	*/
    	return $table->id;
    }
    
    
}