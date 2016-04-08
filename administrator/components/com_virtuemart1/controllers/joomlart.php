<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 5/31/14
 * Time: 5:59 AM
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

if(!class_exists('VmController'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmcontroller.php');
class VirtuemartControllerJoomlart extends VmController{
    public function getProducts()
    {
        if(!class_exists('joomlart')) require(JPATH_VM_ADMINISTRATOR.'/helpers/joomlart.php');
        joomlart::getProducts();
        echo "test here";
        jexit();
    }
} 