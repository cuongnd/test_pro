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
$listOrder	= $this->escape($this->state->get('list.ordering'));
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_bookpro');
$saveOrder	= $listOrder == 'ordering';
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
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=bustrips'); ?>" method="post" name="adminForm" id="adminForm">
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
				<input type="text" name="bustrip_filter_search" id="bustrip_filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('bustrip_filter_search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_BOOKPRO_MODULES_FILTER_SEARCH_DESC'); ?>" />
			</div>
			<div class="btn-group pull-left hidden-phone">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('bustrip_filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
            <div class="filter-search btn-group pull-left">
                <?php echo $this->dfrom; ?>
            </div>
            <div class="filter-search btn-group pull-left">
                <?php echo $this->dto; ?>
            </div>
            <div class="filter-search btn-group pull-left">
                <?php echo $this->fromCountries; ?>
            </div>
            <div class="filter-search btn-group pull-left">
                <?php echo $this->toCountries; ?>
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
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_BUSTRIP_FROM', 'bustrip.from', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'parent from', 'dest_from_parent_title', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'From Country', 'from_country_name', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_BUSTRIP_TO', 'bustrip.to', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'Parent to', 'dest_to_parent_title', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'To Country', 'to_country_name', $listDirn, $listOrder); ?>
                </th>

                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_BUSTRIP_BUS', 'bus_title', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'Start time', 'bustrip.start_time', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'Duration', 'bustrip.duration', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'Roundtrip', 'bustrip.roundtrip', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_PUBLISH_DATE', 'bustrip.publish_date', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap hidden-phone" >
                    <?php echo JHtml::_('grid.sort', 'COM_BOOKPRO_UNPUBLISH_DATE', 'bustrip.unpublish_date', $listDirn, $listOrder); ?>
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
                <td colspan="16">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                <tr>
                    <td colspan="16"
                        class="emptyListInfo"><?php echo JText::_('No items found.'); ?>
                    </td>
                </tr>
            <?php

            } else {

                for ($i = 0; $i < $itemsCount; $i++) {
                    $subject = &$this->items[$i];

                    $ordering   = ($listOrder == 'ordering');
                    $canCreate  = $user->authorise('core.create',     'com_bookpro');
                    $canEdit	= $user->authorise('core.edit',		  'com_bookpro.bustrip.'.$subject->id);
                    $canCheckin = $user->authorise('core.manage',     'com_checkin') || $subject->checked_out == $user->get('id')|| $subject->checked_out == 0;
                    $canChange  = $user->authorise('core.edit.state', 'com_bookpro.bustrip.'.$subject->id) && $canCheckin;



                    ?>
                    <tr>

                        <?php if (!$this->selectable) { ?>
                            <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
                            </td>
                        <?php } ?>
                        <td class="center">
                            <div class="btn-group">
                                <?php echo JHtml::_('bustrips.state', $subject->published, $i, $canChange, 'cb'); ?>
                                <?php


                                $action = $trashed ? 'untrash' : 'trash';
                                JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'bustrips');

                                // Render dropdown list
                                echo JHtml::_('actionsdropdown.render', $this->escape("From $subject->dest_from_title to $subject->dest_to_title"));
                                ?>
                            </div>
                        </td>
                        <td>
                            <a href="index.php?option=com_bookpro&task=bustrip.edit&id=<?php echo $subject->id; ?>"><?php echo $subject->dest_from_title; ?></a>
                        </td>
                        <td>
                            <?php echo $subject->dest_from_parent_title; ?>
                        </td>
                        <td>
                            <?php echo $subject->from_country_name; ?>
                        </td>
                        <td>
                            <a href="index.php?option=com_bookpro&task=bustrip.edit&id=<?php echo $subject->id; ?>"><?php echo $subject->dest_to_title; ?></a>
                        </td>
                        <td>
                            <?php echo $subject->dest_to_parent_title; ?>
                        </td>
                        <td>
                            <?php echo $subject->to_country_name; ?>
                        </td>

                        <td>
                            <?php echo $subject->bus_title; ?>
                        </td>
                        <td>
                            <?php echo $subject->start_time; ?>
                        </td>
                        <td>
                            <?php echo $subject->duration; ?>
                        </td>
                        <td>
                            <?php echo $subject->roundtrip?'Round trip':'One Way'; ?>
                        </td>
                        <td><?php echo $subject->publish_date; ?>
                        </td>
                        <td><?php echo $subject->unpublish_date; ?>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span
                                        class="caret"></span></button>
                                <ul class="dropdown-menu">

                                    <li>
                                        <a href="index.php?option=com_bookpro&view=busrate&bustrip_id=<?php echo $subject->id ?>"><?php echo JText::_('Add & Edit buss Rate') ?></a>
                                    </li>

                                    <li>
                                        <a href="index.php?option=com_bookpro&view=addons&type=bustrip&object_id=<?php echo $subject->id ?>"><?php echo JText::_('Addon Manager') ?></a>
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
		<input type="hidden" name="view" value="bustrips" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>