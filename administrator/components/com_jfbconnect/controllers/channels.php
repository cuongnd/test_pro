<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
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