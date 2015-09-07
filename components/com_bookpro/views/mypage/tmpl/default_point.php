<?php
$model = new BookProModelCustomer();
$model->setIdByUserId();
$customer = $model->getObject();

$cid = $customer->id;


AImporter::model('orders');
$orderModel = new BookProModelOrders();
$lists = array("user_id" => $cid);
$orderModel->init($lists);
$orders = $orderModel->getData();
?>
<style type="text/css">
    table{
        background: none ;
    }
</style>
<div class="span10">
    <h2 class="titlePage"><?php echo JText::_('COM_BOOKPRO_ORDER_LIST'); ?></h2>
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="editcell">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_TATUS"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_TOTAL"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_PAY_METHOD"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_CREATED_DATE"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_PAYMENT_LOGS") ?></th>
                    </tr>
                </thead>
                <tfoot>

                </tfoot>
                <tbody>
                    <?php if (count($orders) == 0) { ?>
                        <tr>
                            <td colspan="13" class="emptyListInfo"><?php echo JText::_('No Order.'); ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <?php for ($i = 0; $i < count($orders); $i++) { ?>
                            <?php $subjectOders = $orders[$i]; ?>
                            <td><?php echo $subjectOders->order_number ?></td>
                            <td><?php echo $subjectOders->pay_status ?></td>
                            <td><?php echo $subjectOders->total ?></td>
                            <td><?php echo $subjectOders->pay_method ?></td>
                            <td><?php echo $subjectOders->created ?></td>
                            <td class="conver-point-to-money">  
                                <input type="hidden" name="order_id" value="<?php echo $subjectOders->id ?>">
                                <input type="hidden" name="request_point" value="<?php echo $subjectOders->request_point ?>">
                                <span><input type="button" class="btn conver_point_to_money" value="<?php echo $subjectOders->request_point ? JText::_('COM_BOOKPRO_UNREQUEST_POINT') : JText::_('COM_BOOKPRO_REQUEST_POINT') ?>"></span>

                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Add payment</a></li>
                                        <li><a href="#">View detail</a></li>
                                        <li><a href="#">Edit</a></li>
                                    </ul>
                                </div>
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
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', 'input.conver_point_to_money', function() {
            ajax_requestpoint($(this));
        });
        function ajax_requestpoint($tag_this)
        {
            $td_conver_point_to_money = $tag_this.closest('td.conver-point-to-money');
            $order_id = $td_conver_point_to_money.find('input[name="order_id"]').val();
            $request_point = $td_conver_point_to_money.find('input[name="request_point"]').val();
            $td_conver_point_to_money.find('input[name="request_point"]').val(1 - $request_point);
            $request_point = $td_conver_point_to_money.find('input[name="request_point"]').val();
            console.log($request_point);
            $tag_this.val(($request_point == 1 ? '<?php echo JText::_('COM_BOOKPRO_UNREQUEST_POINT') ?>' : '<?php echo JText::_('COM_BOOKPRO_REQUEST_POINT') ?>'));
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'order',
                        task: 'ajax_requestpoint',
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
                        display: "none",
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
        }
    });
</script>
