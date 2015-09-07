<?php
/**
 * @package		Google Analytics Dashboard - Module for Joomla!
 * @author		DeConf - http://deconf.com
 * @copyright	Copyright (c) 2010 - 2012 DeConf.com
 * @license		GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// Include the syndicate functions only once
require_once( dirname(__FILE__).'/helper.php' );

$output = modGoogleAnalyticsDashboardHelper::ga_generate_code( $params );

require( JModuleHelper::getLayoutPath( 'mod_ga_dash' ) );

?>
