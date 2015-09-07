<h6 style="text-transform: uppercase; color: #9A0000; text-align: left">
	<?php echo JText::_('COM_BOOKPRO_ADDITIONNALTRIP')?>
</h6>

<table class="table table-bordered">

	<tr>
		<td colspan="2">
			<table style="width: 100%">
				<tr>

					<th><?php echo JText::_('#')?></th>
					<th><?php echo JText::_('COM_BOOKPRO_TITLE')?></th>
				</tr>
				<?php $m = 0; ?>
				<?php foreach ($this->passenger->lisaddonselected as $addonselected) { ?>
				<tr>
					<td><?php echo ++$m?></td>
					<td><?php echo $addonselected->title?></td>

				</tr>
				<?php } ?>
			</table>
		</td>
	</tr>

</table>