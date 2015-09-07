<?php
//$this->a_row->booked_seat_location=str_replace(array('[', ']','"'), '', $this->a_row->booked_seat_location);
//$array_deny_select=explode(',', trim($this->a_row->booked_seat_location));

 $block_layout =json_decode($this->a_row->block_layout); 
 ?>

<div class="listseat">
	<div class="iconclose"></div>
	<div class="selectmsg"><span><?php echo JText::_('COM_BOOKPRO_SEAT_SELECT_TIPS') ?></span></div>
	<div class="formchooseseat">
		<div class="bus_name"><?php echo $this->a_row->bus_name ?></div>
		<div class="photoandyoutube">
			
		</div>
		<div class="bodybuyt">
		    <div class="control">
			 <div class="lowerlabel"></div>
			 <div class="door"></div>
			</div>
			<div class="seats">
                <div class="block_layout<?php echo $this->a_row->id ?> <?php echo rand(5, 15); ?>" id="show-block-<?php echo $this->a_row->id ?>">
             
                </div>
			</div>
		</div>
		<div class="noteseats">
			<ol class="seatsDefn">
				<li class="avaiableseat seat_seleeper"><?php echo JText::_('COM_BOOKPRO_SEAT_AVAILABLE') ?></li>
				<li class="selectedseat seat_seleeper"><?php echo JText::_('COM_BOOKPRO_SEAT_SELECTED') ?></li>
				<li class="bookedseat seat_seleeper"><?php echo JText::_('COM_BOOKPRO_SEAT_BOOKED') ?></li>
			</ol>
		</div>
		<div class="payout">
			<div class="yourseat_<?php echo $this->a_row->id?>"><span><?php echo JText::_('COM_BOOKPRO_SEAT_CHOSEN') ?></span><span class="yourseat_<?php echo $this->a_row->id?>"></span><div class="spanlistseat"></div></div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($){
    /*
    $("#show-block.block_layout<?php echo $this->a_row->block_layout_id ?>").css({
        width:($('#show-block.block_layout<?php echo $this->a_row->block_layout_id ?> .block_item').width()+10)*<?php echo $block_layout->column ?>,
        display:"lock"
        
        
    });
    */
    
    $('#show-block-<?php echo $this->a_row->id ?>').creteseat({
        row:<?php echo $block_layout->row ?>,
        areturn:<?php echo $this->return?1:0?>,
        column:<?php echo $block_layout->column ?>,
        block_type: $.parseJSON('<?php echo json_encode($block_layout->block_type) ?>'),
        seatnumber: $.parseJSON('<?php echo json_encode($block_layout->seatnumber) ?>'),
        listselected:$.parseJSON('<?php echo json_encode($this->a_row->booked_seat_location) ?>'),
        hidden_input_submit:"<?php echo $this->hidden_input_submit_name ?>",
        show_lable:'span.yourseat_<?php echo $this->a_row->id?>',
        maxselect:<?php echo ARequest::getUserStateFromRequest('adult', null, 'int') ?>,
        callbacks:{
        	 onclickseat:function(selected,areturn){
            	 
        		 if(areturn == 0){
 	                var option = '';
 	               
 	                 $.each(selected,function(index,value){
 	                     option += '<option value="'+value+'">'+value+'</option>';
 	                     
 	                	$('.passenger-seat').html(option);     
 	                 });
                 }else{
                 	var option = '';
 	                 $.each(selected,function(index,value){
 	 	                
 	                     option += '<option value="'+value+'">'+value+'</option>';
 	                     
 	                	$('.passenger-return-seat').html(option);     
 	                 });
                 } 
                  
             }
        }
    });
     $('#show-block-<?php echo $this->a_row->id ?>').creteseat('option','onclickseat');
    /*
    $('#show-block-<?php echo $this->a_row->block_layout_id ?>').creteseat('option',{
        listselected:[1,3,8]
    });
    
    $('#show-block-<?php echo $this->a_row->block_layout_id ?>').creteseat('destroy');
    */
});
</script> 
