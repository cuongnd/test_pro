<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * websitetemplatepro list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.6
 */
class websitetemplateproControllerlisttemplatecategory extends JControllerAdmin
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'raovat', $prefix = 'websitetemplateproModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
    public function ajax_get_website_template_by_category(){

        $app=JFactory::getApplication();
        $input=$app->input;
        $respone_array=array();
        $post = file_get_contents('php://input');
        $post = json_decode($post);
        $view = $this->getView('listtemplatecategory', 'listtemplate', 'websitetemplateproView');
        $view->category_id=$post->category_id;

        ob_start();
        $view->setLayout('frontend');
        $view->display('listtemplate');
        $contents = ob_get_clean();
        $respone_array[] = array(
            'key' => '.area-list-template',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        die;

    }







}
