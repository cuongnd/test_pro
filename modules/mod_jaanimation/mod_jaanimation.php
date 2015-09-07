<?php
/**
 * ------------------------------------------------------------------------
 * JA Animation module for Joomla 2.5 & 3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

/*ja-moduletable moduletable_jaanim  clearfix*/
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
require_once (dirname(__FILE__) . DS . 'helpers' . DS . 'helper.php');
$ModJAAnimation = new ModJAAnimation();
$helper = $ModJAAnimation->getInstance();
if (!defined('MODULE_PREFIX')) {
    define('MODULE_PREFIX', 'Mod');
}
$module_suffix = $params->get('moduleclass_sfx', '');
/*load profile name*/
$tmpparams = $helper->loadConfig($params);
///if load profile success and has params


/* //////////////////////////////////////////////////////////////////module setttings */
$module_suffix = $tmpparams->get('moduleclass_sfx', '');
$module_id = MODULE_PREFIX . $module->id;
$module_absolute = $tmpparams->get('mod_absolute', 0);
$module_top = $tmpparams->get('mod_top', 0);
$module_left = $tmpparams->get('mod_left', 0);
$screen = $module_absolute == 2 ? 1 : 0;
/* ///////////////////////////////////////////////////////////////////resource settings */
$zindex = $tmpparams->get('zindex', 6);
$element_id = 'ja-anim' . $module_id;
$description = $tmpparams->get('description', 'Lorem ipsome.....');
$description = htmlentities($description, ENT_QUOTES, "UTF-8");
$showdesc = $tmpparams->get('showdesc', 0);
$tooltip_offset = $tmpparams->get('tooltip_offset', '120:20');
$image_url = $tmpparams->get('image_url', 'modules/mod_jaanimation/assets/images/Ballooning2.png');
/*Fixed incorrect image url when enable use multil frame images*/
if (!preg_match('/^http:\/\//', $image_url)) {
    $image_url = JURI::base() . $image_url;
}
/* end fixed*/
$twidth = $tmpparams->get('twidth', '');
$theight = $tmpparams->get('theight', '');
$image_width = "";
$image_height = "";
if (!empty($twidth) && is_numeric($twidth)) {
    $image_width = 'width="' . $twidth . '"';
}
if (!empty($theight) && is_numeric($theight)) {
    $image_height = 'height="' . $theight . '"';
}

/* //////////////////////////////////////////////////////////////////item anim settings */
$direction = $tmpparams->get('direction', 'v');
$movetype = $tmpparams->get('movetype', 'straight');
$repeat = $tmpparams->get('repeat', 0);
$changebg = $tmpparams->get('changebg', 0);
$delay = $tmpparams->get('delay', NULL);
$framesize = $tmpparams->get('framesize', NULL);
$frameitem = $tmpparams->get('frameitem', NULL);
$frameorder = $tmpparams->get('frameorder', ''); /* v:vertical, h: horizontal */
$frameitem_width = $tmpparams->get('framewidth', '180');
$frameitem_height = !empty($framesize) ? $framesize : '80';
$changespeed = $tmpparams->get('changespeed', NULL);
$frame_per_second = $tmpparams->get('fps', 50);

$duration = $tmpparams->get('duration', 30000);
/* get param org_pos */
$pre_posxy = $tmpparams->get('pre_pos_xy', '');
$pre_pos_x_arr = array();
$pre_pos_y_arr = array();
$pre_pos_x = '';
$pre_pos_y = '';
if (!empty($pre_posxy)) {
    $regex = '/(\d*,\d*)/';
    preg_match_all($regex, $pre_posxy, $matches);
    if (!empty($matches)) {
        foreach ($matches[1] as $item) {
            $tempArr = explode(",", $item);
            if (!empty($tempArr)) {
                $pre_pos_x_arr[] = $tempArr[0];
                $pre_pos_y_arr[] = $tempArr[1];
            }
        }
    }
    $pre_pos_x = implode(",", $pre_pos_x_arr);
    $pre_pos_y = implode(",", $pre_pos_y_arr);
}

