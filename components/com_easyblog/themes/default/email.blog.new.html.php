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
	<?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_HELLO' );?>,
	<br /><br />
	<?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_NEW_BLOG_POSTED' ); ?>
</div>
<div style="color:#555;float:left;display:inline-block;width:100%;padding-bottom:20px;">
	<a href="<?php echo $blogAuthorLink;?>"><img src="<?php echo $blogAuthorAvatar;?>" width="50" style="float:left;width:50px;height:auto;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;" /></a>
	<div style="margin-left:60px">
		<a href="<?php echo $blogLink;?>" style="font-weight:bold;color:#477fda;text-decoration:none;font-size:16px;line-height:20px"><?php echo $blogTitle; ?></a>
		<br />
		<span style="color:#999;font-size:11px"><?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_POSTED_BY' );?></span>
		<a href="<?php echo $blogAuthorLink;?>" style="font-weight:bold;font-size:11px;color:#477fda;text-decoration:none;"><?php echo $blogAuthor;?></a>
		<span style="color:#999;font-size:11px"><?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_ON' );?> <?php echo $blogDate;?></span>
		<div style="color:#555;font-size:12px;margin:3px 0 10px;">
			<?php echo $blogIntro; ?>
		</div>
		<a href="<?php echo $blogLink;?>" target="_blank" style="display:inline-block;padding:5px 15px;background:#fc0;border:1px solid #caa200;border-bottom-color:#977900;color:#534200;text-shadow:0 1px 0 #ffe684;font-weight:bold;box-shadow:inset 0 1px 0 #ffe064;-moz-box-shadow:inset 0 1px 0 #ffe064;-webkit-box-shadow:inset 0 1px 0 #ffe064;border-radius:2px;moz-border-radius:2px;-webkit-border-radius:2px;text-decoration:none!important"><?php echo JText::_( 'COM_EASYBLOG_NOTIFICATION_READ_MORE' );?></a>
	</div>
</div>