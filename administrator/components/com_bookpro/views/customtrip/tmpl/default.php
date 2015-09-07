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
AImporter::helper('form');

/* validate using jquery validate plugin */
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if ($local != 'en') {
	$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
/* end valdiate */
?>
   
	<div class="row-fluid">
		 <div class="row-fluid">
             <h4 class="customtrip_info"><?php echo JText::_('COM_BOOKPRO_OPRATION') ?></h4>
		        <div class="customtrip_form">
             <h4 class="header_infor"></h4>
            <div class="span12 customtrip_detail">
			<div class="span6">
			  <h5 class="box_detail">Contact Information</h5>
			 <!--  Update order--> 
			 <form action="index.php" method="post" name="adminForm" id="adminForm" >
             <table class="table">
                <tbody>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>:
                        </th>
                        <!--  -->
                        <td><?php echo $this->getPayStatusSelect($this->order->pay_method); ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_STATUS'); ?>:
                        </th>
                        <td><?php echo $this->getOrderStatusSelect($this->order->order_status); ?>

                        </td>
                    </tr>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ASIGN_SALE'); ?>:
                        </th>
                       
                        <td><?php echo $this->getListSale($this->order->sale_id); ?>

                        </td>
                    </tr>
                  
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>:
                        </th>
                        <td><?php echo JHtml::_('date', $this->order->created); ?></td>
                    </tr>
                </tbody>
            </table>
            
            <input type="hidden" name="order_id" value="<?php echo $this->order->id; ?>" />
<?php echo FormHelper::bookproHiddenField(array('controller' => 'customtrip', 'task' => '', 'Itemid' => JRequest::getInt('Itemid'))) ?>
            
            
            </form>
         	</div>
         	<!--  Send email update --> 
			  <div class="span6">
			  <h5 class="box_detail">Send email</h5>
			  <div class="row-fluid">
              <table class="table">
                <tbody>
                    <tr>
                        <th><?php echo JText::_('COM_BOOKPRO_SEND_EMAIL'); ?>:
                        </th>
                        <td>
                        <input type="button" class="btn sendemailagain" value="<?php echo Jtext::_('COM_BOOKPRO_SENDEMAIL') ?>"/>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
            </div>
 <!--  Send user manual -->           
 <h5 class="box_detail"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_USER_MANUAL_HELP') ?>
 </h5>
<br>
<div class="row-fluid">
<form action="" method="post" id="adminForm" name="adminForm"
class="form-validate" enctype="multipart/form-data">
      <table class="table">
      <tbody>
      <tr>
       <th><?php echo JText::_('COM_BOOKPRO_TOUR_FILES'); ?></th>
       <td> <input name ="manual_file" type="file"  /></td>
       </tr>
       <tr>
        <th></th>
       <td>  <input type="submit" value="Send" class="btn btn-success" />   </td>
      </tr>
      </tbody>
      </table>
                <input type="hidden" name="controller" value="customtrip">
                <input type="hidden" name="task" value="sendusermanual">
                <input type="hidden" name="order_id" value="<?php echo $this ->order_id; ?>"/>
                <input type="hidden" name="id" value ="<?php echo $id;?>" />
			  
			   <?php echo JHtml::_('form.token'); ?>
			   
  </form>	
</div>
         	</div>
            </div>
			</div>			
		 </div>
		</div>
	<div class="row-fluid">
		 <div class="row-fluid">
             <h4 class="customtrip_info"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_INFOR') ?></h4>
		        <div class="customtrip_form">
             <h4 class="header_infor"></h4>
            <div class="span12 customtrip_detail">
            <div class="span6">
			 <h5 class="box_detail">Trip Request Information</h5>
              <?php echo JText::_( $this -> order ->notes)?>
			</div>
			<div class="span6">
			  <h5 class="box_detail">Contact Information</h5>
			  <b>Full name:</b>   <?php echo $this ->customer ->firstname. " ". $this ->customer->lastname;?> <br>
         <b>Address: </b><?php echo $this ->customer ->address;?><br>
         <b>Country: </b><?php echo $this ->loadCountryName($this ->customer->country_id);?><br>
         <b>Nationality: </b>United Kingdom <br>
         <b>Work phone: </b><?php  echo $this ->customer ->mobile;?><br>
         <b>Home phone or cell phone: </b><?php  echo $this ->customer ->telephone;?> <br>
         <b>E-mail address: </b> <a href="mailto:'<?php  echo  $this ->customer ->email;?>' " target="_blank"><?php  echo  $this ->customer ->email;?></a> <br>
			</div>
			
            </div>
			</div>			
		 </div>
		</div>
		
<div class="">
<br>
<div class="expand " >
<?php echo JText::_('COM_BOOKPRO_PASSENGER_MANAGER') ?>
</div>
<br> 

<div class="frontTourForm">
     <div class="row-fluid">
     <div class="row-fluid " >
     <table class="table table-bordered table-striped">
     <thead>
        <tr>
           <th>#</th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?></th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?></th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_BIRTHDAY'); ?></th>
           <th> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_TYPE'); ?></th>
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
          if( (int)($passenger ->birthday)) echo JHtml::date('' .$passenger->birthday.'' , 'd-m-Y  ');  else echo ""?>
          </td>
          <td> <?php $group = $passenger ->group_id;
          if ($group ==1)
          echo "Adult";
          else if ($group ==2) echo "Children";
          else echo "Infant";
          ?> </td>
          <td style="text-align: center;"><?php  echo JText::sprintf('%s',CurrencyHelper::displayPrice($passenger ->price));

          $total_price += $passenger ->price;?></td>
          <td width="15%"> 
                <?php echo "<a href='index.php?option=com_bookpro&view=customtrip&layout=edit&id=".$passenger->id."&cid[]=".$this->order_id."'  class='btn btn-info' >Edit</a>"; ?>                
                <?php echo "<a href='index.php?option=com_bookpro&controller=customtrip&task=removerPassenger&id=".$passenger->id."&order_id=".$this->order_id."'  class='btn btn-warning' >Delete</a>"; ?>                
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
       </div>
</div>
        <!-- end div   -->

</div><!-- end div well -->
        
<!-- JavaScript -->
<script type="text/javascript">

jQuery.noConflict();
jQuery(document).ready(function($){

	 $("#formaddPassenger").validate();
  });
</script>
<?php
$document = JFactory::getDocument ();
// $document->addStyleSheet(JURI::base() . 'example.css');
$style = '.customtrip_info{
		text-transform: uppercase; background-color: #EEEEEE; padding:5px; text-align: left;
		}
 		.expand{
		background: #EEEEEE;padding: 5px;cursor: pointer;text-transform: uppercase; font-weight: bold;
		}
 		.box_detail {
            color: #016596;
            font-size: 15px;
            font-weight: normal;
}
 		';
$document->addStyleDeclaration ( $style );
?>

 
