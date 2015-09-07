<?php

/**
 * Bookpro Image class
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: image.php 44 2012-07-12 08:05:38Z quannv $
 */

defined('_JEXEC') or die('Restricted access');

include_once (JPATH_LIBRARIES . DS . 'joomla' . DS . 'archive' . DS . 'zip.php');
class AImage
{

	/**
	 * Create thumbnail.
	 *
	 * @param string $path absolute path to original image
	 * @param int $cwidth max. thumbnail width
	 * @param int $cheight max. thumbnail height
	 * @return real path to thumnail
	 */
	function thumb($path, $cwidth = null, $cheight = null)
	{
		$config = &AFactory::getConfig();
		/* @var $config BookingConfig */
		 
		if (! is_file($path)) {
			return '';
		}

		if (! $cwidth && ! $cheight) {
			return '';
		}

		static $thumbs;

		$aroot = JPATH_ROOT . DS . 'cache' . DS . OPTION;

		$ini = $aroot . DS . 'thumbs.ini';

		if (is_null($thumbs)) {
			$thumbs = array();
			if (file_exists($ini)) {
				$thumbs = parse_ini_file($ini);
				if ($thumbs === false) {
					$thumbs = array();
				}
			}
		}
		jimport('joomla.filesystem.file');
		$name = JFile::getName($path);
		$ext = JFile::getExt($path);
		$ext = '.' . $ext;
		$names[] = str_replace($ext, '', $name);

		if ($cwidth) {
			$names[] = 'w' . $cwidth;
		}

		if ($cheight) {
			$names[] = 'h' . $cheight;
		}

		$names[] = $ext;

		$name = implode('', $names);

		if (isset($thumbs[$name])) {
			$rpath = $thumbs[$name];
			$apath = AImage::abspath($rpath);
			if (file_exists($apath)) {
				return $thumbs[$name];
			} else {
				unset($thumbs[$name]);
			}
		}

		$data = getimagesize($path);

		if ($data === false) {
			return '';
		}

		list ($width, $height, $type) = $data;

		switch ($type) {
			case 1:
			case 2:
			case 3:
				break;
			default:
				return '';
		}

		if ($width <= $cwidth && $height <= $cheight) {
			$rpath = AImage::realpath($path);
			return $rpath;
		}

		if ($cwidth > $width) {
			$cwidth = $width;
		}

		if ($cheight > $height) {
			$cheight = $height;
		}

		$rroot = JURI::root() . 'cache/' . OPTION . '/';

		if (! file_exists($aroot)) {
			if (! @mkdir($aroot, 0775, true)) {
				return '';
			}
		}

		$crc = (string) crc32($name);

		for ($i = 0; $i < CACHE_IMAGES_DEPTH; $i ++) {
			$d[] = substr($crc, $i, 1);
		}

		$arthumb = $aroot . DS . implode(DS, $d) . DS;
		$athumb = $arthumb . $name;
		$rthumb = $rroot . implode('/', $d) . '/' . $name;

		if (file_exists($athumb)) {
			return $rthumb;
		}

		if (! file_exists($arthumb)) {
			if (! @mkdir($arthumb, 0775, true)) {
				return '';
			}
		}

		$destX = 0;
		$destY = 0;

		$srcX = 0;
		$srcY = 0;

		$nwidth = $cwidth;
		$nheight = $cheight;

		$sheight = $height;
		$swidth = $width;

		if ($cwidth && $cheight) {
			$percent = $cwidth / $cheight;
			$sheight = round($width / $percent);
			if ($sheight > $height) {
				$sheight = $height;
				$swidth = round($height * $percent);
			}
			$srcX = round(($width - $swidth) / 2);
			$srcY = round(($height - $sheight) / 2);
		} elseif ($cwidth) {
			$nheight = round($height * $nwidth / $width);
		} elseif ($cheight) {
			$nwidth = round($width * $nheight / $height);
		}

		$thumb = imagecreatetruecolor($nwidth, $nheight);

		switch ($type) {
			case 1:
				$source = imagecreatefromgif($path);
				imagealphablending($thumb, false);
				imagesavealpha($thumb, true);
				break;
			case 2:
				$source = imagecreatefromjpeg($path);
				break;
			case 3:
				$source = imagecreatefrompng($path);
				imagealphablending($thumb, false);
				imagesavealpha($thumb, true);
				break;
			default:
				return '';
		}

		imagecopyresampled($thumb, $source, $destX, $destY, $srcX, $srcY, $nwidth, $nheight, $swidth, $sheight);

		switch ($type) {
			case 1:
				imagegif($thumb, $athumb);
				break;
			case 2:
				imagejpeg($thumb, $athumb, $config->jpgQuality);
				break;
			case 3:
				imagepng($thumb, $athumb, $config->pngQuality, $config->pngFilter);
				break;
			default:
				return '';
		}
		imagedestroy($source);
		imagedestroy($thumb);

		$handle = @fopen($ini, 'a');
		if ($handle !== false) {
			$cache = $name . '="' . $rthumb . '"' . PHP_EOL;
			@fwrite($handle, $cache);
		}
		return $rthumb;
	}

