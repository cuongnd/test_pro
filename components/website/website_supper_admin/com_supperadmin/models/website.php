<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * website model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminModelWebsite extends JModelAdmin
{
	/**
	 * @var     string  The help screen key for the module.
	 * @since   1.6
	 */

	/**
	 * @var     string  The help screen base URL for the module.
	 * @since   1.6
	 */
	protected $helpURL;

	/**
	 * @var     array  An array of cached website items.
	 * @since   1.6
	 */
	protected $_cache;

	/**
	 * @var     string  The event to trigger after saving the data.
	 * @since   1.6
	 */
	protected $event_after_save = 'onExtensionAfterSave';

	/**
	 * @var     string  The event to trigger after before the data.
	 * @since   1.6
	 */
	protected $event_before_save = 'onExtensionBeforeSave';

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * 
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
		}


		// Get the form.
		$form = $this->loadForm('com_supperadmin.website', 'website', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('enabled', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('enabled', 'filter', 'unset');
		}

		return $form;
	}
    public function save($data)
    {
        $ok= parent::save($data); // TODO: Change the autogenerated stub
        if($ok)
        {
            $list_domain=$data['list_domain'];
            $list_domain[]=0;
            JArrayHelper::toInteger($list_domain);
            $id=$data['id'];
            $db=$this->_db;
            $query=$db->getQuery(true);
            $query->update('#__domain_website')
                ->where('website_id='.(int)$id)
                ->set('website_id=NULL')
                ;
            $db->setQuery($query);
            if(!$db->execute())
            {
                $this->setError($db->getErrorMsg());
                return false;
            }
            $table_domain_website=JTable::getInstance('domainwebsite');
            foreach($list_domain as $website_domain_id)
            {
                $table_domain_website->load($website_domain_id);
                $table_domain_website->website_id=$id;
                if(!$table_domain_website->store())
                {
                    $this->setError($table_domain_website->getError());
                    return false;
                }




            }
            $list_domain=websiteHelperFrontEnd::get_list_domain_by_website($id);
            jimport('joomla.filesystem.file');
            foreach($list_domain as $domain){
                $domain_path="webstore/$domain->domain.ini";
                $write_ok=JFile::write(JPATH_ROOT.DS.$domain_path,$id);
                if(!$write_ok)
                {
                    $this->setError('cannot write file domain');
                }
            }

        }
        return $ok;
    }

    public function getItem($pk = null)
    {
        $item= parent::getItem($pk); // TODO: Change the autogenerated stub
        $item->list_domain=websiteHelperFrontEnd::get_list_domain_website_id_by_website($item->id);
        return $item;
    }


	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_supperadmin.edit.website.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_supperadmin.website', $data);

		return $data;
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function populateState()
	{
    	// Execute the parent method.
		parent::populateState();

		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');

		$this->setState('website.id', $pk);
	}




}
