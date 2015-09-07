<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  2-04-2014 6:16:16
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.framework');

AImporter::helper('currency');
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();

/* validate using jquery validate plugin */
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if ($local != 'en') {
	$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}

$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
/* end valdiate */
//Load order,customer infor
$order = $this ->loadOrder($this->order_id);
//$customer = $this ->loadCustomer($order ->user_id);
$customer = $order ->customer;
// No direct access
?>

 <div class="row-fluid">
  <br>
    <div class="row-fluid"> 
    <div class="btn-group"> 
    <a class="btn btn-primary" href="<?php echo JURI::root()?>/index.php?option=com_bookpro&view=mypage">
      <i class="icon-chevron-left"></i>
      <span>Back</span>
    </a>
  </div>
    </div>
	<div class="row-fluid">
	 <h4 class="customtrip_info"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_INFOR') ?></h4>
		 <div class="row-fluid">
		    
            <h4 class="infor_header"></h4>
            <div class="span12 customtrip_item">
            <div class="span6">
			 	 <h5 class="box_detail">Trip Request Information</h5>
             <?php echo JText::_( $order ->notes)?>
		
			</div>
			<div class="span6">
			  <h5 class="box_detail">Contact Information</h5>
		 <b>Full name:</b>   <?php echo $customer ->firstname. " ". $customer->lastname;?> <br>
         <b>Address:</b>      <?php echo $customer ->address;?><br>
         <b>Country: </b>      <?php echo $this ->loadCountryName($customer->country_id);?><br>
         <b>Nationality:</b>   <?php echo $this ->loadCountryName($customer->country_id);?> <br>
         <b>Work phone: </b>   <?php  echo $customer ->mobile;?><br>
         <b>Home phone or cell phone: </b><?php  echo $customer ->telephone;?> <br>
         <b>E-mail address:</b> <a href="mailto:'<?php  echo  $customer ->email;?>' " target="_blank"><?php  echo  $customer ->email;?></a> <br>
		
			</div>
			
            </div>
			 
		 </div>
		 
<div class="row-fluid">
		    
            <div class="passenger_form">
            <div class="span12 passenger_item">
            <br>
            <div class="expand row-fluid " >
              <?php echo JText::_('COM_BOOKPRO_PASSENGER_MANAGER') ?>
            </div>
            <br>
            <div class="row-fluid">
             <table class="table table-bordered table-striped">
     <thead>
        <tr >
           <th>#</th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?></th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?></th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_BIRTHDAY'); ?></th>
           <th > <?php echo JText::_('COM_BOOKPRO_CUSTOMER_TYPE'); ?></th>
           <th style="text-align: center;"> <?php echo JText::_('COM_BOOKPRO_PRICE'); ?></th>
           <th></th>
        </tr>
     </thead>
     <tbody>
     <?php $passengers = $this ->loadPassengers($this->order_id);
     if (empty($passengers)) { ?>
                             <tr>
                                 <td colspan="7" class="emptyListInfo"><?php echo JText::_('No passenger.'); ?></td>
                             </tr>
       <?php }
      else{
      $total_price =0;
      foreach ($passengers as $key =>$passenger){
     ?>
      <tr>  
          <td> <?php echo $key+1;?> </td>
          <td> <?php echo $passenger ->firstname;?> </td>
          <td> <?php echo $passenger ->lastname;?> </td>
          <td> <?php 
          
        if( (int)($passenger->birthday)) echo JHtml::date('' .$passenger->birthday.'' , 'd-m-Y  ');  else echo "Not set"?> 
          </td>
          <td> <?php $group = $passenger ->group_id;
          if ($group ==1)
          echo "Adult";
          else if ($group ==2) echo "Children";
          else echo "Infant";
          ?> </td>
          <td style="text-align: center;"><?php
           echo JText::sprintf('%s',CurrencyHelper::displayPrice($passenger ->price));
          $total_price += $passenger ->price;?></td>
          <td width="15%"> 
                <?php echo "<a href='index.php?option=com_bookpro&view=customtripinfor&layout=edit&id=".$passenger->id."&order_id=".$this->order_id."'  class='btn btn-info' >Edit</a>"; ?>                
                <?php echo "<a href='index.php?option=com_bookpro&controller=customtripinfor&task=removerPassenger&id=".$passenger->id."&order_id=".$this->order_id."'  class='btn btn-warning' >Delete</a>"; ?>                
          </td>
          </tr>
         
      <?php }
      
      
      }?>
      <tr>
      <td colspan="5"><?php echo JText::sprintf('COM_BOOKPRO_TOTAL_PRICE_PASSENGER'); ?>
      </td>
      <td colspan="" style="text-align: center;">
     <?php  echo CurrencyHelper::displayPrice($total_price);?>
      </td>
      <td></td>
      
      </tr>
     </tbody>
     </table>
            </div>
            <br>
            </div>
