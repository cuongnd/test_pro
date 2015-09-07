<div class="container-fluid">
    <?php echo $this->loadTemplate('booking_detail') ?>
    <div class="row">
        <button type="button" class="btn btn-primary btn-small " data-toggle="modal" data-target="#tour_popup_add_adhoc"><span class="im-plus"></span>Add ad-hoc item</button>
        <button type="button" class="btn btn-primary btn-small " data-toggle="modal" data-target="#popup-add_addons"><span class="im-plus"></span>Book add ons</button>
        <button type="button" class="btn btn-primary btn-small " data-toggle="modal" data-target="#popup-addpassenger"><span class="im-plus"></span>Add passenger</button>
        <button type="button" class="btn btn-primary btn-small " data-toggle="modal" data-target="#popup-addflight"><span class="im-plus"></span>Add flights</button>
    </div>
    <div class="row">
        <div class="table">
            <table class="adminlist table-striped table sortingtable">
                <thead>
                <tr>
                    <th colspan="13" style="background: #e8e8e8;">&nbsp;</th>
                </tr>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>ID
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>SERVICE NAME
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>REF DEAIL
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>SERVICE DATE
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>CUSTOMER
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>NUMBER
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>SUPPLIER
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>TOTAL PRICE
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>RECEIPTS
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>BALANCE
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>ASSIGN
                        <div class="icon-sorting" position="1"></div>
                    </th>
                    <th>ACTION
                        <div class="icon-sorting" position="1"></div>
                    </th>
                </tr>

                </thead>

                <tbody>

                <?php foreach ($this->listOrderLevel1 as $item): ?>
                    <tr>
                        <td><input type="checkbox"></td>
                        <td> <?php echo $item->hassuborder ? '<a href="index.php?option=com_bookpro&view=order&cid=' . $item->order->id . '">' . $item->order->order_number . '</a>' : $item->order->order_number; ?></td>
                        <td>
                            <a href="<?php echo $item->listOrderInfo[0]->link; ?>"><?php echo $item->listOrderInfo[0]->title; ?></a>
                        </td>
                        <td><?php echo $item->listOrderInfo[0]->code; ?></td>
                        <td><?php echo JHtml::_('date', reset($item->listOrderInfo)->serviceDate); ?>
                            <br/><?php echo JHtml::_('date', end($item->listOrderInfo)->serviceDate); ?>
                        </td>
                        <td><?php echo "{$item->customer->firstname} {$item->customer->lastname}"; ?></td>
                        <td><?php echo ((int)reset($item->listOrderInfo)->adult + (int)reset($item->listOrderInfo)->child + (int)reset($item->listOrderInfo)->infant) . " pers"; ?></td>
                        <td><?php echo reset($item->listOrderInfo)->objectInfo->supplier_company; ?> </td>
                        <td><?php echo $item->order->total; ?></td>
                        <td><?php echo $item->receipt ?></td>
                        <td><?php echo $item->balance ?></td>
                        <td><?php echo $item->order->user->name; ?></td>
                        <td>
                            <button class="btn btn-primary btn-small pull-right"><span class="im-cancel-circle delete-order"></span></button>
                        </td>
                    </tr>


                <?php endforeach; ?>

                <tr>
                    <td colspan="13" style="background: #ffffff;">&nbsp;</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
