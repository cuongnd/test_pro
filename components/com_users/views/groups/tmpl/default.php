<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$supperAdmin = JFactory::isSupperAdmin();
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$sortFields = $this->getSortFields();

JText::script('COM_USERS_GROUPS_CONFIRM_DELETE');
?>
<script type="text/javascript">
    Joomla.submitbutton = function (task) {
        if (task == 'groups.delete') {
            var f = document.adminForm;
            var cb = '';
            <?php foreach ($this->items as $i => $item):?>
            <?php if ($item->user_count > 0):?>
            cb = f['cb' +<?php echo $i;?>];
            if (cb && cb.checked) {
                if (confirm(Joomla.JText._('COM_USERS_GROUPS_CONFIRM_DELETE'))) {
                    Joomla.submitform(task);
                }
                return;
            }
            <?php endif;?>
            <?php endforeach;?>
        }
        Joomla.submitform(task);
    }
</script>
<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        }
        else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=groups'); ?>" method="post" name="adminForm"
      id="adminForm">
    <?php if (!empty($this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>
            <div id="quick-tool" class="btn-toolbar row-fluid">
                <?php if ($supperAdmin) { ?>
                    <div class="assign-website btn-group pull-left">
                        <?php echo $this->listWebsite; ?>
                    </div>
                <?php } ?>
            </div>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <input type="text" name="filter_search" id="filter_search"
                           placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>"
                           value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip"
                           title="<?php echo JHtml::tooltipText('COM_USERS_SEARCH_IN_GROUPS'); ?>"/>
                </div>
                <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i
                            class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip"
                            title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>"
                            onclick="document.id('filter_search').value='';this.form.submit();"><i
                            class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit"
                           class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable"
                           class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                    <select name="directionTable" id="directionTable" class="input-medium"
                            onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
                        <option
                            value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option
                            value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                        <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>

            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="1%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th class="left">
                        <?php echo JHtml::_('grid.sort', 'Group name', 'a.title', $listDirn, $listOrder); ?>
                    </th>
                    <th width="20%" class="center">
                        <?php echo JText::_('total user'); ?>
                    </th>
                    <th width="1%">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="6">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
                </tfoot>
                <tbody>
                <?php
                $first_group_item = array_shift($this->items);
                $children = array();
                // First pass - collect children
                foreach ($this->items as $v) {
                    $pt = $v->parent_id;
                    $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
                    $list = @$children[$pt] ? $children[$pt] : array();
                    if ($v->id != $v->parent_id || $v->parent_id != null) {
                        array_push($list, $v);
                    }
                    $children[$pt] = $list;
                }
                unset($children['list_root']);
                if (!function_exists('render_group_item_layout')) {
                    function render_group_item_layout($root_group_item_id = 0, $children, $level = 0, $max_level = 999,$index=0)
                    {
                        if ($children[$root_group_item_id] && $level < $max_level) {

                            usort($children[$root_group_item_id], function ($item1, $item2) {
                                if ($item1->ordering == $item2->ordering) return 0;
                                return $item1->ordering < $item2->ordering ? -1 : 1;
                            });
                            $level1 = $level + 1;
                            foreach ($children[$root_group_item_id] as $i => $item) {
                                $root_group_item_id1 = $item->id;
                                $title=str_repeat('<span class="gi">|&mdash;</span>', $level).$item->title;
                                ?>
                                <tr class="row<?php echo $index % 2; ?>">
                                    <td class="center">
                                        <?php echo JHtml::_('grid.id', $index, $item->id); ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo JRoute::_('index.php?option=com_users&task=group.edit&id=' . $item->id); ?>">
                                            <?php echo $title; ?></a>
                                    </td>
                                    <td class="center">
                                        <?php echo $item->user_count ? $item->user_count : ''; ?>
                                    </td>
                                    <td class="center">
                                        <?php echo (int)$item->id; ?>
                                    </td>
                                </tr>
                                <?php
                                $index++;
                                render_group_item_layout($root_group_item_id1, $children, $level1, $max_level,$index);
                            }
                        }


                    }
                }
                render_group_item_layout($first_group_item->id, $children);
                ?>

                </tbody>
            </table>
            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
