<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: controller.php 66 2012-07-31 23:46:01Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.controller');
//import needed JoomLIB helpers
AImporter::helper('controller');

class BookProController extends AController
{

    function display()
    {
        $helpController = JRequest::getString('help_controller');
        if ($helpController) {
            $classname = AImporter::controller($helpController);
            if (class_exists($classname)) {
                $controller = new $classname();
                $controller->_doRedirect = false;
                $controller->execute(JRequest::getVar('task'));
            }
        }
        parent::display();
    }

    function sampleData()
    {
       
    }
}

?>