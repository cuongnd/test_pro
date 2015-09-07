<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: agent.php 108 2012-09-04 04:53:31Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables


class BookProModelAgent extends AModelFrontEnd
{

	/**
	 * Main table.
	 *
	 * @var TableAgent
	 */
	var $_table;
	/**
	 * Map user ids to agent ids.
	 *
	 * @var array
	 */
	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableAgent')) {
			AImporter::table('agent');
		}
		$this->_table = $this->getTable('agent');
	}

	function getObject()
	{
		$query = 'SELECT `agent`.*, GROUP_CONCAT(`group`.`title`) AS `usertype` FROM `' . $this->_table->getTableName() . '` AS `agent` ';
		$query .= 'LEFT JOIN `#__user_usergroup_map` AS `map` ON `map`.`user_id` = `agent`.`user` ';
		$query .= 'LEFT JOIN `#__usergroups` AS `group` ON `group`.`id` = `map`.`group_id` ';
		$query .= 'WHERE `agent`.`id` = ' . (int) $this->_id;
		$query .= ' GROUP BY `agent`.`user` ';
		$this->_db->setQuery($query);
		if (($object = &$this->_db->loadObject())) {
			$this->_table->bind($object);
			$this->_table->usertype = $object->usertype;
			return $this->_table;
		}

		return parent::getObject();
	}

	/**
	 * Set agent ID by logged user ID.
	 */
	function setIdByUserId()
	{
		$user = &JFactory::getUser();
		/* @var $user JUser */
		if ($user->id) {
				
			$query = 'SELECT `agent`.`id` ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `agent` ';
			$query .= 'LEFT JOIN `#__users` AS `user` ON `agent`.`user` = `user`.`id` ';
			// is active agent
			$query .= 'WHERE `agent`.`user` = ' . $user->id;
			// juser is active
			//$query .= ' AND `user`.`block` = 0';
			$this->_db->setQuery($query);
			$agent_id = (int) $this->_db->loadResult();
		}
		$this->setId($agent_id);
	}
	function getAgentByID($id){
		$query = 'SELECT `agent`.*, c.country_name AS country FROM `' . $this->_table->getTableName() . '` AS `agent` ';
		$query .= 'LEFT JOIN `#__bookpro_country` AS c ON agent.country_id = c.id ';
		$query .= 'WHERE `agent`.`id` = ' . $id;
		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}
	function getObjectByUserId()
	{
		$user = &JFactory::getUser();

		$query = 'SELECT `agent`.* FROM `' . $this->_table->getTableName() . '` AS `agent` ';
		$query .= 'WHERE `agent`.`user` = ' . $user->id;
		$this->_db->setQuery($query);
		if (($object = &$this->_db->loadObject())) {
			$this->_table->bind($object);
			return $this->_table;
		}

		return parent::getObject();
	}



	/**
	 * Save agent.
	 *
	 * @param array $data request data
	 * @return agent id if success, false in unsuccess
	 */

	function store($data)
	{
		$config = &AFactory::getConfig();
		$config->agentUsergroup;
		/* @var $config BookingConfig */
		$user = &JFactory::getUser();
		/* @var $user JUser logged user */

		$id = (int) $data['id'];
		$this->_table->init();
		$this->_table->load($id);

		if (! $this->_table->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$id && $user->id && IS_SITE)
			// on frontend to become logged user as agent
			$this->_table->user = $user->id;

		unset($data['id']);
		//if($this->_table->user){
		$cuser = new JUser($this->_table->user);
		/* @var $cuser JUser agent user to update */
		$cuser->bind($data);
		$cuser->name =($this->_table->company);

		if (! $cuser->id) {
			// agent hasn't user - create
			$cuser->groups = array($config->agentUsergroup);
			$cuser->block = CUSTOMER_USER_STATE_ENABLED;
			$cuser->sendEmail = CUSTOMER_SENDEMAIL;
			$cuser->registerDate = null;
		}
		if (! $cuser->save()) {
			$this->_errors = $cuser->getErrors();
			return false;
		}
		//}
		$this->_table->user = $cuser->id;

		if (! $this->_table->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (! $this->_table->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $this->_table->id;
	}

	/**
	 * Trashed selected agents.
	 *
	 * @param $cids agents IDs
	 * @return boolean success sign
	 */

	function trash($cids)
	{

		foreach ($cids as $id){

			if( !$this->_table->delete($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			
		}
		return true;
			
	}

	/**
	 * Restore selected agents.
	 *
	 * @param $cids agents IDs
	 * @return boolean success sign
	 */
	function restore($cids)
	{
		return $this->state('state', $cids, CUSTOMER_STATE_ACTIVE, CUSTOMER_STATE_DELETED);
	}

	/**
	 * Remove trashed agents and users accounts.
	 *
	 * @return true if successfull
	 */
	function emptyTrash()
	{
		$query = 'SELECT user FROM ' . $this->_table->getTableName() . ' WHERE state = ' . CUSTOMER_STATE_DELETED;
		$this->_db->setQuery($query);
		$users = $this->_db->loadResultArray();
		foreach ($users as $user) {
			$user = &JFactory::getUser($user);
			$user->delete();
		}
		$query = 'DELETE FROM ' . $this->_table->getTableName() . ' WHERE state = ' . CUSTOMER_STATE_DELETED;
		$this->_db->setQuery($query);
		return $this->_db->query();
	}
}

?>