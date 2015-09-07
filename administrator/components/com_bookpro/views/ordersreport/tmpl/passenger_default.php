<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );
$config = AFactory::getConfig ();
?>
<table class="table">
	<tbody>
		
		<tr>
			<th><?php echo JText::_('COM_BOOKPRO_TOUR_TITLE')?>
                        </th>
			<td><a
				href="<?php echo JURI::root() . 'index.php?option=com_bookpro&controller=tour&view=tour&id=' . $this->tour->id ?>"><?php echo $this->tour->title?>
                            </a></td>
		</tr>
		<tr>
			<th><?php echo JText::_('COM_BOOKPRO_TOUR_CODE')?>
                        </th>
			<td>
                            <?php echo $this->tour->code?>
                        </td>
		</tr>
		



		
	</tbody>
</table>
<?php
$i = 1;
foreach ( $this->passengers as $passenger ) {
	$this->passenger = $passenger;
	?>
<div class="row-fluid">
	<div class="table_passenger">
		<h5
			style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo $i++?>.<?php echo "$passenger->firstname  $passenger->lastname (".($passenger->gender ? "Male" : "Female") . ")"; ?>
<?php if($passenger->leader){ ?><span class="label label-warning leader"><?php echo JText::_('COM_BOOKPRO_LEADER') ?></span><?php } ?></h5>

		<table style="width: 100%" class="table table-bordered">
			<thead>
				<tr>


					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_FULL_NAME')?>
                </th>

					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY')?>
                </th>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_EMAIL')?>
                </th>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PHONE1')?>
                </th>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_ADDRESS')?>
                </th>
					<th><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT')?>
                </th>

				</tr>
			</thead>

			<tr>

				<td><?php echo "$passenger->firstname  $passenger->lastname (".($passenger->gender ? "Male" : "Female") . ")"; ?><?php if($passenger->leader){ ?><span
					class="label label-warning leader"><?php echo JText::_('COM_BOOKPRO_LEADER') ?></span><?php } ?></td>
				<td><?php echo JHtml::_('date', $passenger->birthday, "d-m-Y"); ?></td>
				<td><?php echo $passenger->email; ?></td>
				<td><?php echo $passenger->phone1; ?></td>
				<td><?php echo $passenger->address; ?></td>
				<td><?php echo $passenger->passport; ?></td>

			</tr>

		</table>
		<div class="row-fluid">

            <?php
	$this->setlayout ( 'tour' );
	
	?>
            <div class="order-overview row-fluid">
                <?php if (count($this->passenger->nonetrip)) { ?>
                    <div class="roomselected ">
                        <?php echo $this->loadTemplate("roomselected")?>
                    </div>
                <?php } ?>
                <?php if (count($this->passenger->pre_trip_acommodaton)) { ?>
                    <div class="tripprice pre_trip_acommodaton ">
                        <?php echo $this->loadTemplate("pretripprice")?>
                    </div>
                <?php } ?>
                <?php if (count($this->passenger->post_trip_acommodaton)) { ?>
                    <div class="tripprice post_trip_acommodaton ">
                        <?php echo $this->loadTemplate("posttripprice")?>
                    </div>
                <?php } ?>
                <?php if (count($this->passenger->pre_airport_transfer)) { ?>
                    <div class="triptransfer pre_airport_transfer ">
                        <?php echo $this->loadTemplate("pretriptransferprice")?>
                    </div>
                <?php } ?>
                <?php if (count($this->passenger->post_airport_transfer)) { ?>
                    <div class="triptransfer post_airport_transfer ">
                        <?php echo $this->loadTemplate("posttriptransferprice")?>
                    </div>
                <?php } ?>
                <?php if (count($this->passenger->lisaddonselected)) { ?>
                    <div class="additionnaltripprice ">
                        <?php echo $this->loadTemplate("additionnaltripprice")?>
                    </div>
                <?php } ?>

            </div>

		</div>
	</div>
<?php
}

?>
</div>

