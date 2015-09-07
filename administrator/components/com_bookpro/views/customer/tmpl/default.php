<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 47 2012-07-13 09:43:14Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');


$bar = &JToolBar::getInstance('toolbar');
JToolBarHelper::custom('Edit', 'edit', 'edit', 'Edit', false);

JToolBarHelper::cancel();
echo $this->loadTemplate('customer');
?>

