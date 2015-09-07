<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * products list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_product
 * @since       1.6
 */
class fileManagerControllerFiles extends JControllerAdmin
{
	public function AjaxGetFoldersFiles()
	{
		$app=JFactory::getApplication();
		$data_path=$app->input->get('data_path','','string');
		require_once JPATH_ROOT.'/administrator/components/com_filemanager/helpers/files.php';
		$items=FilesHelper::getFoldersAndFilesLocalAndServer(JPATH_ROOT.'/'.$data_path,$data_path);
		$returnArray=array();
		foreach($items as $item)
		{
			$returnArray[]=$item;
		}
		echo json_encode($returnArray);
		die;
	}

	public function AjaxServerCalculatorFiles()
	{
		$app=JFactory::getApplication();
		$data_path=$app->input->get('data_path','','string');
		require_once JPATH_ROOT.'/administrator/components/com_filemanager/helpers/files.php';
		$items=FilesHelper::CalculatorFilesServer(JPATH_ROOT.'/'.$data_path,$data_path);
		$returnArray=array();
		foreach($items as $item)
		{
			$returnArray[]=$item;
		}
		echo json_encode($returnArray);
		die;
	}

}
