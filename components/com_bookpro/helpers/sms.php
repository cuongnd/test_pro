<?php


defined('_JEXEC') or die('Restricted access');
AImporter::helper('date');
class SMSHelper
{
	var $gateway;
	var $username;
	var $password;

 public function __construct() {
 	
 	$this->gateway="http://123.30.17.109/smsws/services/SendMT?wsdl";
 	$this->username='quannv';
 	$this->password='111111';
 } 
 public function createSMSFromOrder($order_id){
 	
 	$model= new BookProModelOrder();
 	$model->setId($order_id);
 	$order= $model->getByOrderNumber($order['order_number']);
 
 	
 	$smsModel=new BookProModelSms();
 	$sms=array('to'=>$order->mobile);
 	//$content=
 
 }
  
   
}

?>