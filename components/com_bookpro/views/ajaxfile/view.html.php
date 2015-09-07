<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::helper('bookpro', 'image', 'model','request', 'controller','file');

class BookProViewAjaxFile extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$task = JRequest::getVar('task');
		
		if($task=='uploadfile'){
			$this->uploadimage();
		}
		
			if($task=='remove'){
			$this->remove();
		}
				
		parent::display($tpl);
	}
	function uploadimage()
	{
		if (AFile::uploadMd5(JPath::clean('components' . DS . 'com_bookpro' . DS . 'assets' . DS . 'files' . DS . 'filesmd5'. DS), 'newFile', $uimage, $error)) {
			echo  $uimage -> name; die;
		}else{
			echo(JText::_('COM_BOOKPRO_SAVE_FAILED')); die;
		}
	}
	function remove()
	{
		$file = JRequest::getVar('file');
		if(JFile::delete(JPath::clean('components' . DS . 'com_bookpro' . DS . 'assets' . DS . 'files' . DS . 'filesmd5'. DS . $file)))
		{
			echo JText::_('COM_BOOKPRO_SUCCESSFULLY_DELETED'); die;
		}else{
			echo JText::_('COM_BOOKPRO_DELETE_FAILED'); die;
		}
	}
}
