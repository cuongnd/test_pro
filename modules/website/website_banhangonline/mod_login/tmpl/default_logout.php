<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$doc=JFactory::getDocument();
$scriptId = "script_mod_login_logout" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mod_login_logout_<?php echo $module->id ?> .dropdown-menu').on({
            "click":function(e){
                e.stopPropagation();
            }
        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$user=JFactory::getUser();

JHtml::_('behavior.keepalive');
?>
<div id="mod_login_logout_<?php echo $module->id ?>" class="mod_login_logout pull-right mod_login_logout_<?php echo $module->id ?>">
    <a class="dropdown-toggle" data-toggle = "dropdown" href="#"><i class="im-user"></i> <?php echo $user->name ?></a>
    <div class = "dropdown-menu">
        <div class="row-fluid">
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
                    <a href="index.php?option=com_banhangonline&view=listraovat" class="btn btn-primary"  ><?php echo JText::_('Quản lý đăng tin rao vặt') ?></a>
                    <a href="index.php?option=com_virtuemart&view=gianhang&layout=edit" class="btn btn-primary"  ><?php echo JText::_('Tạo gian hàng') ?></a>
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
</div>
