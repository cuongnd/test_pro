<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 62 2012-07-29 01:18:34Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

AImporter::helper( 'request');
$order_id = ARequest::getCid();
$app =& JFactory::getApplication();
$app->redirect('index.php?option=com_bookpro&view=customtrip&cid[]='.$order_id);
?>
