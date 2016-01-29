<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	28 March 2012
 * @file name	:	modules/mod_jblancecategory/mod_jblancecategory.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( dirname(__FILE__).'/helper.php' );
$total_column 	  = intval($params->get('total_column', 1));

$show_count 	  = $params->get('show_count', 1);
$show_count=JUtility::toStrictBoolean($show_count);
$show_empty_count = $params->get('show_empty_count', 1);
$show_empty_count=JUtility::toStrictBoolean($show_empty_count);

$set_Itemid = intval($params->get('set_itemid', 0));

$rows = ModJblanceCategoryHelper::getCategory(1);
require(JModuleHelper::getLayoutPath('mod_jblancecategory'));

?>