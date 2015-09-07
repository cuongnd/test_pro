<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Folder Media Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_media
 * @since       1.5
 */
class MediaControllerFolder extends JControllerLegacy
{
	/**
	 * Deletes paths from the current path
	 *
	 * @return  boolean
	 *
	 * @since   1.5
	 */
	public function delete()
	{
		JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

		$user	= JFactory::getUser();

		// Get some data from the request
		$tmpl   = $this->input->get('tmpl');
		$paths  = $this->input->get('rm', array(), 'array');
		$folder = $this->input->get('folder', '', 'path');

		$redirect = 'index.php?option=com_media&folder=' . $folder;

		if ($tmpl == 'component')
		{
			// We are inside the iframe
			$redirect .= '&view=mediaList&tmpl=component';
		}

		$this->setRedirect($redirect);

		// Just return if there's nothing to do
		if (empty($paths))
		{
			return true;
		}

		if (!$user->authorise('core.delete', 'com_media'))
		{
			// User is not authorised to delete
			JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'));

			return false;
		}

		// Set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		$ret = true;

		JPluginHelper::importPlugin('content');
		$dispatcher	= JEventDispatcher::getInstance();

		if (count($paths))
		{
			foreach ($paths as $path)
			{
				if ($path !== JFile::makeSafe($path))
				{
					$dirname = htmlspecialchars($path, ENT_COMPAT, 'UTF-8');
					JError::raiseWarning(100, JText::sprintf('COM_MEDIA_ERROR_UNABLE_TO_DELETE_FOLDER_WARNDIRNAME', substr($dirname, strlen(COM_MEDIA_BASE))));
					continue;
				}

				$fullPath = JPath::clean(implode(DIRECTORY_SEPARATOR, array(COM_MEDIA_BASE, $folder, $path)));
				$object_file = new JObject(array('filepath' => $fullPath));

				if (is_file($object_file->filepath))
				{
					// Trigger the onContentBeforeDelete event.
					$result = $dispatcher->trigger('onContentBeforeDelete', array('com_media.file', &$object_file));

					if (in_array(false, $result, true))
					{
						// There are some errors in the plugins
						JError::raiseWarning(100, JText::plural('COM_MEDIA_ERROR_BEFORE_DELETE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
						continue;
					}

					$ret &= JFile::delete($object_file->filepath);

					// Trigger the onContentAfterDelete event.
					$dispatcher->trigger('onContentAfterDelete', array('com_media.file', &$object_file));
					$this->setMessage(JText::sprintf('COM_MEDIA_DELETE_COMPLETE', substr($object_file->filepath, strlen(COM_MEDIA_BASE))));
				}
				elseif (is_dir($object_file->filepath))
				{
					$contents = JFolder::files($object_file->filepath, '.', true, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX', 'index.html'));

					if (empty($contents))
					{
						// Trigger the onContentBeforeDelete event.
						$result = $dispatcher->trigger('onContentBeforeDelete', array('com_media.folder', &$object_file));

						if (in_array(false, $result, true))
						{
							// There are some errors in the plugins
							JError::raiseWarning(100, JText::plural('COM_MEDIA_ERROR_BEFORE_DELETE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));
							continue;
						}

						$ret &= !JFolder::delete($object_file->filepath);

						// Trigger the onContentAfterDelete event.
						$dispatcher->trigger('onContentAfterDelete', array('com_media.folder', &$object_file));
						$this->setMessage(JText::sprintf('COM_MEDIA_DELETE_COMPLETE', substr($object_file->filepath, strlen(COM_MEDIA_BASE))));
					}
					else
					{
						// This makes no sense...
						JError::raiseWarning(100, JText::sprintf('COM_MEDIA_ERROR_UNABLE_TO_DELETE_FOLDER_NOT_EMPTY', substr($object_file->filepath, strlen(COM_MEDIA_BASE))));
					}
				}
			}
		}

		return $ret;
	}
	 public function ajaxReadImage()
	 {
		 $listImage=array();
		 $files=JFolder::files(JPATH_ROOT.'/images/stories');
		 foreach($files as $file)
		 {
			 $obj=new stdClass();
			 $obj->name=$file;
			 $obj->type='f';
			 $listImage[]=$obj;
		 }
		 header('Content-Type: application/json');
		 echo json_encode($listImage,JSON_NUMERIC_CHECK);
		 die;
	 }
	public function ajaxGetThumbnail()
	{
		require_once JPATH_ROOT.'/media/kendotest/php/lib/ImageBrowser.php';
		$app=JFactory::getApplication();
		$path=$app->input->get('path','','string');
		$imageBrowser=new ImageBrowser();
		@ob_end_clean();
		$imageBrowser->getThumbnail(JPATH_ROOT.'/images/stories'.'/'.$path);
		die;


	}
	function getImage_w($image,$w,$base64=false){
		// Get the extension of the file
		$file = explode('.',basename($image));
		$ext = array_pop($file);
		// These operations are the same regardless of file-type
		$size = getimagesize($image);
		$src_w = $size[0];
		$src_h = $size[1];
		$dst_w = $w;
		$dst_h = round(($dst_w/$src_w)*$src_h);
		$dst_im = imagecreatetruecolor($dst_w,$dst_h);
		// These operations are file-type specific
		switch (strtolower($ext)) {
			case 'jpg': case 'jpeg':
			$ctype = 'image/jpeg';;
			$src_im = imagecreatefromjpeg($image);
			$outfunc = 'imagejpeg';
			break;
			case 'png':
				$ctype = 'image/png';;
				$src_im = imagecreatefrompng($image);
				$outfunc = 'imagepng';
				break;
			case 'gif':
				$ctype = 'image/gif';;
				$src_im = imagecreatefromgif($image);
				$outfunc = 'imagegif';
				break;
		}
		// Do the resample
		imagecopyresampled($dst_im,$src_im,0,0,0,0,$dst_w,$dst_h,$src_w,$src_h);
		// Get the image data into a base64_encoded string
		ob_start();
		$outfunc($dst_im);
		$imgdata = base64_encode(ob_get_contents()); // Don't use ob_get_clean() in case we're ever running on some ancient PHP build
		ob_end_clean();
		if($base64)
			return "data:$ctype;base64,$imgdata";
		else
		{   // Return the data so it can be used inline in HTML
			return  $imgdata;
		}
	}	/**
	 * Create a folder
	 *
	 * @return  boolean
	 *
	 * @since   1.5
	 */
	public function create()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user  = JFactory::getUser();

		$folder      = $this->input->get('foldername', '');
		$folderCheck = (string) $this->input->get('foldername', null, 'raw');
		$parent      = $this->input->get('folderbase', '', 'path');

		$this->setRedirect('index.php?option=com_media&folder=' . $parent . '&tmpl=' . $this->input->get('tmpl', 'index'));

		if (strlen($folder) > 0)
		{
			if (!$user->authorise('core.create', 'com_media'))
			{
				// User is not authorised to create
				JError::raiseWarning(403, JText::_('JLIB_APPLICATION_ERROR_CREATE_NOT_PERMITTED'));

				return false;
			}

			// Set FTP credentials, if given
			JClientHelper::setCredentialsFromRequest('ftp');

			$this->input->set('folder', $parent);

			if (($folderCheck !== null) && ($folder !== $folderCheck))
			{
				$app = JFactory::getApplication();
				$app->enqueueMessage(JText::_('COM_MEDIA_ERROR_UNABLE_TO_CREATE_FOLDER_WARNDIRNAME'), 'warning');

				return false;
			}

			$path = JPath::clean(COM_MEDIA_BASE . '/' . $parent . '/' . $folder);

			if (!is_dir($path) && !is_file($path))
			{
				// Trigger the onContentBeforeSave event.
				$object_file = new JObject(array('filepath' => $path));
				JPluginHelper::importPlugin('content');
				$dispatcher	= JEventDispatcher::getInstance();
				$result = $dispatcher->trigger('onContentBeforeSave', array('com_media.folder', &$object_file, true));

				if (in_array(false, $result, true))
				{
					// There are some errors in the plugins
					JError::raiseWarning(100, JText::plural('COM_MEDIA_ERROR_BEFORE_SAVE', count($errors = $object_file->getErrors()), implode('<br />', $errors)));

					return false;
				}

				if (JFolder::create($object_file->filepath))
				{
					$data = "<html>\n<body bgcolor=\"#FFFFFF\">\n</body>\n</html>";
					JFile::write($object_file->filepath . "/index.html", $data);

					// Trigger the onContentAfterSave event.
					$dispatcher->trigger('onContentAfterSave', array('com_media.folder', &$object_file, true));
					$this->setMessage(JText::sprintf('COM_MEDIA_CREATE_COMPLETE', substr($object_file->filepath, strlen(COM_MEDIA_BASE))));
				}
			}

			$this->input->set('folder', ($parent) ? $parent . '/' . $folder : $folder);
		}
		else
		{
			// File name is of zero length (null).
			JError::raiseWarning(100, JText::_('COM_MEDIA_ERROR_UNABLE_TO_CREATE_FOLDER_WARNDIRNAME'));

			return false;
		}

		return true;
	}
}
