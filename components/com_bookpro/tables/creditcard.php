<?php
/*
 * Created on 19.7.2011
 */

defined('_JEXEC') or die('Restricted access');

class TableCreditCard extends JTable {
	var $id=null;
	var $reservation_id=null;
	var $card_type=null;
	var $username=null;
	var $card_number=null;
	var $sec_code=null;
	var $exp_month=null;
	var $exp_year=null;
	var $pay_type=null;

	function __construct($db) {
		parent::__construct('#__bookpro_creditcard','id',$db);
	}
}
?>
