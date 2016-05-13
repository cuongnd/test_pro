<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$doc=JFactory::getDocument();
$website = JFactory::getWebsite();
$websiteTable = JTable::getInstance('Website', 'JTable');
$websiteTable->load($website->website_id);
$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/disableedit.less');
$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/main.less');
$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/mainFrontEnd.less');
$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/plugins.less');
//$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/template_backend.less');
$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/icomoon.less');
if(JFile::exists(JPATH_ROOT. "/layouts/website/less/" . $websiteTable->source_less))
{
    $doc->addLessStyleSheetTest(JUri::root() ."/layouts/website/less/" . $websiteTable->source_less);
}

$doc->addScript(JUri::root().'/templates/sprflat/assets/js/jquery.sprFlatFrontEnd.js');

$doc->addLessStyleSheet(JUri::root() . "/media/jui_front_end/bootstrap-3.3.0/less/bootstrap.less");

$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/icons.less');
$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/assets/less/plugins.less');
$doc->addLessStyleSheetTest(JUri::root().'/components/com_users/assets/less/view_user_login.less');

JHtml::_('behavior.keepalive');
?>
<div class="login<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1>
			<?php echo $this->escape($this->params->get('page_heading')); ?>
		</h1>
	</div>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif; ?>

		<?php if ($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image') != '')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JText::_('COM_USERS_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif; ?>

	<form action="index.php?option=com_users&task=user.login" method="post" class="form-validate form-horizontal">



        <div class=login-page>
        <!-- Start #login -->
        <div id=login class="animated bounceIn"><img id=logo src="<?php echo JUri::root() ?>/" alt="sprFlat Logo">
            <!-- Start .login-wrapper -->
            <div class=login-wrapper>
                <fieldset>
                    <?php foreach ($this->form->getFieldset('credentials') as $field) : ?>
                        <?php if (!$field->hidden) : ?>
                            <div class="control-group">
                                <div class="control-label">
                                    <?php echo $field->label; ?>
                                </div>
                                <div class="controls">
                                    <?php echo $field->input; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>

                    <?php if ($this->tfa): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getField('secretkey')->label; ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getField('secretkey')->input; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
                        <div  class="control-group">
                            <div class="control-label"><label><?php echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?></label></div>
                            <div class="controls"><input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/></div>
                        </div>
                    <?php endif; ?>

                    <div class="control-group">
                        <div class="controls">
                            <button type="submit" class="btn btn-primary">
                                <?php echo JText::_('JLOGIN'); ?>
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                </fieldset>
                <div>
                    <ul class="nav nav-tabs nav-stacked">
                        <li>
                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
                                <?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
                                <?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a>
                        </li>
                        <?php
                        $usersConfig = JComponentHelper::getParams('com_users');
                        if ($usersConfig->get('allowUserRegistration')) : ?>
                            <li>
                                <a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
                                    <?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <!-- End #.login-wrapper -->
        </div>
        <!-- End #login -->
        </div>

	</form>
</div>

