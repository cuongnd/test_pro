<?php 
defined('_JEXEC') or die('Restricted access');
?>
<table class="bus-list bus table">
	<thead>
		<tr>
			<th style="width: 40%;"><a class="agent" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_AGENT_COMPANY')?>
			</a>
			</th>
			<th style="width: 40%;"><a class="datime" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_DATE_TIME')?>
			</a>
			</th>

			<th><a class="price" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_PRICE')?>
			</a>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php if (count($this->going_trips)==0) { ?>
		<tr>
			<td colspan="6"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_NOT_FOUND')?>
			</td>
		</tr>
		<?php }?>
		<?php if($this->going_trips):?>

		<?php $i=1; foreach($this->going_trips as $row):


		$avail=$row->seats - $row->booked_seat;

		?>

		<tr class="busitem <?php echo $row->seat_layout ?>">
			<td valign="top"><span class="bus_title"><?php echo $row->brandname?>
			</span> <br /> <?php echo JText::sprintf('COM_BOOKPRO_BUS_TITLE_TEXT',$row->bus_name,$row->bus_seat) ?><br />
				
				<?php  
				$modal->id="#myModal".$i;
				$modal->title="Policy";
				$modal->content=$row->policy;
				$layout = new JLayoutFile('modal', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
				$html = $layout->render($modal);
				echo $html;
				?>
				<a href="#myModal<?php echo $i?>"  role="button" class="btn btn-mini" data-toggle="modal">Policy</a>
				
			</td>
			<?php 
			
			?>
			<td valign="top"><div id="journey_sum">
					<?php $layout = new JLayoutFile('station', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts'); 
					$html = $layout->render($row);
					echo $html;
					?>
			</td>
			<td class="price"><input type="radio" class="radio_bus"
				id="bustrip<?php echo $i?>" name="bustrip_id"
				value="<?php echo $row->id?>" /> <?php 
				$price = $row->price;
				
				$layout = new JLayoutFile('price', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
				$html = $layout->render($price);
				echo $html;
				 ?>
				<div class="viewseat plusimage btn btn-success"><?php echo JText::_('COM_BOOKPRO_VIEW_SEAT')?></div>
			</td>

		</tr>
		<tr class="tr_viewseat <?php echo $row->seat_layout ?>"
			style="display: none">
			<td colspan="3"><?php 
			$this->a_row=$row ;
			$this->return = 0;
			?> <?php $this->hidden_input_submit_name="listseat_".$this->a_row->id;?>
				<?php 


				echo $this->loadTemplate('block')?></td>
		</tr>
		<?php $i++; endforeach;?>

		<?php endif;?>
	</tbody>

</table>

