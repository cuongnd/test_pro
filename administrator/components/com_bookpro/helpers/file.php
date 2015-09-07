<?php


defined('_JEXEC') or die('Restricted access');

include_once (JPATH_LIBRARIES . '/joomla/archive/zip.php');


class AFile
{
	/**
	 * Upload file.
	 *
	 * @param string    $dest   target directory to upload
	 * @param string    $field  request fieldname
	 * @param stdClass  $file  property where sets ouptput with this format:
	 * $file->tmp    ...  request template file name
	 * $file->name   ...  uploaded file name
	 * $file->apath  ...  absolute path to file
	 * $file->rpath  ...  real path to file
	 * @param string    $error  property to set error messages
	 * @param boolean	$unpackZip  wheater unpack zip files
	 * @return boolean
	 */
	function upload($dest, $field, &$file, &$error, $unpackZip = false)
	{
		$adir = $dest;
		$rdir = JURI::root() . str_replace(DS, '/', $dest);
		$rdir = str_replace('//', '/', $dest);

		if (! file_exists($adir)) {
			if (! @mkdir($adir, 0775, true)) {
				$mainframe = &JFactory::getApplication();
				/* @var $mainframe JApplication */
				$mainframe->enqueueMessage(sprintf(JText::_('UNABLE_CREATE_DIRECTORY_S'), $adir), 'error');
				return false;
			}
		}

		if (isset($_FILES[$field])) {

			$request = &$_FILES[$field];

			$file = new stdClass();
			$file->tmp = $request['tmp_name'];
			$file->name = $request['name'];

			if ($request['error'] == 0) {

				$zip = new JArchiveZip();
				$data = JFile::read($file->tmp);
				$isZip = $zip->checkZipData($data);

				unset($data);

				if ($isZip && $unpackZip) {

					$tmpDir = AFile::getTmpDir();
					$zip->extract($file->tmp, $tmpDir);

					unset($zip);

					$files = &JFolder::files($tmpDir, '.', true, true);
					$count = count($files);

					for ($i = 0; $i < $count; $i ++) {
						$file->tmp = $files[$i];
						$file->name = JFile::getName($file->tmp);
						AFile::save($file, $adir, $rdir);
					}
					JFolder::delete($tmpDir);
					return true;

				} else {
					unset($zip);
					return AFile::save($file, $adir, $rdir);
				}
			}
		}
		return false;
	}

	function uploadMd5($dest, $field, &$file, &$error, $unpackZip = false)
	{
		$adir = $dest;
		$rdir = JURI::root() . str_replace(DS, '/', $dest);
		$rdir = str_replace('//', '/', $dest);

		if (! file_exists($adir)) {
			if (! @mkdir($adir, 0775, true)) {
				$mainframe = &JFactory::getApplication();
				/* @var $mainframe JApplication */
				$mainframe->enqueueMessage(sprintf(JText::_('Unable create directory %s'), $adir), 'error');
				return false;
			}
		}

		if (isset($_FILES[$field])) {

			$request = &$_FILES[$field];

			$file = new stdClass();
			$file->tmp = $request['tmp_name'];
			$file->name = $request['name'];

			// change name by Md5
			$first = strstr($file->name, '.',  true);
			$second = strstr($file->name, '.',  false);
			$file->name = md5($first).$second;
			 
			if ($request['error'] == 0) {

				$zip = new JArchiveZip();
				$data = JFile::read($file->tmp);
				$isZip = $zip->checkZipData($data);

				unset($data);

				if ($isZip && $unpackZip) {

					$tmpDir = AFile::getTmpDir();
					$zip->extract($file->tmp, $tmpDir);

					unset($zip);

					$files = &JFolder::files($tmpDir, '.', true, true);
					$count = count($files);

					for ($i = 0; $i < $count; $i ++) {
						$file->tmp = $files[$i];
						$file->name = JFile::getName($file->tmp);
						AFile::save($file, $adir, $rdir);
					}
					JFolder::delete($tmpDir);
					return true;

				} else {
					unset($zip);
					return AFile::save($file, $adir, $rdir);
				}
			}
		}
		return false;
	}

	function save(&$file, $adir, $rdir)
	{
		if (file_exists($file->tmp)){
			$extension = JFile::getExt($file->name);
			$shortname = str_replace('.' . $extension, '', $file->name);
			$shortname = JFilterOutput::stringURLSafe($shortname);
			$index = '';
			$number = 1;
			do {
				$file->name = $shortname . $index . '.' . $extension;
				$file->apath = $adir . $file->name;
				$file->rpath = $rdir . $file->name;
				$index = '-' . ($number ++);
			} while (file_exists($file->apath));
			JFile::copy($file->tmp, $file->apath);
			return $file;
		}
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		$mainframe->enqueueMessage(JText::sprintf('File does not exist: ', $file->name), 'notice');
		return null;
	}

	/**
	 * Get absolute path to files directory.
	 *
	 * @param string $dest part of absolute path from Joomla root
	 * @return string complet absolute path
	 */
	function getIPath($dest)
	{
		$dest = JPath::clean($dest);
		$length = JString::strlen($dest);
		$begin = JString::substr($dest, 0, 1);
		$end = JString::substr($dest, $length - 1, 1);
		if ($begin != DS) {
			$dest = DS . $dest;
		}
		if ($end != DS) {
			$dest .= DS;
		}
		$ipath = JPATH_ROOT . $dest;
		return $ipath;
	}

	/**
	 * Get path to file.
	 *
	 * @param $file		filename
	 * @param $url		true: url, (default) false: path
	 * @return string
	 */
	function getFPath($file = null,$url=false)
	{
		static $fpath;
		static $fUrl;

		if (empty($fpath)) {

			$fpath =  JPATH_SITE.'/images/';
			if (! file_exists($fpath)) {
				@mkdir($fpath, 0775, true);
			}
		}
		if (empty($fUrl))
		$fUrl = JURI::root().'images/';

		if ($url)
		return is_null($file) ? $fUrl : ($fUrl . $file);
		else
		return is_null($file) ? $fpath : ($fpath . $file);
	}

	function getTmpDir()
	{
		static $jTmpDir;
		if (is_null($jTmpDir)) {
			$config = &JFactory::getConfig();
			/* @var $config JRegistry */
			$jTmpDir = $config->get('config.tmp_path');
			$jTmpDir = realpath($jTmpDir);
		}
		do {
			$dirname = rand(1000, 2000);
			$tmpDir = $jTmpDir . DS . $dirname;
		} while (file_exists($tmpDir));
		@mkdir($tmpDir);
		return $tmpDir;
	}

	/**
	 * Get unique id for file
	 */
	function getId($file)
	{
		$file = str_replace(AFile::getFPath(),'',$file); //remove relative path
		$file = trim($file, ' '.DS);
			
		$id = sprintf("%u", crc32($file));
		return $id;
	}
}

?>