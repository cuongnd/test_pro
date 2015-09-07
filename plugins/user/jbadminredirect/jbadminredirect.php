<?php

/**
 * @package  Bookpro
 * @author   Nguyen Dinh Cuong
 * @link   http://ibookingonline.com
 * @copyright  Copyright (coffee) 2011 - 2012 Nguyen Dinh Cuong
 * @license  GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

jimport('joomla.application.application');



class plgUserJbAdminRedirect extends JPlugin

{

    function onUserAfterLogin($user, $options = array())

	{

		$app =  JFactory::getApplication();

        if($app->isAdmin()) {

            $redirect = $this->params->get('redirect');

            if($redirect){

				$app->redirect($redirect);

            }

        }

    }

} 



