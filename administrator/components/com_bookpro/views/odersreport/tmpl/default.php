<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
//JHtml::_('behavior.tooltip');
AImporter::helper('currency');
BookProHelper::setSubmenu(1);
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::custom('export_passenger', 'filter', 'Export passenger', 'Export passenger');
JToolBarHelper::deleteList('', 'trash', 'Trash');

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        Joomla.submitbutton = function(task)
        {
            if (task == "export_passenger")
            {
                $str_cids = [];
                $('input[name="cid[]"]:checkbox:checked').each(function() {
                    $str_cids.push($(this).val());
                });

                $searchids = '&cid[]='+$str_cids.join('&cid[]=');
                console.log($searchids);
                window.open('index.php?option=com_bookpro&view=orders&layout=passenger&tpl=default'+$searchids+'&tmpl=component', "popupWindow", "width=800,height=600,scrollbars=yes");
            }
        }
    });

</script>
<div class="span10">
    <h2 class="titlePage"><?php echo JText::_('COM_BOOKPRO_ORDER_LIST'); ?></h2>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <fieldset id="filter-bar">
            <div class="filter-search fltlft">
                <div class="btn-group pull-left hidden-phone fltlft">
                    <?php echo JHtml::calendar($this->lists['from'], 'filter_from', 'filter_from', '%Y-%m-%d', 'placeholder="From date" style="width: auto"') ?>
                    <?php echo JHtml::calendar($this->lists['to'], 'filter_to', 'filter_to', '%Y-%m-%d', 'placeholder="From date" style="width: auto"') ?>
                    <?php //echo $this->orderstatus ?>
                    <?php echo $this->paystatus ?>
                    <?php echo $this->getOrderTypeSelect($this->lists['type']) ?>
                    <?php echo $this->tour_type ?>

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

        <div id="editcell">

            <table class="adminlist table-striped table">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <?php if (!$this->selectable) { ?>
                            <th width="2%"><input type="checkbox" class="inputCheckbox"
                                                  name="toggle" value=""
                                                  onclick="Joomla.checkAll(this);" /></th>
                            <?php } ?>
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_CUSTOMER"), 'ufirstname', $orderDir, $order); ?>
                        </th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_TATUS"), 'order_status', $orderDir, $order); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_DISCOUNTED"); ?></th>
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_TOTAL"), 'total', $orderDir, $order); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_PAY_METHOD"); ?></th>
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_PAY_STATUS"), 'pay_status', $orderDir, $order); ?></th>
<!--			<th><?php echo JText::_("COM_BOOKPRO_ORDER_IP_ADDRESS"); ?></th>-->

                        <th width="20%"><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_CREATED_DATE"), 'created', $orderDir, $order); ?>

                        </th>
                        <th><?php echo JText::_("COM_BOOKPRO_PAYMENT_LOGS") ?></th>


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
                    <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
                        <?php $subject = &$this->items[$i]; ?>
                        <?php
                        ?>

                        <tr class="row<?php echo $i % 2; ?>">
                            <td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
                            <?php if (!$this->selectable) { ?>
                                <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                            <?php } ?>
                            <td><a href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->user_id)); ?>"><?php echo $subject->ufirstname; ?></a></td>
                            <td><a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $subject->order_number; ?></a></td>
                            <td><?php echo $subject->order_status ?></td>
                            <td><?php echo CurrencyHelper::formatprice($subject->discount) ?></td>
                            <td><?php echo CurrencyHelper::formatprice($subject->total) ?></td>
                            <td><?php echo $subject->pay_method; ?></td>
                            <td><?php echo $subject->pay_status; ?></td>

                                            <!--			<td><?php echo $subject->ip_address; ?></td>-->

                            <td><?php echo JFactory::getDate($subject->created)->format('d-m-Y H:i:s') ?></td>
                            <td><a href="<?php echo JRoute::_(ARoute::view('paylogs', null, null, array('order_id' => $subject->id))); ?>" class="btn btn-small">Lists</a>
                                <a href="<?php echo JRoute::_(ARoute::view('paylog', null, null, array('order_id' => $subject->id, 'layout' => 'edit'))); ?>" class="btn btn-small">Add</a>
                            </td>


                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="option" value="<?php echo OPTION; ?>" />
        <input	type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" /> 
        <input type="hidden" name="reset"	value="0" /> 
        <input type="hidden" name="cid[]"	value="" />
        <input type="hidden" name="boxchecked" value="0" /> 
        <input	type="hidden" name="filter_order" value="<?php echo $order; ?>" /> 
        <input	type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>" />
        <input type="hidden" name="controller"	value="<?php echo CONTROLLER_ORDER; ?>" />
        <?php echo JHTML::_('form.token'); ?>
    </form>

</div>
