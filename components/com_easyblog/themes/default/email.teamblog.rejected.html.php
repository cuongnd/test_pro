<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

defined('_JEXEC') or die('Restricted access');
?>
<div style="color:#555;padding-bottom:20px;margin-bottom:20px">
	<?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_HELLO' );?>,
	<br /><br />
	<?php echo JText::sprintf( 'COM_EASYBLOG_NOTIFICATION_TEAMBLOG_REQUEST_REJECTED' , '<a href="' . $teamLink . '" style="font-weight:bold;color:#477fda;text-decoration:none">' . $teamName . '</a>' ); ?>
</div>