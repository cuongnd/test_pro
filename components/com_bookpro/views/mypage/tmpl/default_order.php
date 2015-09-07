<?php
$app=JFactory::getApplication();
$model = new BookProModelCustomer();
$model->setIdByUserId();
$customer = $model->getObject();

$cid = $customer->id;
$type=$app->input->get('type','tour');
AImporter::helper('bookpro', 'route', 'request', 'orderstatus', 'ordertype', 'paystatus');
AImporter::model('orders','order','orderinfos');
$ordersModel = new BookProModelOrders();
$lists = array("user_id" => $cid,'type'=>$type);
$ordersModel->init($lists);
$orders = $ordersModel->getData();

$model_order=new BookProModelOrder();


$this->types=$model_order->getType($type);
$infomodel = new BookProModelOrderinfos();
?>
<style type="text/css">
    table{
        background: none ;
    }
</style>
<div class="span12">
    <h2 class="titlePage"><?php echo JText::_('COM_BOOKPRO_ORDER_LIST'); ?></h2>


    <form action="index.php?option=com_bookpro&view=mypage" method="post" name="adminForm" id="adminForm">
        <div class="fillter">
            <?php echo $this->types ?>
        </div>
        <div id="editcell">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_TOUR"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_TOTAL"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_PAY_STATUS"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_CREATED_DATE"); ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ORDER_CANCELLATION") ?></th>
                        <th><?php echo JText::_("COM_BOOKPRO_ACTION") ?></th>
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
                           <?php

                           $tour=$model_order->getObjectTourByOrderID($subjectOders->id);
                           $infomodel->init(array('order_id' => $subjectOders->id));
                           $orderinfo = $infomodel->getData();
                           $orderinfo=$orderinfo[0];
                           $now=JFactory::getDate();
                           $day=JFactory::getDate($orderinfo->start)->diff($now);
                           ?>
                            <td><a href="index.php?option=com_bookpro&controller=order&task=detail&order_id=<?php echo $subjectOders->id ?>"> <?php echo $subjectOders->order_number ?></a></td>
                            <td><?php if ($tour ->title)echo $tour->title;
                            else echo "Customize" ;?></td>
                            <td><?php    echo CurrencyHelper::formatprice($subjectOders->total)  ?></td>
                            <td> <?php echo PayStatus::format($subjectOders->pay_method) ?></td>
                            <td><?php echo   JFactory::getDate($subjectOders->created)->format('d-m-Y H:i:s')  ?></td>
                            <td>
                            <?php
                            if($subjectOders->order_status=='CANCELLED'){?>
                            	<?php echo JText::_('COM_BOOKPRO_ORDER_CANCEL_REQUESTED')?>
                            <?php }elseif($day->days>$tour->cancel_day){?>
                            	<a href="index.php?option=com_bookpro&controller=order&task=cancel_order&order_id=<?php echo $subjectOders->id?>" class="btn btn-small" ><?php echo JText::_('Cancel')?></a>
                            	<?php }else{?>
                            		<?php echo JText::_('COM_BOOKPRO_ORDER_CANCEL_OVERTIME')?>
                            	<?php }?>
                            </td>
                            <td class="action_item">
                                <input type="hidden" name="order_id" value="<?php echo $subjectOders->id ?>">
                                <input type="hidden" name="request_point" value="<?php echo $subjectOders->request_point ?>">
                                <div class="btn-group">
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><?php echo JHtml::link('index.php?option=com_bookpro&view=mypage&form=paylogs&order_id=' . $subjectOders->id, JText::_('List payment log')); ?> </li>
                                        <?php if ($subjectOders->pay_status == 'SUCCESS' && $subjectOders->order_status == 'FINISHED') { ?> <li><a class="conver_point_to_money" href="javascript:void(0)"><?php echo $subjectOders->request_point ? JText::_('COM_BOOKPRO_UNREQUEST_POINT') : JText::_('COM_BOOKPRO_REQUEST_POINT') ?></a></li> <?php } ?>
                                        <li><a href="#">Edit</a></li>

                                    </ul>
                                </div>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="option" value="com_bookpro" />
        <input	type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" />
        <input type="hidden" name="reset"	value="0" />
        <input type="hidden" name="cid[]"	value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="view" value="mypage" />

        <input	type="hidden" name="filter_order" value="<?php echo $order; ?>" />
        <input	type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>" />
        <input type="hidden" name="controller"	value="mypage" />
        <?php echo JHTML::_('form.token'); ?>
    </form>

</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', 'a.conver_point_to_money', function() {
            ajax_requestpoint($(this));
        });
        function ajax_requestpoint($tag_this)
        {
            $action_item = $tag_this.closest('td.action_item');
            $order_id = $action_item.find('input[name="order_id"]').val();
            $request_point = $action_item.find('input[name="request_point"]').val();
            $action_item.find('input[name="request_point"]').val(1 - $request_point);
            $request_point = $action_item.find('input[name="request_point"]').val();
            console.log($request_point);
            $tag_this.html(($request_point == 1 ? '<?php echo JText::_('COM_BOOKPRO_UNREQUEST_POINT') ?>' : '<?php echo JText::_('COM_BOOKPRO_REQUEST_POINT') ?>'));
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