<?php
/**
 *
 * Controller for the front end Orderviews
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: orders.php 6383 2012-08-27 16:53:06Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the controller framework
jimport('joomla.application.component.controller');

/**
 * VirtueMart Component Controller
 *
 * @package		VirtueMart
 */
class VirtueMartControllerOrders extends JControllerLegacy
{

	/**
	 * Todo do we need that anylonger? that way.
	 * @see JControllerLegacy::display()
	 */
	public function display($cachable = false, $urlparams = false)  {

		$format = JRequest::getWord('format','html');
		if  ($format == 'pdf') $viewName= 'pdf';
		else $viewName='orders';
		$view = $this->getView($viewName, $format);

		$this->addModelPath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_virtuemart' . DS . 'models');

		// Display it all
		$view->display();
	}
	function downloadproduct()
	{
		$orderModel = VmModel::getModel('orders');
		$orderDetails = $orderModel ->getMyOrderDetails();
		$filename=JPATH_ROOT.'/media/vmfiles/JA_Travel_j25_v2.5.0.rar';

		$file_downloadname=$orderDetails['details']['BT']->order_number;

		// For a certain unmentionable browser -- Thank you, Nooku, for the tip
		if (function_exists ( 'ini_get' ) && function_exists ( 'ini_set' )) {
			if (ini_get ( 'zlib.output_compression' )) {
				ini_set ( 'zlib.output_compression', 'Off' );
			}
		}

		// Remove php's time limit -- Thank you, Nooku, for the tip
		if (function_exists ( 'ini_get' ) && function_exists ( 'set_time_limit' )) {
			if (! ini_get ( 'safe_mode' )) {
				@set_time_limit ( 0 );
			}
		}

		$basename = @basename ( $filename );
		$filesize = @filesize ( $filename );
		$extension = strtolower ( str_replace ( ".", "", strrchr ( $filename, "." ) ) );

		while ( @ob_end_clean () )
			;
		@clearstatcache ();
		// Send MIME headers
		header ( 'MIME-Version: 1.0' );
		header ( 'Content-Disposition: attachment; filename="' . ($file_downloadname.'.'.$extension) . '"' );
		header ( 'Content-Transfer-Encoding: binary' );
		header ( 'Accept-Ranges: bytes' );

		switch ($extension) {
			case 'zip' :
				// ZIP MIME type
				header ( 'Content-Type: application/zip' );
				break;

			default :
				// Generic binary data MIME type
				header ( 'Content-Type: application/octet-stream' );
				break;
		}
		// Notify of filesize, if this info is available
		if ($filesize > 0)
			header ( 'Content-Length: ' . @filesize ( $filename ) );
			// Disable caching
		header ( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header ( "Expires: 0" );
		header ( 'Pragma: no-cache' );
		flush ();
		if ($filesize > 0) {
			// If the filesize is reported, use 1M chunks for echoing the data to the browser
			$blocksize = 1048756; // 1M chunks
			$handle = @fopen ( $filename, "r" );
			// Now we need to loop through the file and echo out chunks of file data
			if ($handle !== false)
				while ( ! @feof ( $handle ) ) {
					echo @fread ( $handle, $blocksize );
					@ob_flush ();
					flush ();
				}
			if ($handle !== false)
				@fclose ( $handle );
		} else {
			// If the filesize is not reported, hope that readfile works
			@readfile ( $filename );
		}
		exit ( 0 );
	}

}

// No closing tag
