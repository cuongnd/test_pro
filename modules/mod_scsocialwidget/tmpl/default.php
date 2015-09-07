<?php
/**
 * @package		JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

if ($userIntro != '') {
    echo '<div class="sc_social_widget">'.$userIntro."</div>";
}

if($widget)
    echo $widget->render();

require(JPATH_ROOT.'/components/com_jfbconnect/assets/poweredBy.php');
?>