<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::helper('bookpro', 'image', 'model','request', 'controller');

class BookProViewAjaxImage extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$task = JRequest::getVar('task');
		
		if($task=='uploadimage'){
			$this->uploadimage();
		}
		
			if($task=='remove'){
			$this->remove();
		}
				
		parent::display($tpl);
	}
	function uploadimage()
	{
		if (AImage::uploadMd5(JPath::clean('components' . DS . 'com_bookpro' . DS . 'assets' . DS . 'images' . DS . 'imagesmd5'. DS), 'newFile', $uimage, $error)) {
			echo  $uimage -> name; die;
		}else{
			echo(JText::_('COM_BOOKPRO_SAVE_FAILED')); die;
		}
	}
	function remove()
	{
		$image = JRequest::getVar('image');
	
		if(JFile::delete(JPath::clean('components' . DS . 'com_bookpro' . DS . 'assets' . DS . 'images' . DS . 'imagesmd5'. DS . $image)))
		{
			echo JText::_('COM_BOOKPRO_SUCCESSFULLY_DELETED'); die;
		}else{
			echo JText::_('COM_BOOKPRO_DELETE_FAILED'); die;
		}
	}
}
