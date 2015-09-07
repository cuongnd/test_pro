<?php

/**
 * @version        $Id: k2.php 1978 2013-05-15 19:34:16Z joomlaworks $
 * @package        K2
 * @author        JoomlaWorks http://www.joomlaworks.net
 * @copyright    Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemAjaxGetContent extends JPlugin
{

    function plgSystemAjaxGetContent(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onAfterRoute()
    {

        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();

    }

    // Extend user forms with K2 fields
    public function __destruct()
    {

    }

    function onAfterInitialise()
    {
        $app=JFactory::getApplication('site');

        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');
        if($ajaxGetContent)
        {
            require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
            $editingWebsiteState=$app->input->get('editingWebsiteState',0,'int');
            UtilityHelper::setEditingState($editingWebsiteState);
            $screenSize=$app->input->get('screenSize','','string');
            $isAdminSite=UtilityHelper::isAdminSite();
            if($isAdminSite)
                UtilityHelper::setCurrentScreenSizeEditing($screenSize);
            else
            {
                UtilityHelper::setScreenSize($screenSize);
            }
        }


    }
    function onAfterRender()
    {


        $app=JFactory::getApplication('site');
        $ajaxGetContent=$app->input->get('ajaxgetcontent',0,'int');

        //code here
    }



}
