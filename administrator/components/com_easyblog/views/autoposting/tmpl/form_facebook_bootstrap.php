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
<div class="row-fluid">
	<img src="<?php echo JURI::root();?>administrator/components/com_easyblog/assets/images/autoposting/facebook_setup.png" style="float:left;margin-right:20px;" />
	<h3 class="head-3"><?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_FACEBOOK' );?></h3>
	<div class="clear"></div>
</div>

<div class="row-fluid">

	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#facebook-basic" data-toggle="tab"><?php echo JText::_( 'COM_EASYBLOG_AUTOPOSTING_BASIC_SETTINGS' );?></a>
			</li>
			<li>
				<a href="#facebook-advanced" data-toggle="tab"><?php echo JText::_( 'COM_EASYBLOG_AUTOPOSTING_ADVANCED_SETTINGS' );?></a>
			</li>
		</ul>
	</div>

	<div class="tab-content">
		<div id="facebook-basic" class="tab-pane active">
			<table class="table table-striped">
				<tbody>
				<tr>
					<td width="300" class="key">
						<span><?php echo JText::_( 'COM_EASYBLOG_AUTOPOST_FACEBOOK_ENABLE' ); ?></span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_('COM_EASYBLOG_AUTOPOST_FACEBOOK_ENABLE_DESC'); ?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook' , $this->config->get( 'integrations_facebook' ) );?>
						</div>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span>
							<?php echo JText::_( 'COM_EASYBLOG_AUTOPOSTING_CENTRALIZED' ); ?>
						</span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_DESC', 'Facebook'); ?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook_centralized' , $this->config->get( 'integrations_facebook_centralized', false ) );?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span>
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID' ); ?>
						</span>
					</td>
					<td>
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_APP_ID_DESC');?></div>
							<input type="text" name="integrations_facebook_api_key" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_api_key');?>" size="60" />
							<a href="http://stackideas.com/docs/easyblog/how-tos/setting-up-facebook-integration.html" target="_blank" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span>
							<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_SECRET_KEY' ); ?>
						</span>
					</td>
					<td>
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_SECRET_KEY_DESC');?></div>
							<input type="text" name="integrations_facebook_secret_key" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_secret_key');?>" size="60" />
							<a href="http://stackideas.com/docs/easyblog/how-tos/setting-up-facebook-integration.html" target="_blank" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
						</div>
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span><?php echo JText::sprintf( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_PAGE_IMPERSONATION' ); ?></span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_PAGE_IMPERSONATION_DESC');?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook_impersonate_page' , $this->config->get( 'integrations_facebook_impersonate_page' ) );?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span><?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_PAGE_ID' ); ?></span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_PAGE_ID_DESC');?></div>
							<input type="text" name="integrations_facebook_page_id" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_page_id');?>" size="30" />
							<a href="http://stackideas.com/docs/easyblog/how-tos/setting-up-facebook-integration.html#mypage" target="_blank" style="margin-left:5px;"><?php echo JText::_('COM_EASYBLOG_WHAT_IS_THIS'); ?></a>
							<div style="margin-top: 5px;"><?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_PAGE_ID_SEPARATE' ); ?></div>
						</div>
					</td>
				</tr>



				<tr>
					<td width="300" class="key">
						<span><?php echo JText::sprintf( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_GROUP' ); ?></span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_GROUP_DESC');?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook_impersonate_group' , $this->config->get( 'integrations_facebook_impersonate_group' ) );?>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_GROUP_ID' ); ?></span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_SETTINGS_SOCIALSHARE_FACEBOOK_GROUP_ID_DESC');?></div>
							<input type="text" name="integrations_facebook_group_id" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_group_id');?>" size="30" />
						</div>
					</td>
				</tr>

				
				<tr>
					<td class="key">
						<span><?php echo JText::_('COM_EASYBLOG_AUTOPOSTING_FACEBOOK_ACCESS'); ?></span>
					</td>
					<td valign="top">
						<?php if( $this->isAssociated ){ ?>
							<div class="has-tip">
								<div class="tip"><i></i><?php echo JText::_('COM_EASYBLOG_AUTOPOSTING_FACEBOOK_ACCESS_DESC'); ?></div>
								<div style="margin-top:5px;">
									<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&c=autoposting&task=revoke&type=' . EBLOG_OAUTH_FACEBOOK . '&return=form');?>"><?php echo JText::_( 'COM_EASYBLOG_OAUTH_REVOKE_ACCESS' ); ?></a>
								</div>

								<p style="margin:15px 0 8px 0;">
									<?php echo JText::_( 'COM_EASYBLOG_FACEBOOK_EXPIRE_TOKEN');?> <strong><?php echo $this->expire; ?></strong>.
								</p>
								<a href="javascript:void(0);" class="button" id="facebook-login"><?php echo JText::_( 'COM_EASYBLOG_FACEBOOK_RENEW_TOKEN' );?></a>
							</div>
						<?php } else { ?>
							<div class="has-tip">
								<div class="tip"><i></i><?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ALLOW_ACCESS_DESC', 'Facebook'); ?></div>
								<div style="margin-bottom:10px;"><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_FACEBOOK_ACCESS_DESC');?></div>
								<a href="javascript:void(0);" id="facebook-login">
									<img src="<?php echo JURI::root();?>administrator/components/com_easyblog/assets/images/autoposting/facebook_signon.png" border="0" alt="here" />
								</a>
							</div>
						<?php } ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>

		<div id="facebook-advanced" class="tab-pane">
			<table class="admintable" cellspacing="1">
				<tbody>
				<tr>
					<td class="key">
						<span>
							<?php echo JText::_('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES'); ?>
						</span>
					</td>
					<td class="paramlist_value">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::sprintf('COM_EASYBLOG_OAUTH_ENABLE_AUTO_UPDATES_DESC', 'Facebook'); ?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook_centralized_auto_post' , $this->config->get('integrations_facebook_centralized_auto_post', false) );?>
						</div>							
					</td>
				</tr>
				<tr>
					<td class="key">
						<span>
							<?php echo JText::_('COM_EASYBLOG_OAUTH_SEND_UPDATES'); ?>
						</span>
					</td>
					<td class="paramlist_value">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::sprintf('COM_EASYBLOG_OAUTH_SEND_UPDATES_DESC', 'Facebook'); ?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook_centralized_send_updates' , $this->config->get('integrations_facebook_centralized_send_updates', false) );?>
						</div>				
					</td>
				</tr>
				<tr>
					<td class="key">
						<span>
							<?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_FROM'); ?>
						</span>
					</td>
					<td>
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_('COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_FROM_DESC'); ?></div>
							<select name="integrations_facebook_source" class="inputbox">
								<option value="intro"<?php echo $this->config->get( 'integrations_facebook_source' ) == 'intro' ?  ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYBLOG_INTROTEXT' ); ?></option>
								<option value="content"<?php echo $this->config->get( 'integrations_facebook_source' ) == 'content' ?  ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYBLOG_CONTENT' ); ?></option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td class="key">
						<span>
							<?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_LENGTH' ); ?></span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::_( 'COM_EASYBLOG_INTEGRATIONS_FACEBOOK_CONTENT_LENGTH_DESC');?></div>
							<input type="text" name="integrations_facebook_blogs_length" class="inputbox" value="<?php echo $this->config->get('integrations_facebook_blogs_length');?>" size="5" />
						</div>							
					</td>
				</tr>
				<tr>
					<td width="300" class="key">
						<span>
							<?php echo JText::sprintf( 'COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_ALLOW_USER_OWN_ACCOUNT', 'Facebook' ); ?>
						</span>
					</td>
					<td valign="top">
						<div class="has-tip">
							<div class="tip"><i></i><?php echo JText::sprintf('COM_EASYBLOG_INTEGRATIONS_CENTRALIZED_ALLOW_USER_OWN_ACCOUNT_DESC', 'Facebook'); ?></div>
							<?php echo $this->renderCheckbox( 'integrations_facebook_centralized_and_own' , $this->config->get( 'integrations_facebook_centralized_and_own', false ) );?>
						</div>							
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>