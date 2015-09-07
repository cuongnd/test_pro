<?php
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency');
$config=AFactory::getConfig();

?>
<table class="bus-list returnbus">
<thead>
		<tr>
			<th style="width: 30%;"><a class="agent" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_AGENT_COMPANY')?></a>
			</th>
			<th style="width: 35%;"><a class="datime" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_DATE_TIME')?></a>
			</th>
			
			<th><a class="price" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_PRICE')?></a>
			</th>
		</tr>
	</thead>
		<tbody>
		<?php if (count($this->return_trips)==0) { ?>
			<tr><td colspan="6"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_NOT_FOUND')?></td></tr>
		<?php }?>
		<?php if($this->return_trips):?>
	
		<?php 
		
		$i=1; foreach($this->return_trips as $row):
			
			$offset=0;
			$stations=$row->stations;
			
			$first = reset($stations);
			$last=end($stations);
			$depart_time=JFactory::getDate(JFactory::getDate($this->cart->end)->format('Y-m-d').' '.$first->start_time)->getTimestamp();
		
			$shift=$config->offset_time?$config->offset_time:1800;
			$str_current_date=JHtml::_('date','now','Y-m-d H:i:s');
			$offset= JFactory::getDate($str_current_date)->getTimestamp() +(int)$shift;
			
			//echo $row->booked_seat_location;
			if($offset < $depart_time):
		?>
		<tr class="busitem <?php echo $row->seat_layout ?>">			
			<td valign="top"><span class="bus_title"><?php echo $row->brandname?> </span>
			<?php echo $row->bus_name ?>
			 
			</td>
			
			<td valign="top">
			<div id="journey_sum">
			<?php $layout = new JLayoutFile('station', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts'); 
					$html = $layout->render($row);
					echo $html;
					?>
             </div>           
			</td>
			
			<td class="price">			
			<?php 
			$price = $row->price;
			
			 ?> <input type="radio" class="radio_bus" id="return_bustrip<?php echo $i?>" name="return_bustrip_id" value="<?php echo $row->id?>" /> 
				<?php 
				$layout = new JLayoutFile('price', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
				$html = $layout->render($price);
				echo $html;
				?>
				<div class="viewseat plusimage btn btn-success"><?php echo JText::_('COM_BOOKPRO_VIEW_SEAT')?></div>
				
			</td>
			
		</tr>
		<tr class="tr_viewseat" style="display: none">
			<td colspan="3">
				<?php $this->a_row=$row;
					$this->return = 1;
				?>
				<?php $this->hidden_input_submit_name="returnlistseat_".$this->a_row->id;?>
                <?php echo $this->loadTemplate('block')?>
			</td>
		</tr>
		<?php endif;?>
		<?php $i++; endforeach;?>
	<?php endif;?>
	</tbody>
	
</table>

<script type="application/javascript">
 
jQuery(document).ready(function() {
	jQuery("a.return_detail").live("click", function(e){
		jQuery(this).next('div.return_detail').toggle();
	}); 
	jQuery("a.return_detail_journey").live("click", function(e){
		jQuery(this).next('div.return_detail_journey').toggle();
	}); 
});
</script>
