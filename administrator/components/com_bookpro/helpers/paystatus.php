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
class PayStatus {

	static $PENDING = null;
	static $SUCCESS = null;
	static $DEPOSIT = null;

	public $value = null;

	public static $map;
	private $key=null;
	public $text=null;

	public function __construct($value) {
		$this->value = $value;
		$this->text= JText::_('COM_BOOKPRO_PAYMENT_STATUS_'.strtoupper($this->value));
	}
	static function format($status){
		return JText::_('COM_BOOKPRO_PAYMENT_STATUS_'.strtoupper($status));
	}
	public function getText() {
		return JText::_('COM_BOOKPRO_PAYMENT_STATUS_'.strtoupper($this->value));
	}

	public static function init () {
		self::$PENDING  = new PayStatus("PENDING");
		self::$SUCCESS = new PayStatus("SUCCESS");
		self::$DEPOSIT = new PayStatus("DEPOSIT");
		self::$map = array (self::$PENDING,self::$SUCCESS,self::$DEPOSIT);
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
	public function __getText() {
		return $this->value;
	}
	public function __setKey($key){
		$this->key=$key;
	}
	
	public function equals(PayStatus $element) {
		return $element->getValue() == $this->getValue();
	}

	public function __toString () {
		return $this->value;
	}
}