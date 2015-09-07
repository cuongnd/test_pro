<?php


defined('_JEXEC') or die('Restricted access');

class TablePayment extends JTable
{
  
    var $id;
    var $title;
   
    var $email;
    var $secondemail;
    var $ipn_url;
    var $secure_code;
    var $merchant_id;
    var $access_code;
    var $username;
    var $password;
    var $signature;
    var $gateway_url;
    var $success_url;
    var $cancel_url;
    var $description;
    var $state;
    var $code;
    var $istest;
    var $hostedtype;
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__bookpro_payment', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->id = 0;
        $this->title = '';
        $this->username='';
        $this->password='';
        $this->signature='';
        $this->secure_code='';
        $this->merchant_id='';
        $this->access_code='';
        $this->email='';
        $this->secondemail='';
        $this->ipn_url='';
        $this->gateway_url='';
        $this->cancel_url='';
        $this->success_url='';
        $this->description = '';
        $this->state = 1;
        $this->code='';
        $this->istest=0;
              
    }
}

?>