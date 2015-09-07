<?php
/**
 * @package		Google Analytics Dashboard - Module for Joomla!
 * @author		DeConf - http://deconf.com
 * @copyright	Copyright (c) 2010 - 2012 DeConf.com
 * @license		GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
if (($params->get('ga_chart_theme')==1) OR (($params->get('ga_chart_theme')==3) AND (!preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|sagem|sharp|sie-|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])))){
	$ga_style= '
	<style type="text/css">
		.gatable{
			font-family:Arial; 
			font-weight:bold; 
			font-size:10pt;
		}
		.gabutton {
			-moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			-webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			box-shadow:inset 0px 1px 0px 0px #97c4fe;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #3d94f6), color-stop(1, #1e62d0) );
			background:-moz-linear-gradient( center top, #3d94f6 5%, #1e62d0 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#3d94f6\', endColorstr=\'#1e62d0\');
			background-color:#3d94f6;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #337fed;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:11px;
			font-weight:bold;
			padding:4px 3%;
			text-decoration:none;
			text-shadow:1px 1px 0px #1570cd;
		}
		.gabutton:hover {
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #1e62d0), color-stop(1, #3d94f6) );
			background:-moz-linear-gradient( center top, #1e62d0 5%, #3d94f6 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#1e62d0\', endColorstr=\'#3d94f6\');
			background-color:#1e62d0;
		}
		.gabutton:active {
			position:relative;
			top:1px;
		}
		a.gatable:link { 
			color: #FFFFC6; 
			text-decoration: none;
		}
		a.gatable:visited {
			color: #FFFFC6; 
			text-decoration: none;
		}
		a.gatable:hover {
			color: #FAFF00; 
			text-decoration: none;
		}
		
		#buttons_div, #chart1_div, #details_div, #ga_dash_mapdata {
			width: '.$params->get('ga_chart_width').'px;
		}
		
		#ga_dash_pgddata, #ga_dash_rdata, #ga_dash_sdata {
			width: '.($params->get('ga_chart_width')+10).'px;
		}
		
		#chart1_div {
			height: '.$params->get('ga_chart_height').'px;
		}

		#ga_dash_trafficdata, #ga_dash_nvrdata, #ga_dash_pgddata, #ga_dash_rdata, #ga_dash_sdata {
			height: '.($params->get('ga_chart_height')+30).'px;
		}		
		
		#ga_dash_mapdata{
			height: '.(2*$params->get('ga_chart_height')-100).'px;
		} 
		
		#ga-dash { 
			margin:10px; 
		}

	';

	$ga_style.='table.gatable {
		width:'.($params->get('ga_chart_width')).'px;
	}';
		
	$ga_style.='

		table.gatable {
			-moz-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			-webkit-box-shadow:inset 0px 1px 0px 0px #97c4fe;
			box-shadow:inset 0px 1px 0px 0px #97c4fe;
			background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #3d94f6), color-stop(1, #1e62d0) );
			background:-moz-linear-gradient( center top, #3d94f6 5%, #1e62d0 100% );
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#3d94f6\', endColorstr=\'#1e62d0\');
			background-color:#3d94f6;
			-moz-border-radius:6px;
			-webkit-border-radius:6px;
			border-radius:6px;
			border:1px solid #337fed;
			display:inline-block;
			color:#ffffff;
			font-family:arial;
			font-size:11px;
			font-weight:bold;
			padding:2px 2px;
			text-decoration:none;
			text-shadow:1px 1px 0px #1570cd;
		}	
	</style>
	';
} else{
	$ga_style= '
	<style type="text/css">
		#chart1_div, #ga_dash_mapdata, #ga_dash_trafficdata, #ga_dash_pgddata {
			height: '.$params->get('ga_chart_height').'px;
		}

		#ga_dash_trafficdata, #ga_dash_nvrdata, #ga_dash_pgddata, #ga_dash_rdata, #ga_dash_sdata {
			height: '.($params->get('ga_chart_height')+30).'px;
		}
		
		#ga_dash_mapdata{
			height: '.(2*$params->get('ga_chart_height')-100).'px;
		} 

		#ga_dash_pgddata, #ga_dash_rdata, #ga_dash_sdata {
			width: '.($params->get('ga_chart_width')+10).'px;
		}
		
		#buttons_div, #chart1_div, #details_div, #ga_dash_mapdata {
			width: '.$params->get('ga_chart_width').'px;
		}		
		
		#ga-dash { 
			margin:10px; 
		}
	</style>';
}


echo $ga_style.$output; 

?>
