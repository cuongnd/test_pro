<?php
defined('_JEXEC') or die('Restricted access');
/**
 * 
 * @author sony
 *
 */
class session_website {
    const WEBSITE = 'website';
    var $id=0;
    var $name=null;
    var $website_id=0;
    var $sub_domain='';
    var $your_domain='';
    var $email='';

	function saveToSession() {
		$session =& JFactory::getSession();
		$session->set(static::WEBSITE, serialize($this));
	
	}
	function clear(){
		$session =& JFactory::getSession();
		$session->clear(static::WEBSITE);
	}
    function load($type_cart = "tourcart") {
        $session = & JFactory::getSession();
        $obj_website = $session->get(static::WEBSITE);

        if (isset($obj_website) && $obj_website != '') {
            $temp_cart = unserialize($obj_website);

            $this->id = $temp_cart->id;
            $this->name = $temp_cart->name;
            $this->website_id = $temp_cart->website_id;
            $this->sub_domain = $temp_cart->sub_domain;
            $this->your_domain = $temp_cart->your_domain;
            $this->email = $temp_cart->email;
        }
    }


}
