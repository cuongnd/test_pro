<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controlleradmin');
class JFBConnectControllerChannels extends JControllerAdmin
{
    public function getModel($name = 'Channel', $prefix = 'JFBConnectModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

}