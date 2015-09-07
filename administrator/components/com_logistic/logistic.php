<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/9/2015
 * Time: 11:35 AM
 */
defined('_JEXEC') or die;
$controller = JControllerLegacy::getInstance('logistic');//đang gọi đến class  trong  controller.php bên trên
$controller->execute(JFactory::getApplication()->input->get('task'));  //hellocontroller trong controller.php
$controller->redirect();