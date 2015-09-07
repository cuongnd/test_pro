<h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_POST_TRIP_TRANSFER') ?></h3>
<?php $k = 0; ?>
<table class="table table-bordered">
	<tr style="background: #444;color: #fff;">

		<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME')?>
        </th>

		<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
        </th>
		<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL')?>
        </th>
		<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1')?>
        </th>
		<th><?php echo JText::_('COM_BOOKPRO_FLIGHT_NUMBER')?>
        </th>
		<th><?php echo JText::_('COM_BOOKPRO_ARRIVAL_DATE_TIME')?>
        </th>
		<th><?php echo JText::_("COM_BOOKPRO_ACTION") ?></th>
	</tr>
    <?php foreach ($this->post_airport_transfer as $airport_transfer) { ?>
        <tr>
            <?php
					$passenger = $this->pivot_passengers [$airport_transfer->passenger_id];
					$fullname = $passenger->firstname . ' ' . $passenger->lastname;
					?>
            <td><?php echo (++$k) . '.' . $passenger->firstname . ' ' . $passenger->lastname . ' (' . ($passenger->gender ? "Male" : "Female") . ')'; ?></td>
		<td><?php echo JHtml::_('date', $passenger->birthday, "d-m-Y"); ?></td>
		<td><?php echo $passenger->email; ?></td>
		<td><?php echo $passenger->phone1; ?></td>
		<td><?php echo $airport_transfer->flightnumber; ?></td>
		<td><?php echo JFactory::getDate($airport_transfer->arrival_date_time)->format('l, j F Y h:i A'); ?></td>
		<td class="action_item"><input type="hidden" name="order_id"
			value="<?php echo $subjectOders->id ?>"> <input type="hidden"
			name="request_point"
			value="<?php echo $subjectOders->request_point ?>">
            <?php if(!$this->sendmail){ ?>
			<div class="btn-group">
				<button class="btn btn-primary dropdown-toggle"
					data-toggle="dropdown">
					Action <span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li><?php echo JHtml::link('index.php?option=com_bookpro&view=orderdetail&layout=voucher&tpl=posttriptransfer&order_id=' . $this->order->id.'&airport_transfer_id='.$airport_transfer->id, JText::_('Print voucher')); ?> </li>
					<li><a href="#">Edit</a></li>

				</ul>
			</div>
            <?php } ?>
        </td>
	</tr>
    <?php } ?>

</table>