<?php 
    $customer = AFactory::getCustomer();
    AImporter::helper('hotel','currency');
    $orderid = HotelHelper::getOrderByHotelUser($customer->id);
		
    if( count($orderid) <=0 ){     
        $orderid[]= 0;
    }

    AImporter::model('orders');
    $model = new BookProModelOrders();

    $mainframe = &JFactory::getApplication();
    $lists = array('limit'=>$mainframe->getCfg('list_limit'),'limitstart'=>0,'orders-id'=>$orderid,'order'=>'created','order_Dir'=>'DESC');
    
    $model->init($lists);
    
    $orders = $model->getFullObject();
    $pagination = &$model->getPagination();
?>
<?php
    $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
    $html = $layout->render(array());
    echo $html;
?>

<fieldset>
    <legend>
        <?php echo JText::_('COM_BOOKPRO_LATEST_ORDER')?>
    </legend>
    <form name="tourOrder" action="index.php">
        <table class="table">
            <thead>
                <tr>
                    <th><?php echo JText::_('COM_BOOKPRO_BOOKING_HOTEL_DESTINATION'); ?></th>
                    <th><?php echo JText::_('COM_BOOKPRO_BOOKING_CUSTOMER_EMAIL_MOBILE'); ?></th>
                    
                    <th><?php echo JText::_('COM_BOOKPRO_BOOKING_AMOUNT_DISCOUNT'); ?></th>
                    
                    <th><?php echo JText::_('COM_BOOKPRO_BOOKING_PAYSATUS_PAYMETHOD'); ?></th>
                    <th><?php echo JText::_('COM_BOOKPRO_BOOKING_DATE_IP'); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th colspan="5"><?php echo $pagination->getListFooter(); ?></th>
                </tr>
            </tfoot>

            <tbody>
                <?php 
                    if(count($orders)>0) {

                        foreach ($orders as $order) {
						$customer = $order->customer;
						$hotel = HotelHelper::getObjectHotelByOrder($order->id);
						$pay_status = $order->pay_status;
						if (!$order->pay_status) {
							$pay_status = JText::_('N/A');
						}
						$pay_method = $order->pay_method;
						if (!$order->pay_method) {
							$pay_method = JText::_('N/A');
						}
						?>
                        <tr>
                            <td>

                                <?php echo JHtml::link(JURI::root().'index.php?option=com_bookpro&controller=order&task=detail&order_id='.$order->id, $hotel->title,'class="cancelbt"');
                                
                                ?>
                                <div><?php echo $hotel->city_title; ?></div>
                            </td>
                            <td>
                            	<div><?php echo $customer->fullname; ?></div>
								<div><?php echo $customer->email; ?></div>
								<div><?php echo $customer->mobile; ?></div>
                            </td>
                           
                            <td align="right">
	                            <div><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_AMOUNT',CurrencyHelper::formatprice($order->total)) ?></div>
								<div><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_DISCOUNT',CurrencyHelper::formatprice($order->discount)) ?></div>
                            </td>
                            
                            <td>
                            <?php echo JText::sprintf('COM_BOOKPRO_BOOKING_SATUS_METHOD',$pay_status,$pay_method); ?>
                            </td>
                            <td>
                            	<div><?php echo JFactory::getDate($order->created)->format('d-m-Y H:i:s') ?></div>
								<?php echo $order->ip_address; ?>
                            </td>
                        </tr>
                        <?php } 
                    }
                    else {
                    ?>
                    <tr>
                        <td colspan="6"><?php echo JText::_('COM_BOOKPRO_ORDER_UNAVAILABLE') ?>
                        </td>
                    </tr>
                    <?php 
                    }
                ?>
            </tbody>
        </table>       
    </form>
</fieldset>