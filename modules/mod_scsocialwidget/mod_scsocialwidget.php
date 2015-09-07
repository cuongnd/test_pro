<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
if (!JFile::exists(JPATH_ROOT . '/components/com_jfbconnect/libraries/provider.php'))
{
    echo "JFBConnect not found. Please reinstall.";
    return;
}

$userIntro = $params->get('user_intro');
$providerType = $params->get('provider_type');
$widgetType = $params->get('widget_type');
$widget = JFBCFactory::widget($providerType, $widgetType, $params->get('widget_settings'));

require(JModuleHelper::getLayoutPath('mod_scsocialwidget'));

?>
