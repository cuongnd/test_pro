<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
class WebsiteSetupMenus
{
    public function checkSetupWebsite($website_id)
    {
        return true;
    }
    public function addStepSetupWebsite(&$steps)
    {
        $objStep=new stdClass();
        $objStep->step="checkSetupMenu";
        $objStep->className=__CLASS__;
        $objStep->fileName=__FILE__;
        $objStep->functiongetLayoutCurrentStep='getLayoutOfCurrentStep';
        $objStep->pathLayoutSetup=__DIR__.'/layoutsetup/default.php';
        $steps=$objStep->step.':'.json_encode($objStep);
    }
    public function getLayoutOfCurrentStep()
    {

    }
}