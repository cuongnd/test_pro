<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewReservations */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(2);

JToolBarHelper::title(JText::_('Vehicle Manager'), 'user.png');
JToolBarHelper::addNew('buses.add');
JToolBarHelper::editList('buses.edit');
JToolBarHelper::divider();
JToolBarHelper::deleteList('buses.delete', 'trash', 'Trash');

$colspan = $this->selectable ? 9 : 10;
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>
<div style="float: left;width: 80%; ">
    <form action="index.php" method="post" name="adminForm" id="adminForm">

        <div  id="filter-bar" class="filter-search fltlft form-inline">


            <input type="text" name="title" value="<?php echo $this->lists['title'] ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_BUS_NAME') ?>">
            <button onclick="this.form.submit();" class="btn">
                <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
            </button>



            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>


            <table style="width: 100%" class="adminlist table">
                <thead>
                    <tr>
                        <th width="2%">#</th>
                        <?php if (!$this->selectable) { ?>
                            <th width="2%">
                                <input type="checkbox" class="inputCheckbox" name="toggle" value="" onclick="checkAll(<?php echo $itemsCount; ?>);" />
                            </th>
                        <?php } ?>	
                        <th width="5%">
                            <?php echo JText::_('COM_BOOKPRO_BUS_STATE'); ?>
                        </th>
                        <th class="title" width="30%">
                            <?php echo JHTML::_('grid.sort', 'COM_BOOKPRO_BUS_NAME', 'title', $orderDir, $order); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_BOOKPRO_BUS_AGENT'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_BOOKPRO_BUS_CODE'); ?>
                        </th>
                        <th width="15%">
                            <?php echo JText::_('COM_BOOKPRO_BUS_SEATS'); ?>
                        </th>
                        <th width="30%">
                            <?php echo JText::_('COM_BOOKPRO_BUS_ADD_FACILITY'); ?>
                        </th>

                        <th width="4%">
                            <?php echo JHTML::_('grid.sort', 'COM_BOOKPRO_BUS_ID', 'id', $orderDir, $order); ?>
                        </th>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>">
                            <?php echo $pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot>
                <tbody>
                    <?php if (!is_array($this->items) || !$itemsCount) { ?>
                        <tr><td colspan="<?php echo $colspan; ?>"><?php echo JText::_('No items found.'); ?></td></tr>
                    <?php } else { ?>
                        <?php for ($i = 0; $i < $itemsCount; $i++) { ?>

                            <?php
                            $subject = &$this->items[$i];

                            $link = JRoute::_('index.php?option=com_bookpro&task=bus.edit&id='.$subject->id);
                            ?>

                            <tr class="row<?php echo ($i % 2); ?>">
                                <td  style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
                                <?php if (!$this->selectable) { ?>
                                    <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                                <?php } ?>
                                <td><?php echo JHtml::_('jgrid.published', $subject->state, $i, 'buses.', true, 'cb', $subject->publish_up, $subject->publish_down); ?></td>
                                <td><a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a></td>
                                <td><a href="<?php echo $link; ?>"><?php echo $subject->company; ?></a></td>
                                <td><?php echo $subject->code; ?> </td>
                                <td><?php echo $subject->seat; ?> </td>
                                <td><a class="btn" href="index.php?option=com_bookpro&view=facilities&type=bus&object_id=<?php  echo $subject->id ?>"><?php echo JText::_('COM_BOOKPRO_FACILITIES_MANAGER') ?></a> </td>
                                <td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>


            <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
            <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
            <?php $tmpl = JRequest::getCmd('tmpl'); ?>
            <?php if ($tmpl) { ?>
                <input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>"/>
            <?php } ?>	
            <input type="hidden" name="reset" value="0"/>
            <input type="hidden" name="view" value="buses"/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
            <input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
            <?php echo JHTML::_('form.token'); ?>
    </form>	
</div>