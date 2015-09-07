<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

function showColumn($element)
{
    return ($element{0} != "_" && $element != "id" && $element != "provider_user_id");
}

$items = $this->_models[strtolower('UserMap')]->getList();
$row = JTable::getInstance('UserMap', 'Table');
$columns = array_filter(array_keys(get_object_vars($row)), "showColumn");
$avatarSettings = new JRegistry();
$avatarSettings->set('width', 50);
$avatarSettings->set('height', 50);

include_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/usermap.php');
?>

<div class="sourcecoast">
    <form action="index.php" method="post" id="adminForm" name="adminForm">
        <fieldset id="filter-bar">
            <div class="filter-search pull-left">
                <div class="btn-group pull-left">
                    <input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>"
                           class="filter-search btn-group pull-left"
                           title="<?php echo JText::_('COM_JFBCONNECT_FILTER'); ?>"
                           placeholder="<?php echo JText::_('COM_JFBCONNECT_FILTER'); ?>" />

                    <div class="btn-group pull-left">
                        <button class="btn tip" id="jfbcSubmitButton" title="<?php echo JText::_('COM_JFBCONNECT_BUTTON_GO'); ?>" onclick="this.form.submit();">
                            <?php
                            if (defined('SC30')):
                                echo '<i class="icon-search"></i>';
                            endif; //SC30
                            if (defined('SC16')):
                                echo JText::_('COM_JFBCONNECT_BUTTON_GO');
                            endif; //SC16
                            ?>
                        </button>
                        <?php
                        $resetJavascript = "document.getElementById('search').value='';";
                        foreach (array_keys($this->lists) as $key)
                        {
                            if ($key != 'search')
                            {
                                $resetJavascript .= "this.form.getElementById('" . $key . "').value='-1';";
                            }
                        }
                        $resetJavascript .= "this.form.submit();";
                        ?>
                        <button class="btn tip" id="jfbcResetButton" title="<?php echo JText::_('COM_JFBCONNECT_BUTTON_RESET'); ?>"
                                onclick="<?php echo $resetJavascript; ?>">
                            <?php
                            if (defined('SC30')):
                                echo '<i class="icon-remove"></i>';
                            endif; //SC30
                            if (defined('SC16')):
                                echo JText::_('COM_JFBCONNECT_BUTTON_RESET');
                            endif; //SC16
                            ?>
                        </button>
                    </div>
                </div>
                <div class="filter-select pull-right">
                    <?php
                    foreach (array_keys($this->lists) as $key)
                    {
                        if ($key != 'search' && $key != 'order' && $key != 'order_Dir')
                        {
                            echo $this->lists[$key];
                        }
                    }
                    ?>
                </div>
            </div>
        </fieldset>
        <table class="adminlist table table-striped">
            <thead>
            <tr>
                <th width="5"><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_ID'), 'id', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th width="20">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($items); ?>);" />
                </th>
                <th><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_JOOMLA_USER'), 'j_user_id', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_PROVIDER'), 'provider', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_FB_USER'), 'provider_user_id', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_SENT_REQUESTS'), 'sent', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_RECEIVED_REQUESTS'), 'received', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('COM_JFBCONNECT_USERMAP_TITLE_FB_APP_AUTH'), 'authorized', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('JGLOBAL_CREATED'), 'created_at', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_('JGLOBAL_MODIFIED'), 'updated_at', @$this->lists['order_Dir'], @$this->lists['order']); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="10">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <?php
            $k = 0;
            $deletedMappings = false;
            for ($i = 0, $n = count($items); $i < $n; $i++)
            {
                $row = & $items[$i];
                $checked = JHTML::_('grid.id', $i, $row->id);

                $user = JFactory::getUser($row->j_user_id);
                ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td>
                        <?php echo $row->id; ?>
                    </td>
                    <td>
                        <?php echo $checked; ?>
                    </td>
                    <td>
                        <a target="_blank" href="<?php print JRoute::_("index.php?option=com_users&view=user&task=user.edit&id=" . $row->j_user_id); ?>">
                            <?php print $user->name; ?>
                        </a>
                    </td>
                    <td>
                        <?php
                        echo '<a target="_blank" href="' . JFBCFactory::provider($row->provider)->profile->getProfileUrl($row->provider_user_id) . '">';
                        echo '<img src="' . JURI::root() . '/media/sourcecoast/images/provider/icon_' . $row->provider . '.png" />';
                        echo '</a>';
                        ?>
                    </td>
                    <td align="center">
                        <?php
                        if ($row->provider != 'linkedin')
                            echo '<img src="' . JFBCFactory::provider($row->provider)->profile->getAvatarUrl($row->provider_user_id, false, $avatarSettings) . '" />';
                        ?>
                    </td>
                    <td align="center"><?php if ($row->provider == 'facebook') : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_jfbconnect&controller=notification&task=display&fbuserfrom=' . $row->provider_user_id); ?>"><?php echo $row->sent ?></a>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td align="center">
                        <?php if ($row->provider == 'facebook') : ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_jfbconnect&controller=notification&task=display&fbuserto=' . $row->provider_user_id); ?>"><?php echo $row->received ?></a>
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td align="center">
                        <?php if ($row->provider == 'facebook')
                        {
                            if ($row->authorized)
                                echo '<img src="components/com_jfbconnect/assets/images/icon-16-allow.png" />';
                            else
                                echo '<img src="components/com_jfbconnect/assets/images/icon-16-deny.png" />';
                        }
                        else
                            echo ' - ';
                        ?>
                    </td>
                    <td><?php print $row->created_at; ?></td>
                    <td><?php print $row->updated_at; ?></td>
                </tr>
                <?php
                $k = 1 - $k;
            }
            ?>
        </table>


        <input type="hidden" name="option" value="com_jfbconnect" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="permtype" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
        <input type="hidden" name="view" value="usermap" />
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>