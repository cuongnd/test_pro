<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/9/2015
 * Time: 2:57 PM
 */
defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$input = $app->input;
JHTML::_('behavior.tooltip');

JToolBarHelper::title(JText::_('COM_BOOKPRO_DOCUMENT_MANAGER'), 'object');

AImporter::helper('route', 'bookpro', 'request', 'touradministrator');

$colspan = $this->selectable ? 7 : 10;
$notFound = '- ' . JText::_('not found') . ' -';
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>
<div class="span10">
    <form action="<?php echo JRoute::_('index.php?option=com_bookpro&view=promotions&obj_id='.JFactory::getApplication()->getUserStateFromRequest('obj_id', 'obj_id', 0)); ?>" method="post" name="adminForm" id="adminForm">
        <div id="filter-bar" class="btn-toolbar">

            <div class="btn-group pull-left fltlft">
                <?php echo $this->listType; ?>
            </div>
            <div class="btn-group pull-left fltlft">
                <button onclick="this.form.submit();" class="btn">
                    <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                </button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>


        </div>
        <table class="table-striped table" >
            <thead>
            <tr>
                <th width="1%">#</th>
                <?php if (!$this->selectable) { ?>
                    <th width="1%">
                        <?php echo JHtml::_('grid.checkall'); ?>
                    </th>
                <?php } ?>
                <th width="2%">
                    <?php echo JHTML::_('grid.sort', JText::_('JSTATUS'), 'state', $orderDir, $order); ?>
                </th>
                <th class="title" width="30%">
                    <?php echo JHTML::_('grid.sort', JText::_('COM_BOOKPRO_PROMOTION_TITLE'), 'title', $orderDir, $order); ?>
                </th>

                <th style="text-align: right;" width="5%">
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
            <?php if (!is_array($this->items) || !$itemsCount && $this->tableTotal) { ?>
                <tr><td colspan="<?php echo $colspan; ?>" class="emptyListInfo"><?php echo JText::_('No items found.'); ?></td></tr>
            <?php
            } else {

                for ($i = 0; $i < $itemsCount; $i++) {

                    $subject = &$this->items[$i];
                    $link = JRoute::_(ARoute::view('promotion', null, null, array('id' => $subject->id, 'layout' => 'edit')));
                    $ipath = JUri::root() . $subject->path;
                    ?>
                    <tr>
                        <td  style="text-align: left; white-space: nowrap;"><?php echo number_format($this->pagination->getRowOffset($i), 0, '', ' '); ?></td>
                        <?php if (!$this->selectable) { ?>
                            <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                        <?php } ?>

                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $subject->state, $i, 'promotions.', true, 'cb', $subject->publish_up, $subject->publish_down); ?>
                        </td>
                        <td>
                            <a href="<?php echo $link; ?>"><?php echo $subject->title; ?></a>
                        </td>
                        <td style="text-align: right; white-space: nowrap;"><?php echo number_format($subject->id, 0, '', ' '); ?></td>
                    </tr>
                <?php
                }
            }
            ?>
            </tbody>
        </table>

        <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
        <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
        <input type="hidden" name="reset" value="0"/>
        <input type="hidden" name="cid[]"	value="" />
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="controller" value="promotion"/>
        <input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
        <?php echo JHTML::_('form.token'); ?>


    </form>
</div>