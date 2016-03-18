<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
?>
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo htmlspecialchars($user->get('name'));  ?></a>
<div class="dropdown-menu">
	<div class="profile row-fluid" >
		<div class="col-md-12">
			<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-vertical">
				<?php if ($params->get('greeting')) : ?>
					<div class="login-greeting">
						<?php if ($params->get('name') == 0) : {
							echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
						} else : {
							echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
						} endif; ?>
					</div>
				<?php endif; ?>
				<a href="index.php?option=com_virtuemart&view=raovat" class="btn btn-primary"  ><?php echo JText::_('Quản lý đăng tin rao vặt') ?></a>
				<div class="logout-button">
					<input type="submit" name="Submit" class="btn btn-primary" value="<?php echo JText::_('JLOGOUT'); ?>" />
					<input type="hidden" name="option" value="com_users" />
					<input type="hidden" name="task" value="user.logout" />
					<input type="hidden" name="return" value="<?php echo $return; ?>" />
					<?php echo JHtml::_('form.token'); ?>
				</div>
			</form>
		</div>
	</div>
</div>

