<?php
/**
 * @package    Bookpro
 * @author        Nguyen Dinh Cuong
 * @link        http://ibookingonline.com
 * @copyright    Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version    $Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
$input = JFactory::getApplication()->input;


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;


?>

<div class="span10">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div>
            <div class="form-inline">
                <?php echo $this->dfrom; ?>
                <?php echo $this->dto; ?>
                <!--
				<button class="btn" onclick="this.form.submit();"><?php echo JText::_('COM_BOOKPRO_SEARCH'); ?></button>
				 -->
            </div>
        </div>
        <table class="table">
            <thead>
            <tr>

                <th width="1%" class="hidden-phone"><?php echo JHtml::_('grid.checkall'); ?>

                </th>
                <th width="5%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_BDSS_STATUS'), 'state', $orderDir, $order); ?>
                </th>
                <th width="5%"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_FROM'); ?>
                </th>
                <th width="5%"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_TO'); ?>
                </th>
                <th width="5%"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_BUS'); ?>
                </th>
                <th width="5%"><?php echo JText::_('COM_BOOKPRO_PUBLISH_DATE'); ?>
                </th>
                <th width="5%"><?php echo JText::_('COM_BOOKPRO_UNPUBLISH_DATE'); ?>
                </th>
                <th width="5%"><?php echo JText::_('COM_BOOKPRO_ACTION'); ?>
                </th>


                <th width="1%"><?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
                </th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td colspan="10"><?php echo $pagination->getListFooter(); ?>
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                <tr>
                    <td colspan="<?php echo $colspan; ?>"
                        class="emptyListInfo"><?php echo JText::_('No items found.'); ?>
                    </td>
                </tr>
            <?php

            } else {

                for ($i = 0; $i < $itemsCount; $i++) {
                    $subject = &$this->items[$i];
                    $isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out);
                    ?>
                    <tr>

                        <?php if (!$this->selectable) { ?>
                            <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
                            </td>
                        <?php } ?>
                        <td>
                            <?php echo JHtml::_('jgrid.published', $subject->state, $i, 'bustrips.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
                        </td>
                        <td>
                            <a href="index.php?option=com_bookpro&task=bustrip.edit&id=<?php echo $subject->id; ?>"><?php echo $subject->dest_from_title; ?></a>
                        </td>
                        <td>
                            <a href="index.php?option=com_bookpro&task=bustrip.edit&id=<?php echo $subject->id; ?>"><?php echo $subject->dest_to_title; ?></a>
                        </td>
                        <td>
                            <?php echo $subject->bus_title; ?>
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
                                        <a href="index.php?option=com_bookpro&view=busrates&bustrip_id=<?php echo $subject->id ?>"><?php echo JText::_('Buss Rate') ?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_bookpro&view=busrate&bustrip_id=<?php echo $subject->id ?>"><?php echo JText::_('Add & Edit buss Rate') ?></a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_bookpro&view=addons&bustrip_id=<?php echo $subject->id ?>"><?php echo JText::_('Addone Manager') ?></a>
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

        <input type="hidden" name="option" value="com_bookpro"/>
        <input type="hidden" name="controller" value="bustrips"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
