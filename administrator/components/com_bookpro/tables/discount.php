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

class TableDiscount extends JTable
{

	var $id;
	var $amount;
	var $type;
	var $app_id;
	var $start;
	var $end;
	

	function __construct(& $db)
	{
		parent::__construct('#__' . PREFIX . '_discount', 'id', $db);
	}

	/**
	 * Init empty object.
	 */
	function init()
	{
		$this->id = 0;
		$this->amount = '';
		$this->type = '';
		$this->start = '';
		$this->end = '';
		$this->app_id='';
	}
	
}