<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customer.php 108 2012-09-04 04:53:31Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables
AImporter::model('passenger','orders');

class BookProModelCustomer extends AModelFrontEnd
{

	/**
	 * Main table.
	 *
	 * @var TableCustomer
	 */
	var $_table;
	/**
	 * Map user ids to customer ids.
	 *
	 * @var array
	 */
	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableCustomer')) {
			AImporter::table('customer');
		}
		$this->_table = $this->getTable('customer');
	}

	function getObject()
	{
		$query = 'SELECT `customer`.*,`country`.`country_name` AS `country_name`,point.point AS point,`country`.`flag`, GROUP_CONCAT(`group`.`title`) AS `usertype` FROM `' . $this->_table->getTableName() . '` AS `customer` ';
		$query .= 'LEFT JOIN `#__user_usergroup_map` AS `map` ON `map`.`user_id` = `customer`.`user` ';
		$query .= 'LEFT JOIN `#__usergroups` AS `group` ON `group`.`id` = `map`.`group_id` ';
		$query .= 'LEFT OUTER JOIN `#__bookpro_country` AS `country` ON `country`.`id` = `customer`.`country_id` ';
		$query .= 'LEFT OUTER JOIN #__bookpro_point AS point ON point.customer_id = `customer`.`id` ';
		$query .= 'WHERE `customer`.`id` = ' . (int) $this->_id;
		$query .= ' GROUP BY `customer`.`user` ';
		$this->_db->setQuery($query);
		if (($object = &$this->_db->loadObject())) {
			$this->_table->bind($object);
			$this->_table->usertype = $object->usertype;
			$this->_table->country_name=$object->country_name;
			$this->_table->fullname = $object->firstname.' '.$object->lastname;
			return $this->_table;
		}

		return parent::getObject();
	}
	function getUserSystemByEmail($email='')
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('user.id');
		$query->from('#__users as user');
		$query->where('user.email='.$db->quote($email));
		$db->setQuery($query);
		$user_id=$db->loadResult();
		if($user_id)
		{
			return JFactory::getUser($user_id);
		}
		return false;
	}
	function getUserSystemByUsername($username='')
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('user.id');
		$query->from('#__users as user');
		$query->where('user.email='.$db->quote($email));
		$db->setQuery($query);
		$user_id=$db->loadResult();
		if($user_id)
		{
			return JFactory::getUser($user_id);
		}
		return false;
	}
	function createUserSystem($data=array())
	{
		$user=clone(JFactory::getUser());
		$user->bind($data);
		if($user->save()){
			return $user;
		}
		return false;


	}
	function getCustomerByUserIdSystem($user_id=0)
	{
		$user = JFactory::getUser($user_id);
		$query = 'SELECT `customer`.* FROM `' . $this->_table->getTableName() . '` AS `customer` ';
		$query .= 'WHERE `customer`.`user` = ' . $user->id;
		$this->_db->setQuery($query);
		$object = &$this->_db->loadObject();
		if($object)
			return $object;
		return false;
	}
	function getCustomerByEmail($email='')
	{

		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('customer.id');
		$query->from('#__bookpro_customer AS customer');
		$query->where('customer.email='.$db->quote($email));
		$db->setQuery($query);
		$customer_id=$db->loadResult();
		if($customer_id)
			$customer=$this->getCustomerByID($customer_id);
		return false;

	}
	function createCustomerByUserIdSystem($user_id=0,$extend_data=array())
	{
		$user = &JFactory::getUser($user_id);
		$customer=$this->getCustomerByEmail($user->email);
		$db=JFactory::getDbo();

		if($customer)
		{
			if($customer->user!=$user->id)
			{

				$query=$db->getQuery();
				$query->update('#__bookpro_customer')->set('user='.$user->id)->where('id='.$customer->id);
				$db->setQuery($query);
				if(!$db->query())
					return false;
			}
			return $customer;
		}
		if(!$extend_data)
		{
			$extend_data['firstname']=$user->name;
			$extend_data['lastname']=$user->name;
		}

		$query=$db->getQuery(true);

		$query->insert('#__bookpro_customer')->columns('firstname,user,lastname,state,email')
		->values(array("
				{$db->quote($extend_data['firstname'])},
				{$user->id},
				{$db->quote($extend_data['lastname'])},
				1,
				{$db->quote($user->email)}")
		);
		$db->setQuery($query);
		//echo $db->replacePrefix($query);
		if($db->execute())
		{

			$customer_insert_id=$db->insertid();
			echo $customer_insert_id;
			$customer=$this->getCustomerByID($customer_insert_id);
			return $customer;
		}
		return false;
	}

	function autoLoginByUserIdSystem($user_id=0)
	{
		$user=JFactory::getUser($user_id);
		$session =& JFactory::getSession();
		$session->set('user', $user);

	}

	public static function autoCreateUserSystem($data)
	{
		$user=clone (JFactory::getUser());
		$user->bind($data);
		$user->groups=array($config->get('customers_usergroup'));
		$user->block=0;

		if($user->save()){
			return $user;
			//create new customer


		}

	}

	function getrewards($customer_id=0)
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('point.*');
		$query->from('#__bookpro_point AS point');
		$query->where('point.customer_id='.$customer_id);
		$db->setQuery($query);
		$point=$db->loadObject();
		return $point;
	}
	/**
	 * Set customer ID by lsogged user ID.
	 */
	function setIdByUserId()
	{
		$user = &JFactory::getUser();
		/* @var $user JUser */
		if ($user->id) {

			$query = 'SELECT `customer`.`id`, `cgroup`.`title` AS `group_title` ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `customer` ';
			$query .= 'LEFT JOIN `#__bookpro_cgroup` AS `cgroup` ON `customer`.`cgroup_id` = `cgroup`.`id` ';
			$query .= 'LEFT JOIN `#__users` AS `user` ON `customer`.`user` = `user`.`id` ';
			// is active customer
			$query .= 'WHERE `customer`.`user` = ' . $user->id;
			// juser is active
			//$query .= ' AND `user`.`block` = 0';
			$this->_db->setQuery($query);
			$customer_id = (int) $this->_db->loadResult();
		}
		$this->setId($customer_id);
	}
	function getCustomerByID($id){
		$query = 'SELECT `customer`.*, c.flag AS flag,c.country_name AS country_name, c.country_code AS country_code FROM `' . $this->_table->getTableName() . '` AS `customer` ';
		$query .= 'LEFT JOIN `#__bookpro_country` AS c ON customer.country_id = c.id ';
		$query .= 'WHERE `customer`.`id` = ' . $id;

		$this->_db->setQuery($query);
		return $this->_db->loadObject();
	}
	function getObjectByUserId($user_id = null)
	{
		$user = &JFactory::getUser($user_id);

		$query = 'SELECT `customer`.* FROM `' . $this->_table->getTableName() . '` AS `customer` ';
		$query .= 'WHERE `customer`.`user` = ' . $user->id;
		$this->_db->setQuery($query);
		$object = &$this->_db->loadObject();
		return $object;
	}


	function getCustomerNameByID($id){
		$customer=$this->_db->load($id);
		return  $customer->firstname;
	}


	/**
	 * Save customer.
	 *
	 * @param array $data request data
	 * @return customer id if success, false in unsuccess
	 */

	function store($data)
	{
		$config = &AFactory::getConfig();
		$jconfig = JFactory::getConfig();
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
			// on frontend to become logged user as customer
			$this->_table->user = $user->id;

		unset($data['id']);
		//if($this->_table->user){
		$cuser = new JUser($this->_table->user);
		/* @var $cuser JUser customer user to update */
		$cuser->bind($data);
		$cuser->name =($this->_table->firstname .' ' .$this->_table->lastname);
		if (! $cuser->id) {
			// customer hasn't user - create
            if($data['group_id']){
               	$cuser->groups = array($data['group_id']);
            }else{

                $cuser->groups = array($config->customersUsergroup);
            }
            $cuser->activation=$data['activation'];
			$cuser->block = $data['block']?$data['block']:0;
			$cuser->sendEmail = CUSTOMER_SENDEMAIL;
			$cuser->registerDate = null;
		}else{
			if($data['group_id']){
				$cuser->groups = array($data['group_id']);
			}else{
				$cuser->groups = array($config->customersUsergroup);
			}
		}
		if (! $cuser->save()) {
			$this->_errors = $cuser->getErrors();
			return false;
		}
		unset($data['activation']);
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
	 * Trashed selected customers.
	 *
	 * @param $cids customers IDs
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
			$oModel = new BookProModelOrders();
			if(!$oModel->deleteByCustomerID($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

		}
		return true;

	}

	/**
	 * Restore selected customers.
	 *
	 * @param $cids customers IDs
	 * @return boolean success sign
	 */
	function restore($cids)
	{
		return $this->state('state', $cids, CUSTOMER_STATE_ACTIVE, CUSTOMER_STATE_DELETED);
	}

	/**
	 * Remove trashed customers and users accounts.
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
	function getFormFieldUser()
	{
		JForm::addFormPath(JPATH_COMPONENT_BACK_END.'/models/forms'); // set destination directory of xml maniest
		$form = JForm::getInstance('com_bookpro.customer', 'customer', array('control' => '', 'load_data' => true)); // load xml manifest
		/* @var $form JForm */
		return $form->getInput('user');
	}
	function getFormFieldUserGroup()
	{
		JForm::addFormPath(JPATH_COMPONENT_BACK_END.'/models/forms'); // set destination directory of xml maniest
		$form = JForm::getInstance('com_bookpro.customer', 'customer', array('control' => '', 'load_data' => true)); // load xml manifest
		/* @var $form JForm */
		return $form->getInput('usertype');
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

?>