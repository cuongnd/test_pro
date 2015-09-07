<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$document=  JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_bookpro/assets/css/view-orders.css');
// load tooltip behavior
//JHtml::_('behavior.tooltip');
AImporter::helper('currency');
BookProHelper::setSubmenu(1);
//JToolBarHelper::addNew();
$bar = JToolBar::getInstance('toolbar');
//JToolBarHelper::editList();
//JToolBarHelper::custom('export_passenger', 'filter', 'Export passenger', 'Export passenger');
JToolBarHelper::deleteList('', 'trash', 'Trash');
JToolBarHelper::title(JText::_('COM_BOOKPRO_ORDER_LIST')); 

$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];

$itemsCount = count($this->items);
$pagination = &$this->pagination;
?>

<div class="span10">
   <div class="widgetbookpro-loading"></div>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <fieldset id="filter-bar">
            <div class="filter-search fltlft">
                <div class="btn-group pull-left hidden-phone fltlft">
                    <?php echo JHtml::calendar($this->lists['from'], 'filter_from', 'filter_from', '%Y-%m-%d', 'placeholder="From date" style="width: auto"') ?>
                    <?php echo JHtml::calendar($this->lists['to'], 'filter_to', 'filter_to', '%Y-%m-%d', 'placeholder="From date" style="width: auto"') ?>
                    <?php //echo $this->orderstatus ?>
                    <?php echo $this->paystatus ?>
                    <?php //echo $this->getOrderTypeSelect($this->lists['type']) ?>
                    <?php //echo $this->tour_type ?>

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


            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th width="1%">#</th>
                        <?php if (!$this->selectable) { ?>
                            <th width="2%"><input type="checkbox" class="inputCheckbox"
                                                  name="toggle" value=""
                                                  onclick="Joomla.checkAll(this);" /></th>
                            <?php } ?>
                        
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                       
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_TATUS"), 'order_status', $orderDir, $order); ?></th>
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_TOTAL"), 'total', $orderDir, $order); ?></th>
                        <th><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_PAY_STATUS"), 'pay_status', $orderDir, $order); ?></th>
                        <th width="20%"><?php echo JHTML::_('grid.sort', JText::_("COM_BOOKPRO_ORDER_CREATED_DATE"), 'created', $orderDir, $order); ?>
                        </th>
                        <th><?php echo JText::_("COM_BOOKPRO_ACTION") ?></th>

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
                    <?php for ($i = 0; $i < $itemsCount; $i++) {

                    	$model=new BookProModelOrder();
                        $subject = &$this->items[$i];
                        $tour=$model->getObjectTourByOrderID($subject->id);

                        ?>
                        

                        <tr class="row<?php echo $i % 2; ?>">
                            <td style="text-align: right; white-space: nowrap;"><?php echo number_format($pagination->getRowOffset($i), 0, '', ' '); ?></td>
                            <?php if (!$this->selectable) { ?>
                                <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                            <?php } ?>
                            
                            <td><a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=orderflight&cid[]='.$subject->id); ?>"><?php echo $subject->order_number; ?></a><br/>
                            <a href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->user_id)); ?>"><?php echo $subject->ufirstname; ?></a>
                            </td>
                           
                            <td><?php echo OrderStatus::format($subject->order_status) ?><br/>
                            
                            <?php if($subject->order_status=='CONFIRMED'  || $subject->order_status=='FINISHED'){ ?>
                            	<label class="label label-info"><?php echo $subject->username ?></label>
                            	<?php } else { ?>
                            	<label class="label"><?php echo "Not assign" ?></label>
                            	<?php } 	?>
                            </td>
                            <td><?php echo CurrencyHelper::formatprice($subject->total) ?><br/>
                            <?php echo JText::_("COM_BOOKPRO_ORDER_DISCOUNTED"); ?>:<?php echo CurrencyHelper::formatprice($subject->discount) ?><br/>
                            
                            <div class="td-request-point">  <input type="hidden" name="order_id" value="<?php echo $subject->id ?>">
                                <?php if ($subject->request_point == 1) { ?><input type="button" name="allow_convert" class="btn btn-mini btn-success request-point" value="Requested point"><?php } ?></td>
                            
                             </div>
                            <td><?php echo PayStatus::format($subject->pay_status); ?><br/>
                            
                            </td>
                            <td><?php echo JFactory::getDate($subject->created)->format('d-m-Y H:i:s') ?></td>

                            
                            <td>
                                <div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo JRoute::_(ARoute::view('paylogs', null, null, array('order_id' => $subject->id))); ?>">Log Lists</a></li>
                                        <li><a href="<?php echo JRoute::_(ARoute::view('paylog', null, null, array('order_id' => $subject->id, 'layout' => 'edit'))); ?>">Log Add</a></li>
                                    </ul>
                                </div>

                            </td>

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
        <input type="hidden" name="controller"	value="<?php echo CONTROLLER_ORDER; ?>" />
        <?php echo JHTML::_('form.token'); ?>
    </form>

</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', '.td-request-point input.request-point', function() {

            ajax_request_point($(this));
        });
        function ajax_request_point($this_tag)
        {
            $td_request_point = $this_tag.closest('div.td-request-point');
            $order_id = $td_request_point.find('input[name="order_id"]').val();
             $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'order',
                        task: 'ajax_allow_convert_point_to_money',
                        order_id: $order_id
                    }
//                    $data = $.param($data);
//                    $data1 = $('.frontTourForm.children_acommodation *').serialize();
//
//                    $data = $data + '&' + $data1;
//                    console.log($data);
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                }
            });
            $this_tag.hide();
            
           

        }
    });
</script>
