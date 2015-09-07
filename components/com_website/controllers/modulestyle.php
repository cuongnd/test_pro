<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 * @since       1.6
 */
class WebsiteControllerModuleStyle extends JControllerForm
{
	/**
	 * Class constructor.
	 *
	 * @param   array  $config  A named array of configuration variables.
	 *
	 * @since   1.6
	 */

   public function AjaxGetModuleStyle()
   {

       $view = &$this->getView('modulestyle', 'html', 'WebsiteView');
       $view->parentDisPlay();
       $contents = ob_get_contents();
       ob_end_clean(); // get the callback function
       $respone_array[] = array(
           'key' => '.dialog_show_view_body',
           'contents' => $contents
       );
       echo json_encode($respone_array);
       exit();
   }
   public function AjaxGetModuleEditStyle()
   {

       $view = &$this->getView('modulestyle', 'html', 'WebsiteView');
       $view->setLayout('editstyle');
       $view->parentDisPlay();
       $contents = ob_get_contents();
       ob_end_clean(); // get the callback function
       $respone_array[] = array(
           'key' => '.module_dialog_show_view_body',
           'contents' => $contents
       );
       echo json_encode($respone_array);
       exit();
   }
   public function AjaxGetModuleEditAnimation()
   {

       $view = &$this->getView('modulestyle', 'html', 'WebsiteView');
       $view->setLayout('editanimation');
       $view->parentDisPlay();
       $contents = ob_get_contents();
       ob_end_clean(); // get the callback function
       $respone_array[] = array(
           'key' => '.module_dialog_show_view_body',
           'contents' => $contents
       );
       echo json_encode($respone_array);
       exit();
   }
   public function AjaxGetEditStyleItem()
   {

       $view = &$this->getView('modulestyle', 'html', 'WebsiteView');
       $view->setLayout('editstyleitem');
       $view->parentDisPlay();
       $contents = ob_get_contents();
       ob_end_clean(); // get the callback function
       $respone_array[] = array(
           'key' => '.style_item_dialog_show_view_body',
           'contents' => $contents
       );
       echo json_encode($respone_array);
       exit();
   }
    public function aJaxSaveStyleModule()
    {
        $app=JFactory::getApplication();
        $backgroundPath=$app->input->get('backgroundPath','','string');
        $website=JFactory::getWebsite();
        $tableWebsite=JTable::getInstance('Website','JTable');
        $tableWebsite->load($website->website_id);

        $registry = new JRegistry;
        $registry->loadString($tableWebsite->style);
        $tableWebsite->style = $registry->toArray();
        $tableWebsite->style['body']['background']=$backgroundPath;
        $tableWebsite->style=json_encode($tableWebsite->style);
        $tableWebsite->alias=$tableWebsite->title;
        if(!$tableWebsite->store())
        {
            print_r($tableWebsite->getError());
        }
        die;

    }

}
