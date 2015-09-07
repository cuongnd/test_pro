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
class PaymentType {

	static $EGATE = null;
	static $PAYPAL = null;
	static $IDEAL = null;
	static $ONEPAY = null;
	static $PAYPALPRO = null;
	static $MELLAT=null;
	static $COMMBANK=null;
	static $GTPAY=null;
	static $OFFLINE=null;
	static $NGANLUONG=null;

	public $value = null;

	public static $map;
	private $key=null;
	private $text=null;

	public function __construct($value) {
		$this->value = $value;
		$this->text=$value;
	}

	public static function init () {
		self::$EGATE  = new PaymentType("EGATE");
		self::$PAYPAL  = new PaymentType("PAYPAL");
		self::$IDEAL = new PaymentType("IDEAL");
		self::$ONEPAY = new PaymentType("ONEPAY");
		self::$PAYPALPRO = new PaymentType("PAYPALPRO");
		self::$OFFLINE = new PaymentType("OFFLINE");
		self::$MELLAT = new PaymentType("MELLAT");
		self::$COMMBANK = new PaymentType("COMMBANK");
		self::$GTPAY = new PaymentType("GTPAY");
		self::$NGANLUONG = new PaymentType("NGANLUONG");
		self::$map = array (self::$EGATE,self::$PAYPAL,self::$IDEAL,self::$ONEPAY,self::$PAYPALPRO,self::$MELLAT,self::$COMMBANK,self::$GTPAY,self::$NGANLUONG,self::$OFFLINE);
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
	public function getText() {
		return $this->text;
	}
	public function __setKey($key){
		$this->key=$key;
	}
	
	public function equals(PaymentType $element) {
		return $element->getValue() == $this->getValue();
	}

	public function __toString () {
		return $this->value;
	}
}