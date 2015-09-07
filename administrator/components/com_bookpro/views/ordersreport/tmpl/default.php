<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
//JHtml::_('behavior.tooltip');
AImporter::helper('currency');
BookProHelper::setSubmenu(1);
JToolBarHelper::title(JText::_('COM_BOOKPRO_ORDERREPORT'));
JToolBarHelper::custom('export_passenger', 'filter', 'Export passenger', 'Export passenger');

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);
$pagination = &$this->pagination;
$tourtype = array(
    'nonedaytrip' => Jtext::_('COM_BOOKPRO_NONEDAYTRIP'),
    'nonedaytripprivate' => Jtext::_('COM_BOOKPRO_NONEDAYTRIPPRIVATE'),
    'nonedaytripshared' => Jtext::_('COM_BOOKPRO_NONEDAYTRIPSHARED'),
    'daytrip' => Jtext::_('COM_BOOKPRO_DAYTRIP'),
    'shared' => Jtext::_('COM_BOOKPRO_SHARED'),
    'private' => Jtext::_('COM_BOOKPRO_PRIVATE')
);
?>
<script type = "text/javascript">

    jQuery(document).ready(function($) {
        Joomla.submitbutton = function(task)
        {
            if (task == "export_passenger")
            {
                $str_cids = [];
                $('input[name="cid[]"]:checkbox:checked').each(function() {
                    $str_cids.push($(this).val());
                });

                $searchids = '&cid[]=' + $str_cids.join('&cid[]=');
                console.log($searchids);
                window.open('index.php?option=com_bookpro&view=ordersreport&layout=passenger&tpl=default' + $searchids + '&tmpl=component', "popupWindow", "width=800,height=600,scrollbars=yes");
            }
        }
    });

</script>
<div class="span10">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <fieldset id="filter-bar">
            <div class="filter-search fltlft">
                <div class="btn-group pull-left hidden-phone fltlft">
                    <?php echo $this->tours ?>
                    <?php echo JHtml::calendar($this->lists['departdate'], 'filter_departdate', 'filter_departdate', '%Y-%m-%d', 'placeholder="Depart Date" style="width: 80px" onchange="this.form.submit()"') ?>
                    <?php echo $this->orderstatus ?>
                    <?php echo $this->paystatus ?>

                </div>
            </div>
            <div class="btn-group pull-left hidden-phone fltlft">
                <button onclick="this.form.submit();" class="btn">
                    <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                </button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                <?php echo $this->pagination->getLimitBox(); ?>
            </div>
        </fieldset>			


        <table class="table-striped table">
            <thead>
                <tr>
                    <th width="1%">#</th>
                    <?php if (!$this->selectable) { ?>
                        <th width="2%"><input type="checkbox" class="inputCheckbox"
                                              name="toggle" value=""
                                              onclick="Joomla.checkAll(this);" /></th>
                        <?php } ?>
                    <th><?php echo JText::_("COM_BOOKPRO_TOUR_TITLE"); ?>
                    </th>
                    <th width="15%"><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_DEPART_DATE"), 'start', $orderDir, $order); ?>

                    <th><?php echo JText::_("Total passenger"); ?></th>
                    <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                    <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_STATUS"), 'order_status', $orderDir, $order); ?></th>
                    <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_PAY_STATUS"), 'pay_status', $orderDir, $order); ?></th>
                    <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_TOTAL"), 'total', $orderDir, $order); ?></th>

                    </th>



                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="13"><?php echo $pagination->getListFooter(); ?></td>
                </tr>
            </tfoot>
            <tbody>
                <?php if ($itemsCount == 0) { ?>
                    <tr>
                        <td colspan="13" class="emptyListInfo"><?php echo JText::_('No reservations.'); ?></td>
                    </tr>
                <?php } ?>
                <?php
                for ($i = 0; $i < $itemsCount; $i++) {
                    ?>
                    <?php $subject = &$this->items[$i]; ?>
                    <?php ?>

                    <tr class="row<?php echo $i % 2; ?>">
                        <td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
                        <?php if (!$this->selectable) { ?>
                            <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                        <?php } ?>
                            <td><?php echo $subject->tour_type=='shared' ? $subject->daytrip_tour_title : $subject->nonedaytriptour_title ?><br/><span><?php echo $subject->tour_type ?></span></td>
                        <td><?php echo JFactory::getDate($subject->departdate)->format('d-m-Y H:i:s') ?></td>

                        <td><?php echo $this->CountPassengerByOrderId($subject->id) ?></td>
                        <td><a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $subject->order_number; ?></a></td>
                        <td><?php echo OrderStatus::format($subject->order_status) ?></td>
                        <td><?php echo PayStatus::format($subject->pay_status) ?></td>
                        <td><?php echo CurrencyHelper::formatprice($subject->total) ?></td>



                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <input type="hidden" name="option" value="<?php echo OPTION; ?>" />
        <input	type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" /> 
        <input type="hidden" name="reset"	value="0" /> 
        <input type="hidden" name="cid[]"	value="" />
        <input type="hidden" name="boxchecked" value="0" /> 
        <input	type="hidden" name="filter_order" value="<?php echo $order; ?>" /> 
        <input	type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>" />
        <input type="hidden" name="controller"	value="<?php echo COM_BOOKPRO_ORDERSREPORT; ?>" />
        <?php echo JHTML::_('form.token'); ?>
    </form>

</div>
