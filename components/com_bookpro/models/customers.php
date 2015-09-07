<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customers.php 56 2012-07-21 07:53:28Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelCustomers extends AModelFrontEnd
{
	/**
	 * Main table
	 *
	 * @var TableCustomer
	 */
	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('customer');
	}

	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	function buildQuery()
	{
		$query=null;
		$config=AFactory::getConfig();
		if (is_null($query)) {


			if($this->_lists['group_id']==$config->customersUsergroup){

				$query =  'SELECT * FROM (SELECT `customer`.*, '.$config->customersUsergroup.' AS group_id,  CONCAT(`customer`.`firstname`," ",`customer`.`lastname` ) as `fullname` ';
				$query .= 'FROM `' . $this->_table->getTableName() . '` AS `customer` WHERE `customer`.`user` IS NULL  ';
				$query .= 'UNION ALL ';

				$query .= 'SELECT `customer`.*, group_id, CONCAT(`customer`.`firstname`," ",`customer`.`lastname` ) as `fullname` ';
				$query .= 'FROM `' . $this->_table->getTableName() . '` AS `customer` ';
				$query .= 'INNER JOIN `#__users` AS `user` ON `user`.`id` = `customer`.`user` ';
				$query .= 'INNER JOIN #__user_usergroup_map AS map ON map.user_id = customer.user WHERE group_id='.$config->customersUsergroup.') AS `customer` ';

			}else{

				$query = 'SELECT `customer`.*, `user`.`username`, `customer`.`email`, `user`.`block`, CONCAT(`customer`.`firstname`," ",`customer`.`lastname` ) as `fullname` ';
				$query .= 'FROM `' . $this->_table->getTableName() . '` AS `customer` ';
					$query .= 'INNER JOIN `#__users` AS `user` ON `user`.`id` = `customer`.`user` ';
					$query .=' INNER JOIN #__user_usergroup_map AS map ON map.user_id = customer.user ';
			}

			$query .= $this->buildContentWhere();
			//$query .= ' GROUP BY `customer`.`user` ';
			$query .= $this->buildContentOrderBy();

		}
		return $query;
	}



	/**
	 * Get MySQL filter criteria for customers list
	 *
	 * @return string filter criteria in MySQL format
	 */
	function buildContentWhere()
	{
		$where = array();
		$config=AFactory::getConfig();
		$this->addIntProperty($where, 'state');
		if($this->_lists['group_id']!=$config->customersUsergroup){
			$this->addIntProperty($where, 'group_id');
		}
		$this->addIntProperty($where, 'country_id');
		$this->addStringProperty($where, 'firstname');
		return $this->getWhere($where);
	}
}

?>