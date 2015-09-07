<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Categories helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class FilesHelper
{
	/**
	 * Configure the Submenu links.
	 *
	 * @param   string  $extension  The extension being used for the categories.
	 *
	 * @return  void
	 *
	 * @since
     * */
    public function getTruePath($path='',$truePath='')
    {
        if($path=='')
        {
            return $truePath;
        }
        $folders=JFolder::folders($truePath);
        $listFolder=explode('/',$path);
        $firstDirectory=reset($listFolder);
        $exists=0;
        foreach($folders as $folder)
        {
            if(strtolower($folder)==strtolower($firstDirectory))
            {
                $truePath.='/'.$folder;
                $exists=1;
                break;
            }
        }
        array_shift($listFolder);
        $path=implode('/',$listFolder);

        return FilesHelper::getTruePath($path,$truePath);
    }
    public function getFoldersFiles($path='')
    {
        $path=str_replace(JPATH_ROOT.'/','',$path);
        $path=FilesHelper::getTruePath($path,JPATH_ROOT);
        $folderFiles=array();
        $folders=JFolder::folders($path);
        foreach($folders as $folder)
        {
            $item=new stdClass();
            $item->name=strtolower($folder);
            $item->type='folder';
            $folderFiles[]=$item;


        }
        $files=JFolder::files($path);
        foreach($files as $file)
        {
            $item=new stdClass();
            $item->name=strtolower($file);
            $item->type='file';
            $fileSize=filesize($path.'/'.$file);
            $item->size=JUtility::byteFormat($fileSize) ;
            $fileMTime =JFactory::getDate(filemtime($path.'/'.$file));
            $item->mtime=$fileMTime->format('Y-m-d H:i:s') ;
            $folderFiles[]=$item;

        }
        return $folderFiles;
    }

    public function getFoldersAndFilesLocalAndServer($pathLocal='',$pathServer='')
    {
        $items_local=FilesHelper::getFoldersFiles($pathLocal);
        $items_local=JArrayHelper::pivot($items_local,'name');

        $items_file=array();
        $items_folder=array();
        foreach($items_local as $item)
        {
            if($item->type=='file')
            {
                $items_file[]=strtolower($item->name);
            }
            if($item->type=='folder')
            {
                $items_folder[]=strtolower($item->name);
            }
        }
        $link='http://www.supper.websitetemplatepro.com/index.php?option=com_filemanager&task=files.ajaxRemoteGetFolderFilesServer&data_path='.base64_encode($pathServer).'&isAjax=1';
        $items_server=json_decode(file_get_contents($link));
        $items_server=JArrayHelper::pivot($items_server,'name');
        foreach($items_server as $item)
        {
            if($item->type=='file'&&!in_array($item->name,$items_file))
            {
                $items_file[]=strtolower($item->name);
            }
            if($item->type=='folder'&&!in_array($item->name,$items_folder))
            {
                $items_folder[]=strtolower($item->name);
            }
        }


        sort($items_folder);
        sort($items_file);


        $foldersFiles=array();
        foreach($items_folder as $item)
        {
            $foldersFiles[$item]['local']=$items_local[$item];
            $foldersFiles[$item]['server']=$items_server[$item];
        }
        foreach($items_file as $item)
        {
            $foldersFiles[$item]['local']=$items_local[$item];
            $foldersFiles[$item]['server']=$items_server[$item];
        }
        return $foldersFiles;
    }
    public function CalculatorFilesServer($pathLocal='',$pathServer='')
    {
        $link='http://www.supper.websitetemplatepro.com/index2.php?option=com_filemanager&task=files.ajaxCalculatorFilesServer&isAjax=1';
        $items_server=json_decode(file_get_contents($link));
        return $items_server;


    }
}
