<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');

class TableCoupon extends JTable
{

	var $id;
	var $coupon_id;
	var $redeem_date;
	var $order_id;

	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_coupon_log', 'id', $db);
	}

	/**
	 * Init empty object.
	 */
	function init()
	{
		$this->id = 0;
	
	}
	
}