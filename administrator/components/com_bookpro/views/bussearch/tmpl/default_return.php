<?php
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency');
?>
<table class="bus-list returnbus table">
<thead>
		<tr>
			<th style="width: 40%;"><a class="agent" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_AGENT_COMPANY')?></a>
			</th>
			<th style="width: 40%;"><a class="datime" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_BUSTRIP_DATE_TIME')?></a>
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
	
		<?php $i=1; foreach($this->return_trips as $row):
			$avail=$row->seats - $row->booked_seat;
			//echo $row->booked_seat_location;
			$array_deny_select=explode(',', trim($row->booked_seat_location));
		?>
		<tr class="busitem <?php echo $row->seat_layout ?>">			
			<td valign="top"><span class="bus_title"><?php echo $row->brandname?> </span>
			<?php echo JText::sprintf('COM_BOOKPRO_BUS_TITLE_TEXT',$row->bus_name,$row->bus_seat) ?>
			 <a class="detail" href="javascript:void(0)"><?php echo Jtext::_('COM_BOOKPRO_MORE_INFO') ?></a>
               <div class="detail">
                	<span class="bus_sum"><?php echo $row->bus_sum ?></span>
            	</div>
			</td>
			
			<td valign="top">
			<?php $layout = new JLayoutFile('station', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts'); 
					$html = $layout->render($row);
					echo $html;
					?>
			
                        
			</td>
			<td class="price">
			 <input type="radio" class="radio_bus" id="return_bustrip<?php echo $i?>"
				name="return_bustrip_id" value="<?php echo $row->id?>" /> 
				<?php 
				$price = $row->price;
				$price = $row->price;
				
				$layout = new JLayoutFile('price', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
				$html = $layout->render($price);
				echo $html;?>
				
				<div class="viewseat plusimage btn btn-success"><?php echo JText::_('COM_BOOKPRO_VIEW_SEAT')?></div>
				
			</td>
			
		</tr>
		 <tr class="tr_viewseat <?php echo $row->seat_layout ?>" style="display: none">
            <td colspan="3">
                <?php $this->a_row=$row; 
                $this->return = 1;
                ?>
                
                <?php $this->hidden_input_submit_name="returnlistseat_".$this->a_row->id;?>
                 <?php echo $this->loadTemplate('block')?>
            </td>
        </tr>
        
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
