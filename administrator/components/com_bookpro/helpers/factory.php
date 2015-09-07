<?php

/**
* Bookpro check class
*
* @package Bookpro
* @author Nguyen Dinh Cuong
* @link http://ibookingonline.com
* @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
* @version $Id: factory.php 47 2012-07-13 09:43:14Z quannv $
*/

defined('_JEXEC') or die('Restricted access');

class AFactory
{

    /**
     * Create template helper object
     * 
     * @return ATemplateHelper
     */
    function getTemplateHelper()
    {
        static $instance;
        if (empty($instance)) {
            AImporter::helper('template');
            $instance = new ATemplateHelper();
        }
        return $instance;
    }

    /**
     * Create config helper object
     * 
     * @return BookingConfig
     */
    static function getConfig()
    {
        static $instance;
        if (empty($instance)) {
            AImporter::helper('config', 'parameter');
            $instance = new BookProConfig();
        }
        return $instance;
    }
    /**
     * Get Customer login
     * @return Ambigous <mixed, boolean, unknown>
     */
    static function getCustomer(){
    	static $instance;
    	if (empty($instance)) {
    		$user=JFactory::getUser();
    		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
    		$instance = JTable::getInstance('customer', 'table');
    		$instance->load(array('user'=>$user->id));
    		$instance->juser=$user;
    	}
    	return $instance;
    }
}

?>