<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$list_category = $this->items;

$children_category = array();
foreach ($list_category as $category) {
    $pt = $category->parent_id;
    $pt = ($pt == '' || $pt == $category->id) ? 'list_root' : $pt;
    $list = @$children_category[$pt] ? $children_category[$pt] : array();
    array_push($list, $category);
    $children_category[$pt] = $list;
}
$list_root_category = $children_category['list_root'];
$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$canOrder = $user->authorise('core.edit.state', 'com_websitetemplatepro');
$saveOrder = $listOrder == 'ordering';
if ($saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_websitetemplatepro&task=listtemplatecategory.saveOrderAjax&tmpl=component';
    JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
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
<?php echo $this->render_toolbar() ?>
<div class="view-listtemplatecategory-default">

    <form action="<?php echo JRoute::_('index.php?option=com_websitetemplatepro&view=listtemplatecategory'); ?>"
          method="post"
          name="adminForm" id="adminForm">
        <div id="main-container">
            <?php if (!empty($this->sidebar)) : ?>
                <?php echo $this->sidebar; ?>
            <?php endif; ?>
            <?php
            echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            ?>

            <div class="clearfix"></div>
            <table class="table table-striped" id="itemList">
                <thead>
                <tr>
                    <th width="1%" class="nowrap center hidden-phone">
                        <?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
                    </th>
                    <th width="1%" class="hidden-phone">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="1%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JSTATUS', 'enabled', $listDirn, $listOrder); ?>
                    </th>
                    <th class="title">
                        <?php echo JHtml::_('grid.sort', 'title', 'title', $listDirn, $listOrder); ?>
                    </th>
                    <th class="title">
                        <?php echo JText::_('Action') ?>
                    </th>
                    <th width="1%" class="nowrap center hidden-phone">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
                    </th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <td colspan="12">

                    </td>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach ($list_root_category as $i => $category) :
                    $render_category_item = function ($item, $i = 0, $level = 0) {
                        ob_start();
                        ?>
                        <tr class="row<?php echo $i % 2; ?>" item-id="<?php echo $item->id ?>"
                            sortable-group-id="<?php echo $item->folder ?>">
                            <td class="order nowrap center hidden-phone">


                            </td>
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="center">

                            </td>
                            <td>
                                <a class="quick-edit-title"
                                   href="<?php echo JRoute::_('index.php?option=com_websitetemplatepro&task=category.edit&id=' . (int)$item->id); ?>">
                                    <?php echo str_repeat('---', $level) . $item->category_name; ?></a>
                            </td>
                            <td>

                            </td>
                            <td class="center hidden-phone">
                                <?php echo (int)$item->id; ?>
                            </td>
                        </tr>
                        <?php
                        $html = ob_get_clean();
                        return $html;

                    };
                    echo $render_category_item($category, $i);
                    $render_categories = function ($function_callback, $category_id = 0, $children_category = array(), $list_category = array(), $render_category_item, $i, $level = 0, $max_level = 9999) {
                        $category = $list_category[$category_id];
                        $level1 = $level + 1;
                        if (count($children_category[$category_id])) {
                            foreach ($children_category[$category_id] as $category) {
                                echo $render_category_item($category, $i, $level1);
                                $category_id1 = $category->id;
                                $function_callback($function_callback, $category_id1, $children_category, $list_category, $render_category_item, $i, $level1, $max_level);
                            }
                        }
                    };
                    $render_categories($render_categories, $category->id, $children_category, $list_category, $render_category_item, $i);
                endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