</div>

<!--Passenger manager -->
     <div class="row-fluid">
            <div class="passenger_form">
            <h4 class="infor_header"></h4>
            <div class="span12 passenger_item">
               <div class="form-horizontal passenger_select">
                    <div class="row-fluid">
                        <div class="span4"></div>
                        <div class="control-group span2">
                            <label class="control-label" for="adult"><?php echo JText::_('COM_BOOKPRO_PASSENGER'); ?>
                            </label>
                            <div class="controls">
                                <?php echo JHtmlSelect::integerlist(0, 50, 1, 'adult', 'id="adult" class="input-small person"', ($total = count($this->cart->person->adult)) ? $total : 1); ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                
         <form action="" method="post" id="formaddPassenger" name="formaddPassenger"
               class="form-validate" enctype="multipart/form-data">
               
                <div id="frontTourForm" class="frontTourForm" >
                 <div id="passengers" class="passengers" >
                 </div>
                </div>
      
                <input type="hidden" name="controller" value="customtripinfor">
                <input type="hidden" name="task" value="savepassenger">
                <input type="hidden" name="order_id" value="<?php echo $order->id; ?>"/>
                <div style="text-align: center;" class="">
			           <input type="submit" value="Save" class="btn btn-success" /> 
			    </div>
			   <?php echo JHtml::_('form.token'); ?>
        </form>
       </div>
       </div>
       </div>
</div>
        <!-- end div   -->
 </div> 
   </div>     
<!-- JavaScript -->
 
<script type="text/javascript">

jQuery(document).ready(function($){
	var id = jQuery('#adult').val();
	$.ajax({
        url: 'index.php?option=com_bookpro&view=ajaxcustomtrip&adult='+id+'&tmpl=component',
        success: function(data) {
            jQuery("#passengers").html(data);  rendercalendar();
        }
    });
    
	jQuery("#adult").change(function () {
        var id = jQuery(this).val();
		$.ajax({
            url: 'index.php?option=com_bookpro&view=ajaxcustomtrip&adult='+id+'&tmpl=component',
            success: function(data) {
                jQuery("#passengers").html(data);
                rendercalendar();
            }
        });
     });

	 $("#formaddPassenger").validate();
	
	 
	 $( "#date_test" ).datepicker({
         dateFormat:"dd-mm-yy",
        changeMonth: true,
        changeYear: true,
        showButtonPanel: false,
        maxDate: new Date(),
        buttonImageOnly: true,
        buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
        showOn: "both"
   });

	
	 function rendercalendar()
     {
	     var item =$('.passenger_item'); 
	     var x=0;


	     $( ".passenger_itema" ).each(function( index ) {
	    	 // alert( index + ": " + $( this ).text() );

	    	  if ($(this).find('input.birthday').hasClass('hasDatepicker'))
              {
	            //  alert("OK");
                  $(this).find('input.birthday.hasDatepicker').removeClass('hasDatepicker');
                  $(this).find('img.ui-datepicker-trigger').remove();
                  $(this).find('input.birthday').removeAttr('id');
              }
	    	   $(this).find('input.birthday').datepicker({
                   dateFormat: "dd-mm-yy",
                   changeMonth: true,
                   changeYear: true,
                   showButtonPanel: false,
                   maxDate: new Date(),
                   buttonImageOnly: true,
                   buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
               });
	    	  
	    	});
     }

	
	 
  });

</script>

<?php
$document = JFactory::getDocument ();
// $document->addStyleSheet(JURI::base() . 'example.css');
$style = '.customtrip_info{
		text-transform: uppercase; background-color: #EEEEEE; padding:5px; text-align: left;
		}
 		.expand{
		background: #EEEEEE;cursor: pointer;text-transform: uppercase; font-weight: bold;
 		padding-top:5px;
        padding-bottom:5px;
        padding-left:5px;
		}
 		.box_detail {
            color: #016596;
            font-size: 15px;
            font-weight: normal;
}
 		
 		';
$document->addStyleDeclaration ( $style );
?>
