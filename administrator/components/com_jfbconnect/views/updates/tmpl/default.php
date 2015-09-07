<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');
jimport('sourcecoast.utilities');

$versionChecker = $this->versionChecker;
?>
<div class="row-fluid">
    <div class="span12">
        <h2><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_EXTENSION_CHECK');?></h2>
        <?php
        $app = JFactory::getApplication();
        $version = new JVersion();
        $versionStr = $version->getShortVersion();
        $found15Version = SCStringUtilities::startsWith($versionStr, "1.5.");
        if ($found15Version)
            $app->enqueueMessage("<?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INCORRECT_VERSION_WARN');?>", "error");
        ?>
        <div class="span12">
            <table class="table table-striped">
                <tr>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_REQ_EXTENSIONS');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_AVAILABLE');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_STATUS');?></th>
                </tr>
                <?php
                echo $versionChecker->_showVersionInfoRow('com_jfbconnect', 'component');
                //echo $versionChecker->_showVersionInfoRow('sourcecoast', 'library');
                echo $versionChecker->_showVersionInfoRow('mod_sclogin', 'module');
                echo $versionChecker->_showVersionInfoRow('authentication.jfbconnectauth', 'plugin');
                echo $versionChecker->_showVersionInfoRow('system.jfbcsystem', 'plugin');
                echo $versionChecker->_showVersionInfoRow('user.jfbconnectuser', 'plugin');
                ?>
                <tr>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_SOCIAL_EXTENSIONS');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_AVAILABLE');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_STATUS');?></th>
                </tr>
                <?php
                echo $versionChecker->_showVersionInfoRow('content.jfbccontent', 'plugin');
                echo $versionChecker->_showVersionInfoRow('mod_scsocialwidget', 'module');
                echo $versionChecker->_showVersionInfoRow('mod_jfbcsocialshare', 'module');
                ?>
                <tr>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_PROFILE_INTEGRATION');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_AVAILABLE');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_STATUS');?></th>
                </tr>
                <?php
                echo $versionChecker->_showVersionInfoRow('socialprofiles.agorapro', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.communitybuilder', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.customdb', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.easysocial', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.jomsocial', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.joomla', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.kunena', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.k2', 'plugin');
                echo $versionChecker->_showVersionInfoRow('socialprofiles.virtuemart2', 'plugin');
                ?>
                <tr>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_OPENGRAPH_PLUGINS');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_AVAILABLE');?></th>
                    <th><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_STATUS');?></th>
                </tr>
                <?php
                // This is stupid, should update the sourcecoast.php library to not echo this out:
                ob_start();
                $versionChecker->_showVersionInfoRow('opengraph.content', 'plugin');
                $versionChecker->_showVersionInfoRow('opengraph.custom', 'plugin');
                $versionChecker->_showVersionInfoRow('opengraph.easyblog', 'plugin');
                $versionChecker->_showVersionInfoRow('opengraph.easysocial', 'plugin');
                $versionChecker->_showVersionInfoRow('opengraph.jomsocial', 'plugin');
                $versionChecker->_showVersionInfoRow('opengraph.jreviews', 'plugin');
                $versionChecker->_showVersionInfoRow('opengraph.k2', 'plugin');
                $plugins = ob_get_clean();
                echo str_replace("OpenGraph - ", '', $plugins);
                ?>
            </table>
        </div>
        <div style="clear:both"></div>
        <img alt="<?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED_PUBLISHED_DESC');?>" src="components/com_jfbconnect/assets/images/icon-16-allow.png" width="10"
             height="10"/> - <?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED_PUBLISHED');?> |
        <img alt="<?php echo JText::_('COM_JFBCONNECT_OVERVIEW_INSTALLED_UNPUBLISHED_DESC');?>" src="components/com_jfbconnect/assets/images/icon-16-notice-note.png" width="10"
             height="10"/> - <?php echo JText::_('COM_JFBCONNECT_OVERVIEW_NOT_PUBLISHED');?> |
        <img alt="<?php echo JText::_('COM_JFBCONNECT_OVERVIEW_NOT_INSTALLED');?>" src="components/com_jfbconnect/assets/images/icon-16-deny.png" width="10" height="10"/> - <?php echo JText::_('COM_JFBCONNECT_OVERVIEW_NOT_INSTALLED');?>
    </div>
</div>
<div style="clear: both"></div>

<form method="post" id="adminForm" name="adminForm">
    <input type="hidden" name="option" value="com_jfbconnect"/>
    <input type="hidden" name="task" value=""/>
</form>