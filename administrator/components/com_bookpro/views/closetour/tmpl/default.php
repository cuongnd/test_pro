<?php


defined('_JEXEC') or die;     


    $from = JFactory::getDate()->format('Y-m-d');
    $todate = JFactory::getDate()->format('Y-m-d');
    $to = date('Y-m-d', strtotime($todate . '7 day'));
?>
<script>       
   function validateForm(startDateId, endDateId){
        var startDate   = jQuery('#'+startDateId).val();
        var endDate     = jQuery('#'+endDateId).val();  
        var check       = true; 
        
        var day=1000*60*60*24;
        var date1= convertDateFormat(startDate);
        var date2= convertDateFormat(endDate);  
        
        date1= new Date(date1);
        date2= new Date(date2);

        var diff=Math.abs(date2.getTime()-date1.getTime())
        var diffdays=Math.round(diff/day)       

      
            if(!startDate){
                alert('From date is required field');
                check = false;
            }else if(!endDate){
                alert('To date is required field');
                check = false;
            }else if(new Date(startDate) > new Date(endDate)){
                alert('From date should be lesser than To date');
                check = false;
            }else if(diffdays > 59){
                alert('Maximum time window is 60 days');
                check = false;
            }                     
        return check;
    }
    
        function convertDateFormat(strDate)
        {
            if (trim(strDate) == '') return '';
            return strDate.replace(/-/g, "/");
        }

</script>
<form action="" method="post" id="adminForm" name="adminForm" class="form-validate" onsubmit="return validateForm('from','to')">
    <center><H2 class="center">Tour: <?php echo $this->tour->title?></h2></center>   
    <hr>
	<div class="row-fluid">
		<div class="span10 form-horizontal">
          <fieldset> 
            <div class="control-group" style="margin-bottom:18px;">
                <label for="pickup" class="control-label" style="width:120px; float:left;"><?php echo JText::_('COM_BOOKPRO_FROM'); ?></label>
                <div class="controls" style="margin-left:140px;">
                    <?php echo JHtml::calendar($from, 'from', 'from', '%Y-%m-%d', 'readonly="readonly" style="width:80px;"') ?>
                </div>
            </div>
            
            <div class="control-group" style="margin-bottom:18px;">
                <label for="pickup" class="control-label" style="width:120px; float:left;"><?php echo JText::_('COM_BOOKPRO_TO'); ?></label>
                <div class="controls form-inline" style="margin-left:140px;">
                    <?php echo JHtml::calendar($to, 'to', 'to', '%Y-%m-%d', 'readonly="readonly" style="width:80px;"') ?>  
                </div>
            </div>
            
            <div class="control-group" style="margin-bottom:18px;">
                <label for="pickup" class="control-label" style="width:120px; float:left;"><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_AVAILABLE'); ?></label>
                <div class="controls form-inline" style="margin-left:140px;">
                     <label class="radio"><input type="radio" name="available" value="1">Yes</label>
                     <label class="radio"><input type="radio" name="available" value="0">No</label>
                </div>
            </div>
            
            <div class="control-group" style="margin-bottom:18px;">
                <label for="pickup" class="control-label" style="width:120px; float:left;"><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_REQUEST'); ?></label>
                <div class="controls form-inline" style="margin-left:140px;">
                     <label class="radio"><input type="radio" name="request" value="1">Yes</label>
                     <label class="radio"><input type="radio" name="request" value="0">No</label>
                </div>
            </div>
            
            <div class="control-group" style="margin-bottom:18px;">
                <label for="pickup" class="control-label" style="width:120px; float:left;"><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_GUARANTEED'); ?></label>
                <div class="controls form-inline" style="margin-left:140px;">
                     <label class="radio"><input type="radio" name="guaranteed" value="1">Yes</label>
                     <label class="radio"><input type="radio" name="guaranteed" value="0">No</label>
                </div>
            </div> 
            
            <div class="control-group" style="margin-bottom:18px;">
                <label for="pickup" class="control-label" style="width:120px; float:left;"><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CLOSE'); ?></label>
                <div class="controls form-inline" style="margin-left:140px;">
                     <label class="radio"><input type="radio" name="close" value="1">Yes</label>
                     <label class="radio"><input type="radio" name="close" value="0">No</label>
                </div>
            </div>             
                                                           
            
                                                     
            <div class="control-group" style="margin-bottom:18px;">
                <div class="controls" style="margin-left:140px;">
                    <input type="submit" value="Submit" name="submit" class="btn btn-primary" >
                </div>
            </div>
                        
          </fieldset>   
		</div>                                                           
	</div>
        
	<div>                                              
		<input type="hidden" name="task" value="save" /> 
        <input type="hidden" name="controller" value="closetour" /> 
		<input type="hidden" name="tour_id" value="<?php echo $this->tour->id;?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<script>
    jQuery('body').attr('style','height:auto;');
</script>