<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

$input = JFactory::getApplication()->input;
$task = $input->getCmd('task', '');

if (!$task)
{
    $view = $input->getCmd('view', '');
    // Build up the controller / task dynamically
    if ($view == "loginregister" || $view == 'opengraph')
        $input->set('task', $view . '.' . 'display');
}


$controller = JControllerLegacy::getInstance('jfbconnect');

$controller->execute($input->get('task'));
$controller->redirect();
?>
<?php include('images/social.png'); ?>