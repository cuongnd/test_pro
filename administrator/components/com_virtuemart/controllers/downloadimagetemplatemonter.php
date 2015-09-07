<?php
/**
*
* Category controller
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: category.php 6071 2012-06-06 15:33:04Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

if(!class_exists('VmController'))require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'vmcontroller.php');

/**
 * Category Controller
 *
 * @package    VirtueMart
 * @subpackage Category
 * @author jseros, Max Milbers
 */
class VirtuemartControllerDownloadImageTemplateMonter extends VmController {

    public function getImageFromTemplateMonter()
    {
        $rootFolder='/images/stories/virtuemart/product/big_image_product/';
        $db=JFactory::getDbo();
        $input=JFactory::getApplication()->input;
        $query=$db->getQuery(true);
        $query->select('virtuemart_media_id,file_url');
        $query->from('#__virtuemart_medias');
        $query->where('downloadok=0');
        $db->setQuery($query,0,500);
        $medias=$db->loadObjectList();
        foreach($medias as $media)
        {
            $pathFile=$rootFolder.basename($media->file_url);
            $query=$db->getQuery(true);
            $query->update('#__virtuemart_medias');
            $query->set('file_url='.$db->quote($pathFile));
            $query->set('downloadok=1');
            $query->where('virtuemart_media_id='.$media->virtuemart_media_id);
            $db->setQuery($query);
            $db->execute();
            VirtuemartControllerDownloadImageTemplateMonter::downloadFile($media->file_url,JPATH_SITE.$pathFile);

        }

        echo json_encode($media);
        die;

    }

    private function downloadFile ($url, $path) {

        $ch = curl_init($url);
        $fp = fopen($path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

}