$org_pos = $tmpparams->get('org_pos', '20:0');
$arr_org_pos = explode(":", $org_pos);
$org_pos_x = 0;
$org_pos_y = 0;
if (count($arr_org_pos) > 0) {
    if (isset($arr_org_pos[0])) {
        $org_pos_x = $arr_org_pos[0];
    }
    if (isset($arr_org_pos[1])) {
        $org_pos_y = $arr_org_pos[1];
    }

}
/* get param begin_pos */
$begin_pos = $tmpparams->get('begin_pos', '20:0');
$arr_begin_pos = explode(":", $begin_pos);
$begin_pos_x = 0;
$begin_pos_y = 0;
if (count($arr_begin_pos) > 0) {
    if (isset($arr_begin_pos[0])) {
        $begin_pos_x = $arr_begin_pos[0];
    }
    if (isset($arr_begin_pos[1])) {
        $begin_pos_y = $arr_begin_pos[1];
    }

}
/* get param end_pos */
$end_pos = $tmpparams->get('end_pos', '200:0');
$arr_end_pos = explode(":", $end_pos);
$end_pos_x = 0;
$end_pos_y = 0;
if (count($arr_end_pos) > 0) {
    if (isset($arr_end_pos[0])) {
        $end_pos_x = $arr_end_pos[0];
    }
    if (isset($arr_end_pos[1])) {
        $end_pos_y = $arr_end_pos[1];
    }
}
$step_start = $tmpparams->get('step', 4); /*begin run on step */
/*
	get other options
*/
$radius = $tmpparams->get('radius', 0);
$arr_options = array();
$options = "";
/* set params with option movetype = sine */
if ($radius > 0 && $movetype == "sine") {
    $arr_options[] = 'radius:' . $radius;
    $arr_options[] = 'step:' . $step_start;
}
if ($showdesc == 1) {
    $arr_options[] = 'desc:true';
    $arr_offset = explode(":", $tooltip_offset);
    if (!empty($arr_offset) && is_array($arr_offset)) {
        $str_offset = "";
        if (isset($arr_offset[0])) {
            $str_offset .= "{x:" . $arr_offset[0] . ",";
        }
        if (isset($arr_offset[1])) {
            $str_offset .= "y:" . $arr_offset[1] . "}";
        }
        if (!empty($str_offset)) {
            $arr_options[] = "offsets:" . $str_offset;
        }
    }
}
$option_pos_xy = "";
/* set params with option movetype = preset */
if ($movetype == "preset") {
    if (!empty($pre_pos_x)) {
        $option_pos_xy = "{x:[" . $pre_pos_x . "],";
    } else {
        $option_pos_xy = "{x:[],";
    }
    if (!empty($pre_pos_y)) {
        $option_pos_xy .= "y:[" . $pre_pos_y . "]}";
    } else {
        $option_pos_xy .= "y:[]}";
    }
}
$item_width_height = "";
/*set params with option changebg is enable*/
if (!empty($changebg) && $changebg == 1) {
    if (!empty($image_url)) {
        $arr_options[] = "changebg: true";
        $arr_options[] = "bgurl:'" . $image_url . "'";
    }
    if ($framesize != NULL) {
        $arr_options[] = "framesize:" . $framesize;
    }
    if ($frameitem != NULL) {
        $arr_options[] = "frameitem:" . $frameitem;
    }
    if (!empty($frameorder)) {
        $arr_options[] = "frameorder:'" . $frameorder . "'";
    }
    if ($changespeed != NULL) {
        $arr_options[] = "changespeed:" . $changespeed;
    }
    if (!empty($frameitem_width)) {
        $item_width_height .= "width:" . $frameitem_width . "px;";
    }
    if (!empty($frameitem_height)) {
        $item_width_height .= "height:" . $frameitem_height . "px;";
    }
}
if (!empty($option_pos_xy)) {
    $arr_options[] = "pre_pos:" . $option_pos_xy;
}
/* */
if (($module_absolute == 1 || $module_absolute == 2) && !empty($screen) && $screen == 1) {
    $arr_options[] = "screen:true";
}
/* */
if ($repeat == 2 && $delay != NULL) {
    $arr_options[] = "delay:" . $delay;
}
/* setting frame per second */
if (!empty($frame_per_second)) {
    $arr_options[] = "fps:" . $frame_per_second;
}
if (!empty($arr_options)) {
    $options = "," . implode(',', $arr_options);
}
/*end get options
////////////////include resources
*/
$mainframe = JFactory::getApplication();
if (!defined('_MODE_JAANIM_ASSETS_')) {
    define('_MODE_JAANIM_ASSETS_', 1);
    JHTML::stylesheet('modules/' . $module->module . '/assets/css/style.css');
    if (is_file(JPATH_SITE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'css' . DS . $module->module . ".css"))
        JHTML::stylesheet('templates/' . $mainframe->getTemplate() . '/css/' . $module->module . ".css");
}
//mootools support joomla 1.7 and 2.5
JHTML::_('behavior.framework', true);

JHTML::script('modules/' . $module->module . '/assets/script.js');
/* */
$path = JModuleHelper::getLayoutPath($module->module, 'default');
if (file_exists($path)) {
    require ($path);
}
?>
<script type="text/javascript" language="javascript" charset="utf-8">
//<![CDATA[
<?php if($module_absolute == 1):?>
	window.addEvent('domready', function() {
		var module_id = '<?php echo $module_id;?>';
		$$("#"+module_id).setStyles({
									position:'absolute',
									top:'<?php echo $module_top;?>px',
									left:'<?php echo $module_left;?>px'
									});
	})
<?php endif;?>

window.addEvent('domready', function() {
	var anim<?php echo $module_id;?> = new jaAnim('<?php echo $element_id;?>',{
			direction: '<?php echo $direction;?>', /* h: horizontal, v: vertical */
			movetype: '<?php echo $movetype;?>', /* straight | sine */
			loop: <?php echo $repeat;?>,
			duration: <?php echo $duration;?>,
			org_pos: {x:<?php echo is_numeric($org_pos_x)?$org_pos_x:"'".$org_pos_x."'";?>,y:<?php echo is_numeric($org_pos_y)?$org_pos_y:"'".$org_pos_y."'";?>},
			begin_pos: {x:<?php echo is_numeric($begin_pos_x)?$begin_pos_x:"'".$begin_pos_x."'";?>,y:<?php echo is_numeric($begin_pos_y)?$begin_pos_y:"'".$begin_pos_y."'";?>},
			end_pos: {x:<?php echo is_numeric($end_pos_x)?$end_pos_x:"'".$end_pos_x."'";?>,y:<?php echo is_numeric($end_pos_y)?$end_pos_y:"'".$end_pos_y."'";?>},
			index: <?php echo $zindex;?>
			<?php echo $options;?>
	});
});

//]]>
</script>