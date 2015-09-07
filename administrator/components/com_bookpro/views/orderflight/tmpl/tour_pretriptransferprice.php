<h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_PRE_TRIP_TRANSFER') ?></h3>
<?php $k = 0; ?>
<table class="table table-bordered">
    <tr>

        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME') ?>
        </th>

        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY') ?>
        </th>
        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL') ?>
        </th>
        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1') ?>
        </th>
        <th><?php echo JText::_('COM_BOOKPRO_FLIGHT_NUMBER') ?>
        </th>
        <th><?php echo JText::_('COM_BOOKPRO_ARRIVAL_DATE_TIME') ?>
        </th>
    </tr> 
    <?php foreach ($this->pre_airport_transfer as $airport_transfer) { ?>
        <tr>
            <?php
            $passenger = $this->pivot_passengers[$airport_transfer->passenger_id];
            $fullname = $passenger->firstname . ' ' . $passenger->lastname;
            ?>
            <td><?php echo (++$k) . '.' . $passenger->firstname . ' ' . $passenger->lastname . ' (' . ($passenger->gender ? "Male" : "Female") . ')'; ?></td>
            <td><?php echo JHtml::_('date', $passenger->birthday, "d-m-Y"); ?></td>
            <td><?php echo $passenger->email; ?></td>
            <td><?php echo $passenger->phone1; ?></td>
           <td><?php echo $airport_transfer->flightnumber; ?></td>
            <td><?php echo JFactory::getDate($airport_transfer->arrival_date_time)->format('l, j F Y h:i A'); ?></td>
        </tr>
    <?php } ?>

</table>