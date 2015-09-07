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
		$items=FilesHelper::getFoldersAndFilesLocalAndServer(JPATH_ROOT.'/'.$data_path);
		echo json_encode($items);
		die;
	}//only server
	public function ajaxRemoteGetFolderFilesServer()
	{

		$app=JFactory::getApplication();
		$pathServer=$app->input->get('data_path','','string');
		$pathServer=base64_decode($pathServer);
		$pathServer=$pathServer?JPATH_ROOT.'/'.$pathServer:JPATH_ROOT;
		require_once JPATH_ROOT.'/administrator/components/com_filemanager/helpers/files.php';
		$items_local=FilesHelper::getFoldersFiles($pathServer);

		echo json_encode($items_local);
		die;
	}
	public function ajaxCalculatorFilesServer()
	{
		$arrayFoldersAndFiles=array();
		JUtility::listFolderFiles(JPATH_ROOT,$arrayFoldersAndFiles,'');
		$modelFiles=JModelLegacy::getInstance('files','fileManagerModel');
		$modelFiles->saveFilesAndFolder($arrayFoldersAndFiles);
		echo "<pre>";
		print_r($arrayFoldersAndFiles);
		echo "hello ajaxCalculatorFilesServer";
		die;
	}

}
