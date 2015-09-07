<?php
/**
 * Payment status.
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id$
 */
class OrderStatus {

	static $FINISHED = null;
	static $CONFIRMED = null;
	static $PENDING = null;
	static $CANCELLED = null;
	static $NEW = null;

	public $value = null;

	public static $map;
	private $key=null;
	public $text=null;

	public function __construct($value) {
		$this->value = $value;
		$this->text= JText::_('COM_BOOKPRO_ORDER_STATUS_'.strtoupper($this->value));
	}

	public static function init () {
		self::$FINISHED  = new OrderStatus("FINISHED");
		self::$PENDING  = new OrderStatus("PENDING");
		self::$CANCELLED = new OrderStatus("CANCELLED");
		self::$NEW = new OrderStatus("NEW");
		self::$CONFIRMED = new OrderStatus("CONFIRMED");
		//static map to get object by name - example Enum::get("INIT") - returns Enum::$INIT object;
		self::$map = array (self::$PENDING,self::$CONFIRMED,self::$FINISHED,self::$CANCELLED);
	}
	static function format($status){
		return JText::_('COM_BOOKPRO_ORDER_STATUS_'.strtoupper($status));
	}
	public function getText() {
		return JText::_('COM_BOOKPRO_ORDER_STATUS_'.strtoupper($this->value));
	}

	public static function get($element) {
		if($element == null)
			return null;
		return self::$map[$element];
	}

	public function getValue() {
		return $this->value;
	}
	
	public function __getKey() {
		return $this->value;
	}

	public function __setKey($key){
		$this->key=$key;
	}
	
	public function equals(OrderStatus $element) {
		return $element->getValue() == $this->getValue();
	}

	public function __toString () {
		return $this->value;
	}
}