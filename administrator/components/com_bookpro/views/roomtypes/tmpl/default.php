<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 82 2012-08-16 15:07:10Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');


JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(2);

JToolBarHelper::title(JText::_('COM_BOOKPRO_ROOMTYPE_MANAGER'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();

JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();

JToolBarHelper::deleteList('', 'trash', 'Trash');
$colspan = $this->selectable ? 7 : 10;
$notFound = '- ' . JText::_('not found') . ' -';

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>

<div class="span10">
    <form action="index.php" method="post" name="adminForm" id="adminForm">

        <table class="adminlist table-striped table">
            <thead>
                <tr>
                    <th width="1%">#</th>
                    <?php if (!$this->selectable) { ?>
                        <th width="1%"><input type="checkbox" class="inputCheckbox"
                                              name="toggle" value=""
                                              onclick="Joomla.checkAll(this);" />
                        </th>
                    <?php } ?>
                    <th style="text-align:center" width="5%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_STATE'), 'state', $orderDir, $order); ?></th>
                    <th class="title" width="10%"><?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_ROOMTYPE_TITLE'), 'title', $orderDir, $order); ?>
                    </th>
                    <th class="title" width="10%">
                        <?php echo JText::_("COM_BOOKPRO_ROOMTYPE_MAX_PERSON"); ?>
                    </th>
                    
                    <th class="title" width="10%">
                        <?php echo JText::_("COM_BOOKPRO_ROOMTYPE_MAX_CHILDREN"); ?>
                    </th>
                    
                    <th class="title" width="10%">
                        <?php echo JText::_("COM_BOOKPRO_ROOMTYPE_MAX_EXTRA_BED"); ?>
                    </th>
                    <th class="title" width="10%">
                        <?php echo JText::_("COM_BOOKPRO_ROOMTYPE_EXTRA_BED_PRICE"); ?>
                    </th>
                    <th width="5%"><?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
                    </th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="<?php echo $colspan; ?>"><?php echo $pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>" class="emptyListInfo"><?php echo JText::_('COM_BOOKPRO_NO_ITEM_FOUND') ?>
                        </td>
                    </tr>
                    <?php
                } else {

                    for ($i = 0; $i < $itemsCount; $i++) {
                        $subject = &$this->items[$i];
                        $link = JRoute::_(ARoute::edit(CONTROLLER_ROOMTYPE, $subject->id));
                        $js = 'javascript:ListSubjects.select(' . $subject->id . ',\'' . $id_qualif . '\',\'' . $this->escape($subject->alias) . '\')';
                        $isCheckedOut = JTable::isCheckedOut($userId, $subject->checked_out);
                        ?>
                        <tr>
                            <td style="text-align: right; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?>
                            </td>
        <?php if (!$this->selectable) { ?>
                                <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
                                </td>
        <?php } ?>
                            <td class="center">
                            <?php echo JHtml::_('jgrid.published', $subject->state, $i, 'roomtypes.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
                            </td>
                            <td><a href="<?php echo $link; ?>"
                                   title="<?php echo $titleEdit; ?>"><?php echo $subject->title; ?> </a>
                            </td>
                            <td><?php echo $subject->max_person ?></td>
                            <td><?php echo $subject->max_children ?></td>
                            <td><?php echo $subject->max_extra_bed ?></td>
                            <td><?php echo $subject->extra_bed_price ?></td>
                            <td style="white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?>
                            </td>
                        </tr>
        <?php
    }
}
?>
            </tbody>
        </table>
</div>
<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
<input type="hidden" name="reset" value="0"/>
<input type="hidden" name="cid[]"	value="" /> 
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="controller" value="<?php echo CONTROLLER_ROOMTYPE; ?>"/>
<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
<?php echo JHTML::_('form.token'); ?>
</form>	
