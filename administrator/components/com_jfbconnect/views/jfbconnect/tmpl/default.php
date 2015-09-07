<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.version');
jimport('sourcecoast.utilities');
jimport('sourcecoast.adminHelper');

$configModel = $this->configModel;
$autotuneModel = $this->autotuneModel;
$jfbcLibrary = $this->jfbcLibrary;
$usermapModel = $this->usermapModel;
$userCounts = $this->userCounts;
$versionChecker = $this->versionChecker;
?>
    <div class="sourcecoast">
        <div class="row-fluid">
            <div class="span12">
                <div class="span7">
                    <div class="jfbcControlIcons">
                        <h4><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_GENERAL'); ?></h4>

                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=config">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-config2-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_CONFIGURATION'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=social">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-social-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_SOCIAL'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=profiles">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-profiles-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_PROFILES'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=channels">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-channels-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_CHANNELS'); ?></span>
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="jfbcControlIcons">
                        <h4>Facebook</h4>

                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=opengraph">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-opengraph-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_OPENGRAPH'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=canvas">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-pagetab-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_CANVAS'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=request">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-requests-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_REQUESTS'); ?></span>
                                </a>
                            </div>
                        </div>

                    </div>
                    <div class="jfbcControlIcons">
                        <h4><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_TOOLS'); ?></h4>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=usermap">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-usermap-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_MENU_USER_MAP'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=autotune">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-autotune-sc.png', NULL, NULL); ?>
                                    <span><?php echo SCAdminHelper::getAutotuneControlIconText(); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="index.php?option=com_jfbconnect&view=updates">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-updates-sc.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_UPDATES'); ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <div class="icon">
                                <a href="http://www.sourcecoast.com/forums/" target="_blank">
                                    <?php echo JHTML::_('image', 'administrator/components/com_jfbconnect/assets/images/icon-48-support.png', NULL, NULL); ?>
                                    <span><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_SUPPORT'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span5">
                    <div class="well well-small">
                        <h4><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_STATISTICS'); ?></h4>

                        <?php foreach ($userCounts as $p => $count)
                        {
                            ?>
                            <div class="row-fluid">
                                <div class="span12">
                                    <img src="<?php echo JURI::root() . 'media/sourcecoast/images/provider/icon_' . $p . '.png' ?>" /> <span
                                            style="margin-left:8px; font-size:14px"><a href="index.php?option=com_jfbconnect&view=usermap&provider=<?php echo $p;?>"><?php echo $count; ?> Users</a></span>
                                    <?php if ($p == 'facebook')
                                        echo '<span style="margin-left:8px; font-size:12px"><a target="_BLANK" href="http://www.facebook.com/insights/?sk=ao_' . $jfbcLibrary->appId . '">' . JText::_('COM_JFBCONNECT_OVERVIEW_FB_INSIGHTS_LABEL') . '</a></span>';
                                    ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="well well-small">
                        <h4><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_ADDITIONAL_INFO_SUPPORT'); ?></h4>
                        <ul>
                            <li><a target="_blank"
                                   href="http://www.sourcecoast.com/jfbconnect/docs/configuration-guide"><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_JFBC_SETUP_INSTRUCTIONS'); ?></a>
                            </li>
                            <li><a target="_blank"
                                   href="http://developers.facebook.com/"><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_FB_DEVELOPER_PORTAL'); ?></a></li>
                            <li><a target="_blank"
                                   href="http://developers.facebook.com/policy/"><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_FB_PLATFORM_POLICIES'); ?></a>
                            </li>
                        </ul>

                        <p><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_CHANGELOG_DESC'); ?></p>
                    </div>
                    <div class="well well-small">
                        <h4><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_FOLLOW_US'); ?></h4>

                        <div class="row-fluid">
                            <div class="span6">
                                <p><strong><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_FOLLOW_US_FACEBOOK'); ?></strong></p>
                                <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Ffacebook.com%2Fsourcecoast&amp;width=450&amp;height=21&amp;colorscheme=light&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;send=false&amp;appId=116488908376294"
                                        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;"
                                        allowTransparency="true"></iframe>
                            </div>
                            <div class="span6">
                                <p><strong><?php echo JText::_('COM_JFBCONNECT_OVERVIEW_FOLLOW_US_TWITTER'); ?></strong></p>
                                <a href="https://twitter.com/sourcecoast" class="twitter-follow-button" data-show-count="false">Follow @sourcecoast</a>
                                <script type="text/javascript">!function (d, s, id)
                                    {
                                        var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                                        if (!d.getElementById(id))
                                        {
                                            js = d.createElement(s);
                                            js.id = id;
                                            js.src = p + '://platform.twitter.com/widgets.js';
                                            fjs.parentNode.insertBefore(js, fjs);
                                        }
                                    }(document, 'script', 'twitter-wjs');</script>
                            </div>
                            <div
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="clear: both"></div>

    <form method="post" id="adminForm" name="adminForm">
        <input type="hidden" name="option" value="com_jfbconnect" />
        <input type="hidden" name="task" value="" />
    </form>

<?php
/*$appId = $jfbcLibrary->appId;
if ($appId)
{
    $data = new stdClass();
    $data->resource = "sourcecoast_joomla";
    $data->appid = $appId;
    $data->version = $versionChecker->getInstalledVersion('com_jfbconnect', 'component');
    echo '<div><img src="https://www.facebook.com/impression.php?' . http_build_query(array('plugin' => 'featured_resources', 'payload' => json_encode($data))) . '" width="1" height="1" alt=" " /></div>';
}*/