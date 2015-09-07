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
<div style="color:#555;border-bottom:1px solid #ddd;padding-bottom:20px;margin-bottom:20px">
	<?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_HELLO' ) . ' ' . $fullname; ?>,
	<br /><br />
	<?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_SUBSCRIBED_TO' ); ?> <?php echo JText::sprintf( 'COM_EASYBLOG_NOTIFICATION_SUBSCRIBE_' . strtoupper( $type ), $target ); ?> <?php echo $targetlink; ?> 
	<br /><br />
	<?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_SUBSCRIBE_CONFIRMATION_NOTICE' ); ?>
</div>