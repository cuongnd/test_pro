<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bookpro
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('filter_order'));
$trashed	= $this->state->get('bus_filter_published') == -2 ? true : false;
$listDirn	= $this->escape($this->state->get('filter_order_Dir'));
$canOrder	= $user->authorise('core.edit.state', 'com_bookpro');
$saveOrder	= $listOrder == 'bus.ordering';
$user		= JFactory::getUser();
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=buses'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div  class="span2">
        <?php BookProHelper::setSubmenu(1); ?>

	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
        <div id="j-sidebar-container">
            <?php echo $this->sidebar; ?>
        </div>
		<div id="filter-bar" class="btn-toolbar">

			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo JText::_('COM_BANNERS_SEARCH_IN_TITLE');?></label>
				<input type="text" name="bus_filter_search" id="bus_filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('bus_filter_search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_BOOKPRO_MODULES_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('bus_filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>

			<div class="btn-group pull-right hidden-phone">
				<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<div class="btn-group pull-right hidden-phone">
				<label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC');?></label>
				<select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JFIELD_ORDERING_DESC');?></option>
					<option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING');?></option>
					<option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING');?></option>
				</select>
			</div>
			<div class="btn-group pull-right">
				<label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY');?></label>
				<select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
					<option value=""><?php echo JText::_('JGLOBAL_SORT_BY');?></option>
					<?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
				</select>
			</div>
		</div>
		<div class="clearfix"> </div>
        <table class="table">
            <thead>
            <tr>

                <th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>

                </th>
                <th width="5%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BDSS_STATUS'), 'state', $orderDir, $order); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'Car Name', 'bus.title', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'Image', 'bus.image', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JText::_('Action') ?>
                </th>


                <th width="1%"><?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
                </th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="5">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                <tr>
                    <td colspan="5"
                        class="emptyListInfo"><?php echo JText::_('No items found.'); ?>
                    </td>
                </tr>
            <?php

            } else {

                for ($i = 0; $i < $itemsCount; $i++) {
                    $subject = &$this->items[$i];

                    $ordering   = ($listOrder == 'ordering');
                    $canCreate  = $user->authorise('core.create',     'com_bookpro');
                    $canEdit	= $user->authorise('core.edit',		  'com_bookpro.bus.'.$subject->id);
                    $canCheckin = $user->authorise('core.manage',     'com_checkin') || $subject->checked_out == $user->get('id')|| $subject->checked_out == 0;
                    $canChange  = $user->authorise('core.edit.state', 'com_bookpro.bus.'.$subject->id) && $canCheckin;



                    ?>
                    <tr>

                        <?php if (!$this->selectable) { ?>
                            <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
                            </td>
                        <?php } ?>
                        <td class="center">
                            <div class="btn-group">
                                <?php echo JHtml::_('buses.state', $subject->published, $i, $canChange, 'cb'); ?>
                                <?php


                                $action = $trashed ? 'untrash' : 'trash';
                                JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'buses');

                                // Render dropdown list
                                echo JHtml::_('actionsdropdown.render', $this->escape($subject->title));
                                ?>
                            </div>
                        </td>
                        <td>
                            <a href="index.php?option=com_bookpro&task=bus.edit&id=<?php echo $subject->id; ?>"><?php echo $subject->title; ?></a>
                        </td>
                        <td>
                            <img style="width: 90px" src="<?php echo JUri::root().'/'.$subject->image; ?>">
                        </td>

                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span
                                        class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="index.php?option=com_bookpro&view=facilities&type=bus&object_id=<?php echo $subject->id ?>"><?php echo JText::_('Facilities Manager') ?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_bookpro&view=busrate&bus_id=<?php echo $subject->id ?>"><?php echo JText::_('Add & Edit buss Rate') ?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_bookpro&view=addons&bus_id=<?php echo $subject->id ?>"><?php echo JText::_('Addone Manager') ?></a>
                                    </li>
                                </ul>
                            </div>


                        </td>


                        <td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?>
                        </td>
                    </tr>
                <?php
                }
            }
            ?>
            </tbody>
        </table>



		<input type="hidden" name="option" value="com_bookpro" />
		<input type="hidden" name="view" value="buses" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>