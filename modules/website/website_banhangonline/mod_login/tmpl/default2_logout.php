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
$doc->addStyleSheet(JUri::root().'/modules/mod_login/assets/css/style.css');
JHtml::_('behavior.keepalive');
?>


<form action = "<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method = "post" id = "login-form" class = "form-vertical">
    <div class = "btn-group heder-account">
        <?php if ($params->get('greeting')) : ?>
            <div class = "btn btn-default login-greeting lable_name">
                <?php if ($params->get('name') == 0) : {
                    echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')),'0.00$');
                } else : {
                    echo JText::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')),'0.00$');
                } endif; ?>
            </div>
        <?php endif; ?>
        <input type = "submit" name = "Submit" class = "btn btn-default" value = "<?php echo JText::_('JLOGOUT'); ?>"/>
    </div>
    <div id="popover_content_wrapper" title="Twitter Bootstrap Popover" style="display: none">
        <?php
        jimport('joomla.application.module.helper');
        $module = &JModuleHelper::getModule('mod_menu','user menu');
        echo JModuleHelper::renderModule($module);
        ?>
    </div>
    <input type = "hidden" name = "option" value = "com_users"/>
    <input type = "hidden" name = "task" value = "user.logout"/>
    <input type = "hidden" name = "return" value = "<?php echo $return; ?>"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
<script type="text/javascript">
    jQuery( document ).ready(function($) {
        $('.lable_name').popover({
            html : true,
            trigger: 'click',
            placement:'bottom',
            content: function() {
                return $('#popover_content_wrapper').html();
            }
        });
    });
</script>
<style >
    .popover
    {
        pointer-events: initial;
    }

    .popover-content {
        padding: 9px 0;
    }
</style>
