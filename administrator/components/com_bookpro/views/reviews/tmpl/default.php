<?php
defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewSubjects */

JHTML::_('behavior.tooltip');

$bar = &JToolBar::getInstance('toolbar');

BookProHelper::setSubmenu(6);

JToolBarHelper::title(JText::_('Review Manager'), 'object');

JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();

JToolBarHelper::publish();
JToolBarHelper::unpublishList();


JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 7 : 10;

$titleEdit = $this->escape(JText::_('Edit Airport'));

$notFound = '- ' . JText::_('not found') . ' -';


$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>
<div class="span10">
    <form action="index.php" method="post" name="adminForm" id="adminForm">

        <table class="table table-striped" cellspacing="1">
            <thead>
                <tr>
                    <th width="1%" class="hidden-phone">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                    <th width="1%" style="min-width:55px" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                    </th>

                    <th class="title">
                        <?php echo JHTML::_('grid.sort', 'JGLOBAL_TITLE', 'title', $orderDir, $order); ?>
                    </th>
                    <th>
                        <?php echo JText::_('COM_BOOKPRO_CUSTOMERS'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_BOOKPRO_REVIEW_TYPE'); ?>
                    </th>

                    <th width="4%">
                        <?php echo JHTML::_('grid.sort', 'ID', 'id', $orderDir, $order); ?>
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
                <?php
                foreach ($this->items as $i => $item) {
                    $rankstar = JURI::root() . "components/com_bookpro/assets/images/" . $item->rank . 'star.png';
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>

                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->state, $i, 'reviews.', true, 'cb', $item->publish_up, $item->publish_down); ?>
                        </td>
                        <td>
                            <?php if ($item->checked_out) { ?>
                                <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'location.', true); ?>
                            <?php } ?>
                            <a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=review&layout=edit&id=' . $item->id); ?>">
                                <?php echo $this->escape($item->title); ?>
                                <div style="text-align: left"><img src="<?php echo $rankstar; ?>"></div>
                            </a>

                        </td>
                        <td>
                            <?php echo $item->ufirstname ?>
                        </td>
                        <td>
                            <?php echo $item->type ?>
                        </td>

                        <td class="center">
                            <?php echo (int) $item->id; ?>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
</div>

<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
<input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
<input type="hidden" name="reset" value="0"/>
<input type="hidden" name="cid[]"	value="" /> 
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="controller" value="<?php echo CONTROLLER_REVIEW; ?>"/>
<input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
<input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
<?php echo JHTML::_('form.token'); ?>
</form>	
</div>