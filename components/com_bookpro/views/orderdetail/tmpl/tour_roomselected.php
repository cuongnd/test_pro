<h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_ROOM_SELECTED') ?></h3>
<?php $k = 0 ?>
<table class="table table-bordered">
    <?php foreach ($this->list_bookroom as $bookroom) { ?>
        <tr>
            <td><b><?php echo++$k ?>.<?php echo $this->pivot_listroomtype[$bookroom[0]->roomtype_id]->title ?></b>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <table style="width: 100%">
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME') ?>
                        </th>

                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_ADDRESS') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT') ?>
                        </th>
                        <th><?php echo JText::_('COM_BOOKPRO_EXTRABED') ?>
                        </th>
                        <th><?php echo JText::_("COM_BOOKPRO_ACTION") ?></th>
                    </tr>
                    <?php
                    $m = 0;
                    foreach ($bookroom as $room) {
                        ?>
                        <tr>
                            <?php
                            $passenger = $this->pivot_passengers[$room->passenger_id];
                            $fullname = $passenger->firstname . ' ' . $passenger->lastname;
                            ?>
                            <td><?php echo ($i++) . '.' . $passenger->firstname . ' ' . $passenger->lastname . ' (' . ($passenger->gender ? "Male" : "Female") . ')'; ?></td>
                            <td><?php echo JHtml::_('date', $passenger->birthday, "d-m-Y"); ?></td>
                            <td><?php echo $passenger->email; ?></td>
                            <td><?php echo $passenger->phone1; ?></td>
                            <td><?php echo $passenger->address; ?></td>
                            <td><?php echo $passenger->passport; ?></td>
                            <td style="text-align: center"><?php echo $room->extrabed?'<span class="extrabed">&nbsp;</span>':''; ?></td>
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
					<li><?php echo JHtml::link('index.php?option=com_bookpro&view=orderdetail&layout=voucher&tpl=roomselected&order_id=' . $this->order->id.'&roomtypepassenger_id='.$room->id, JText::_('Print voucher')); ?> </li>
					<li><a href="#">Edit</a></li>

				</ul>
			</div>
            <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </td>
        </tr>
    <?php } ?>

</table>