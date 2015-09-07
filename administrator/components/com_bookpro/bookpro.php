<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 27 2012-07-08 17:15:11Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

define('JPATH_COMPONENT_BACK_END', __DIR__);
require_once JPATH_ROOT.'/administrator/components/com_bookpro/legacy/view.php';
date_default_timezone_set('Asia/Calcutta');
// import joomla controller library
jimport('joomla.application.component.controller');
 
include (JPATH_COMPONENT_BACK_END . DS . 'helpers' . DS . 'importer.php');
include (JPATH_COMPONENT_BACK_END . DS . 'helpers' . DS . 'html.php');
include (JPATH_COMPONENT_BACK_END . DS . 'helpers' . DS . 'model.php');

$language = JFactory::getLanguage();
/* @var $language JLanguage */

//$language->load('com_booking.common', JPATH_ADMINISTRATOR);

AImporter::defines();

AImporter::helper('bookpro', 'factory', 'html');
//AImporter::css('general');
AImporter::js('common', 'joomla.javascript', 'view-images');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_bookpro/assets/css/joomla3.css');
JLoader::register('JToolbarButtonALink', dirname(__FILE__) . DS . 'helpers' . DS . 'toolbar' . DS . 'alink.php');
JHtml::_('behavior.framework');
$app=JFactory::getApplication();
$controller	= JControllerLegacy::getInstance('Bookpro');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();