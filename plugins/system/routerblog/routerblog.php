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

class plgSystemRouterBlog extends JPlugin
{

    function plgSystemRouterBlog(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onAfterRoute()
    {

        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();



    }

    // Extend user forms with K2 fields
    function onAfterDispatch()
    {



    }
    public function __destruct()
    {

    }

    function onAfterInitialise()
    {

        /*$query=array(
            'option'=>'com_easyblog'
            ,'view'=>'blogger'
            ,'layout'=>'listings'
            ,'id'=>3007
            ,'tmpl'=>'component'

        );
        $uri=JFactory::getURI();
        jimport('joomla.utilities.utility');
        print_r(JString::parse_url($uri->getHost()));
        echo "<pre>";
        print_r($uri);
        die;
        $uri->setQuery(http_build_query($query));*/

    }
    function onAfterRender()
    {

    }



}
