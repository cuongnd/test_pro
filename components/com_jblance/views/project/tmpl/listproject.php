<?php
/**
 * @company        :    BriTech Solutions
 * @created by    :    JoomBri Team
 * @contact        :    www.joombri.in, support@joombri.in
 * @created on    :    26 March 2012
 * @file name    :    views/project/tmpl/listproject.php
 * @copyright   :    Copyright (C) 2012. All rights reserved.
 * @license     :    GNU General Public License version 2 or later
 * @author      :    Faisel
 * @description    :    Shows list of projects (jblance)
 */
defined('_JEXEC') or die('Restricted access');
$menu = JFactory::getApplication()->getMenu();
$menu_active=$menu->getActive();
$query = new JRegistry;
$query->loadArray($menu_active->query);
$menu_item_detail_project=$query->get('menu_item_detail_project',0);
$model = $this->getModel();
$config =& JblanceHelper::getConfig();
$currencysym = $config->currencySymbol;
$currencycode = $config->currencyCode;
$dformat = $config->dateFormat;

$action = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject');
$link_search = JRoute::_('index.php?option=com_jblance&view=project&layout=searchproject');
?>
<form action="<?php echo $action; ?>" method="post" name="userForm">
    <a href="<?php echo $link_search; ?>" class="fr"><?php echo JText::_('COM_JBLANCE_SEARCH_PROJECTS'); ?></a>

    <div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_LIST_OF_PROJECTS'); ?></div>
    <table width="100%" cellpadding="0" cellspacing="0" class="border">
        <thead>
        <tr class="jbl_rowhead">
            <th>#</th>
            <th><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?></th>
            <th><?php echo JText::_('COM_JBLANCE_BIDS'); ?></th>
            <th nowrap><?php echo JText::sprintf('COM_JBLANCE_AVG_CCY', $currencycode); ?></th>
            <th><?php echo JText::_('COM_JBLANCE_STATUS'); ?></th>
            <th><?php echo JText::_('COM_JBLANCE_STARTED'); ?></th>
            <th><?php echo JText::_('COM_JBLANCE_ENDS'); ?></th>
            <th colspan="2"><?php echo JText::_('COM_JBLANCE_PUBLISHER'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="9" class="jbl_row3">
                <?php echo $this->pageNav->getListFooter(); ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <?php
        $k = 0;
        for ($i = 0, $x = count($this->rows); $i < $x; $i++) {
            $row = $this->rows[$i];
            $buyer = JFactory::getUser($row->publisher_userid);
            $daydiff = $row->daydiff;

            if ($daydiff == -1) {
                $startdate = JText::_('COM_JBLANCE_YESTERDAY');
            } elseif ($daydiff == 0) {
                $startdate = JText::_('COM_JBLANCE_TODAY');
            } else {
                $startdate = JHTML::_('date', $row->start_date, $dformat, true);
            }

            $link_proj_detail = JRoute::_('index.php?option=com_jblance&view=project&layout=detailproject&id=' . $row->id."&Itemid=$menu_item_detail_project");
            $bidsCount = $model->countBids($row->id);
            ?>
            <tr class="jbl_row<?php echo $k; ?>">
                <td><?php echo $this->pageNav->getRowOffset($i); ?></td>
                <td>
                    <a href="<?php echo $link_proj_detail; ?>"><strong><?php echo $row->project_title; ?></strong></a>

                    <div class="fr">
                        <?php if ($row->is_featured) : ?>
                            <img src="components/com_jblance/images/featured.png" alt="Featured" width="24" class=""
                                 title="<?php echo JText::_('COM_JBLANCE_FEATURED_PROJECT'); ?>"/>
                        <?php endif; ?>
                        <?php if ($row->is_urgent) : ?>
                            <img src="components/com_jblance/images/urgent.png" alt="Urgent" width="24" class=""
                                 title="<?php echo JText::_('COM_JBLANCE_URGENT_PROJECT'); ?>"/>
                        <?php endif; ?>
                        <?php if ($row->is_private) : ?>
                            <img src="components/com_jblance/images/private.png" alt="Private" width="24" class=""
                                 title="<?php echo JText::_('COM_JBLANCE_PRIVATE_PROJECT'); ?>"/>
                        <?php endif; ?>
                        <?php if ($row->is_nda) : ?>
                            <img src="components/com_jblance/images/nda.png" alt="NDA" width="24" class=""
                                 title="<?php echo JText::_('COM_JBLANCE_NDA_PROJECT'); ?>"/>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="jb-aligncenter">
                    <?php if ($row->is_sealed) : ?>
                        <img src="components/com_jblance/images/sealed.png" alt="Sealed" width="24" class=""
                             title="<?php echo JText::_('COM_JBLANCE_SEALED_PROJECT'); ?>"/>
                    <?php else : ?>
                        <?php echo $bidsCount; ?>
                    <?php endif; ?>
                </td>
                <td class="jb-aligncenter">
                    <?php
                    $projHelper = JblanceHelper::get('helper.project');        // create an instance of the class ProjectHelper
                    $avg = $projHelper->averageBidAmt($row->id);
                    $avg = round($avg, 0); ?>
                    <?php if ($row->is_sealed) : ?>
                        -
                    <?php else : ?>
                        <?php echo $currencysym . $avg; ?>
                    <?php endif; ?>
                </td>
                <td><?php echo JText::_($row->status); ?></td>
                <td nowrap class="jb-aligncenter"><?php echo $startdate; ?></td>
                <td nowrap class="jb-aligncenter">
                    <?php
                    $expiredate = JFactory::getDate($row->start_date);
                    $expiredate->modify("+$row->expires days");
                    echo JblanceHelper::showRemainingDHM($expiredate, 'SHORT');
                    ?>
                <td>
                    <?php
                    $attrib = 'width=25 height=25';
                    $avatar = JblanceHelper::getThumbnail($row->publisher_userid, $attrib);
                    echo !empty($avatar) ? LinkHelper::GetProfileLink($row->publisher_userid, $avatar) : '&nbsp;' ?>
                </td>
                <td>
                    <?php echo LinkHelper::GetProfileLink($row->publisher_userid, $this->escape($buyer->username)); ?>
                </td>
            </tr>
            <?php
            $k = 1 - $k;
        }
        ?>
        </tbody>
    </table>
    <?php
    $link_rss = JRoute::_('index.php?option=com_jblance&view=project&format=feed');
    $rssvisible = (!$config->showRss) ? 'style=display:none' : '';
    ?>
    <div class="jbrss" <?php echo $rssvisible; ?>>
        <div id="showrss" class="fr">
            <a href="<?php echo $link_rss; ?>" target="_blank">
                <img src="components/com_jblance/images/rss.png" alt="RSS"
                     title="<?php echo JText::_('COM_JBLANCE_RSS_IMG_ALT'); ?>">
            </a>
        </div>
    </div>
    <input type="hidden" name="option" value="com_jblance"/>
    <input type="hidden" name="task" value=""/>
</form>