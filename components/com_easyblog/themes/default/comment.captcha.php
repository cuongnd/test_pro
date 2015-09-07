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
<div class="form-row mtm"><label class="label" for="captcha-response"><?php echo JText::_('COM_EASYBLOG_CAPTCHA_COMMENTS_TITLE'); ?></label></div>
<div class="form-row mbm">
	<img id="captcha-image" class="float-l" src="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&controller=captcha&tmpl=component&no_html=1&captcha-id=' . $id );?>" />
	<input type="text" name="captcha-response" id="captcha-response" class="float-l inputbox mlm" maxlength="5" />
	<div class="clear"></div>
	<a href="javascript:eblog.captcha.reload();"><?php echo JText::_( 'COM_EASYBLOG_CAPTCHA_RELOAD' );?></a>
	<input type="hidden" name="captcha-id" id="captcha-id" value="<?php echo $id;?>" />
</div>