	/**
	 * Upload image.
	 *
	 * @param string    $dest   target directory to upload
	 * @param string    $field  request fieldname
	 * @param stdClass  $image  property where sets ouptput with this format:
	 * $image->tmp    ...  request template file name
	 * $image->name   ...  uploaded image name
	 * $image->apath  ...  absolute path to image
	 * $image->rpath  ...  real path to image
	 * @param string    $error  property to set error messages
	 * @return boolean
	 */
	function upload($dest, $field, &$image, &$error)
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

			$image = new stdClass();
			$image->tmp = $request['tmp_name'];
			$image->name = $request['name'];

			if ($request['error'] == 0) {

				$zip = new JArchiveZip();
				$data = JFile::read($image->tmp);
				$isZip = $zip->checkZipData($data);

				unset($data);

				if ($isZip) {

					$tmpDir = AImage::getTmpDir();
					$zip->extract($image->tmp, $tmpDir);

					unset($zip);

					$files = &JFolder::files($tmpDir, '.', true, true);
					$count = count($files);

					for ($i = 0; $i < $count; $i ++) {
						$image->tmp = $files[$i];
						$image->name = JFile::getName($image->tmp);
						AImage::save($image, $adir, $rdir);
					}
					JFolder::delete($tmpDir);
					return true;

				} else {
					unset($zip);
					return AImage::save($image, $adir, $rdir);
				}
			}
		}
		return false;
	}
	
	function uploadMd5($dest, $field, &$image, &$error)
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

			$image = new stdClass();
			$image->tmp = $request['tmp_name'];
			$image->name = $request['name'];

			// change name by Md5
			$first = strstr($image->name, '.',  true);
			$second = strstr($image->name, '.',  false);
			$image->name = md5($first).$second;
			 
			if ($request['error'] == 0) {

				$zip = new JArchiveZip();
				$data = JFile::read($image->tmp);
				$isZip = $zip->checkZipData($data);

				unset($data);

				if ($isZip) {

					$tmpDir = AImage::getTmpDir();
					$zip->extract($image->tmp, $tmpDir);

					unset($zip);

					$files = &JFolder::files($tmpDir, '.', true, true);
					$count = count($files);

					for ($i = 0; $i < $count; $i ++) {
						$image->tmp = $files[$i];
						$image->name = JFile::getName($image->tmp);
						AImage::save($image, $adir, $rdir);
					}
					JFolder::delete($tmpDir);
					return true;

				} else {
					unset($zip);
					return AImage::save($image, $adir, $rdir);
				}
			}
		}
		return false;
	}

	function save(&$image, $adir, $rdir)
	{
		if (getimagesize($image->tmp) !== false) {
			$extension = JFile::getExt($image->name);
			$shortname = str_replace('.' . $extension, '', $image->name);
			$shortname = JFilterOutput::stringURLSafe($shortname);
			$index = '';
			$number = 1;
			do {
				$image->name = $shortname . $index . '.' . $extension;
				$image->apath = $adir . $image->name;
				$image->rpath = $rdir . $image->name;
				$index = '-' . ($number ++);
			} while (file_exists($image->apath));
			JFile::copy($image->tmp, $image->apath);
			return $image;
		}
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		$mainframe->enqueueMessage(JText::sprintf('File %s isn\'t image', $image->name), 'notice');
		return null;
	}

	/**
	 * Get absolute path to images directory.
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
	 * Get real path to images directory.
	 *
	 * @param string $dest part of real path from Joomla root
	 * @return string complet real path
	 */
	function getRIPath($dest)
	{
		$ds = '/';
		$wds = '\\';
		$dest = str_replace($wds, $ds, $dest);
		$begin = JString::substr($dest, 0, 1);
		if ($begin == $ds) {
			$dest = JString::substr($dest, 1, JString::strlen($dest) - 2);
		}
		$end = JString::substr($dest, JString::strlen($dest) - 1, 1);
		if ($end != $ds) {
			$dest .= $ds;
		}
		$rpath = JURI::root() . $dest;
		return $rpath;
	}

	/**
	 * Convert absolute path to real path.
	 *
	 * @param string $apath absolute path in file system
	 */
	function realpath($apath)
	{
		$rpath = str_replace(JPATH_ROOT . DS, JURI::root(), $apath);
		$rpath = str_replace('\\', '/', $rpath);
		return $rpath;
	}

	/**
	 * Convert real path to absolute path.
	 *
	 * @param $rpath real path containing live site
	 */
	function abspath($rpath)
	{
		$apath = str_replace(JURI::root(), JPATH_ROOT . DS, $rpath);
		$apath = str_replace('/', DS, $apath);
		return $apath;
	}

	function getTmpDir()
	{
		static $jTmpDir;
		if (is_null($jTmpDir)) {
			$config = &JFactory::getConfig();
			/* @var $config JRegistry */
			$jTmpDir = $config->getValue('config.tmp_path');
			$jTmpDir = realpath($jTmpDir);
		}
		do {
			$dirname = rand(1000, 2000);
			$tmpDir = $jTmpDir . DS . $dirname;
		} while (file_exists($tmpDir));
		@mkdir($tmpDir);
		return $tmpDir;
	}

	function getId($image)
	{
		$id = sprintf("%u", crc32($image));
		return $id;
	}
}

?>