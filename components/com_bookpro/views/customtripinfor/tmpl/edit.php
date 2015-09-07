<?php

/**
 * @package 	Bookpro
* @author 		Nguyen Dinh Cuong
* @link 		http://ibookingonline.com
* @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
* @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
* @version 	$Id: view.html.php 84 2012-08-17 07:16:08Z quannv $
**/

defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.framework');

$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');


$app = JFactory::getApplication();
$input = $app->input;
$id = $input->getInt('id',0);


$passenger = $this ->loadPassengerItem($id);
$fullname = $passenger->firstname . ' ' . $passenger->lastname;

?>
<br>
<div class="row-fluid">
<h3 class="passenger_info"><?php echo JText::_('COM_BOOKPRO_ORDER_PASSENGER_INFORMATION') ?></h3>
<div>In order to secure this booking. you need to enter your detail and those of anyone who travell with you. The passenger infomation must match passport you are travelling  on. You are kindly required to leader passenger. If you book the service for more passengers.</div>

 <h4 class="passenger_full_name"><?php echo JText::_('PASSENGER') ?>&nbsp;1:<?php echo $fullname ?></h4>
<form action="" method="post" id="adminForm" name="adminForm"
class="form-validate" enctype="multipart/form-data">
          <div class="form-horizontal">
		  <div class="row-fluid">
		     <div class=" input_detail span6">
		     <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
		     
		      <div class="control-group ">
                            <label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME');?> *
                            </label>
                            <div class="controls">
                                <input class=" input-medium required firstname" type="text" 
                                       name="firstname" 
                                       value="<?php echo $passenger->firstname;?>"  />
                            </div>
               </div>
                <div class="control-group ">
                            <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME');?> *
                            </label>
                            <div class="controls">
                                <input class="input-medium required lastname" type="text" 
                                       name="lastname" 
                                       value="<?php echo $passenger ->lastname;?>" />
                            </div>
                        </div>
                <div class="control-group ">
                            <label class="control-label" for="gender"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GENDER');?> *
                            </label>
                            <div class="controls">
                                <div class="form-inline">

                            <fieldset class="radio required" id="gender">
                                <label class="">
                                    <input type="radio" class="required" <?php echo $passenger->gender=='male'?' checked="checked"':'' ?>  name="gender" id="inlineCheckbox1" value="male">Male
                                </label>
                                <label class="">
                                    <input type="radio" class="required" <?php echo $passenger->gender=='female'?' checked="checked"':'' ?>  name="gender" id="inlineCheckbox2" value="female">Female
                                </label>
                            </fieldset>


                        </div>
                            </div>
                </div>
                         <div class="control-group ">
                            <label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_DATE_OF_BIRTH');?> *
                            </label>
                            <div class="controls">
                                <input  class=" required birthday validate-birth" type="text"  id="birthday" name="birthday" 
                                       value="<?php if( (int)($passenger->birthday)) echo JHtml::date('' .$passenger->birthday.'' , 'd-m-Y  ');?>"/>
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?> *
                            </label>
                            <div class="controls">
                                <input  class="inputbox required email" type="text"  placeholder="Email" id="email"
                                       name="email" 
                                       value="<?php echo $passenger ->email;?>"  />
                            </div>
                        </div>
                         <div class="control-group ">
                            <label class="control-label" for="cemail"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONFIRM_EMAIL');?> *
                            </label>
                            <div class="controls ">
                                <input  class="inputbox required confirm_email" type="text" id="cemail" placeholder="Confirm email"
                                       name="confirm_email" 
                                       value=""  />
                            </div>
                        </div>
		     </div>
		     
		     <!-- Span6 _2 -->
		      <div class="span6">
		     <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
		      <div class="control-group ">
                            <label class="control-label" for="homephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE');?> *
                            </label>
                            <div class="controls">
                                <input class=" input-small required " type="text"  placeholder="Home Phone" id="homephone"
                                       name="homephone" id="homephone" 
                                       value="<?php echo $passenger ->homephone;?>"  />
                            </div>
               </div>
                <div class="control-group validate-dropbox">
                            <label class="control-label" for="country1"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_RES_COUNTRY');?> *
                            </label>
                            <div class="controls">
                                <?php echo BookProHelper::getCountryTourBookSelect( $passenger->country1, 'country_id',"country1"); ?>
                  
                            </div>
                        </div>
                <div class="control-group ">
                            <label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAITIONALITY');?> *
                            </label>
                            <div class="controls">
                                   <?php echo BookProHelper::getCountryTourBookSelect( $passenger->country_id, 'country_id', "country_id"); ?>
                  
                            </div>
                </div>
                         <div class="control-group ">
                            <label class="control-label" for="passport"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_NO');?> *
                            </label>
                            <div class="controls">
                                <input  class="inputbox required passport error" type="text" placeholder="Number Passport" id="passport"
                                       name="passport" 
                                       value="<?php echo $passenger ->passport;?>"  />
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" for="passport_issue"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_ISSUE_DATE');?> *
                            </label>
                            <div class="controls">
                                <input id="passport_issue" class="inputbox required passport_issue validate-birth" type="text" placeholder="P. issue date"
                                       name="passport_issue" 
                                       value="<?php 
                                       if( (int)($passenger ->passport_issue)) echo JHtml::date('' .$passenger->passport_issue.'' , 'd-m-Y  ');  else echo ""?>"/>
                            </div>
                        </div>
                         <div class="control-group ">
                            <label class="control-label" for="passport_expiry"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_EXPIRY_DATE'); ?> *
                            </label>
                            <div class="controls">
                                <input  id="passport_expiry" class="required passport_expiry" type="text" placeholder="P. expiry date"
                                       name="passport_expiry" 
                                       value="<?php 
                                       if( (int)($passenger ->passport_expiry)) echo JHtml::date('' .$passenger->passport_expiry.'' , 'd-m-Y  ');  else echo ""?>"/>
                            </div>
                        </div>
		     </div>
		     </div>
		     <div class="expand row-fluid " >
					Add orther infomation			</div>
		     <div class="row-fluid">
		     <div class=" input_detail span6">
		     
		     <h5 class="passenger_detail">Orther detail</h5>
		     <div class="control-group ">
                            <label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_AGENT_MOBILE'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" input-small mobile" type="text" placeholder="Mobile"
                                       name="mobile" 
                                       value="<?php echo $passenger ->mobile;?>"  />
                            </div>
             </div>
                        
            <div class="control-group ">
                            <label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STREET_ADDRESS'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" address" type="text" placeholder="Street address"
                                       name="address" 
                                       value="<?php echo $passenger ->address;?>"  />
                            </div>
           </div>
           <div class="control-group ">
                            <label class="control-label" for="suburb"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SUBURB_TOWN'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" suburb" type="text" placeholder="Suburb/town"
                                       name="suburb" 
                                       value="<?php echo $passenger ->suburb;?>"  />
                            </div>
           </div>
         
           <div class="control-group ">
                            <label class="control-label" for="province"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE_PROVINCE'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" address" type="text" placeholder="State/province"
                                       name="province" 
                                       value="<?php echo $passenger ->province;?>"  />
                           </div>
          </div>
          <div class="control-group ">
                            <label class="control-label" for="code_zip"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_POST_CODE_ZIP'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" address" type="text" placeholder="Postcode/Zip"
                                       name="code_zip" 
                                       value="<?php echo $passenger ->code_zip;?>"  />
                            </div>
          </div>
		  </div>
		  
		    <div class="span6">
		      <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_EMERGENCY_CONTACT'); ?></h5>
		       <div class="control-group ">
                            <label class="control-label" for="emergency_name"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONTACT_NAME'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" emergency_name" type="text" placeholder="Contact name"
                                       name="emergency_name" 
                                       value="<?php echo $passenger ->emergency_name;?>"  />
                            </div>
           </div>
           <div class="control-group ">
                            <label class="control-label" for="emergency_mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE_PHONE'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" emergency_mobile" type="text" placeholder="Mobile phone"
                                       name="emergency_mobile" 
                                       value="<?php echo $passenger ->emergency_mobile;?>"  />
                            </div>
           </div>
         
           <div class="control-group ">
                            <label class="control-label" for="emergency_homephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOMEPHOME'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" emergency_homephone" type="text" placeholder="Home phone"
                                       name="emergency_homephone" 
                                       value="<?php echo $passenger ->emergency_homephone;?>"  />
                           </div>
          </div>
          <div class="control-group ">
                            <label class="control-label" for="emergency_address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAI_ADDRESS'); ?>
                            </label>
                            <div class="controls">
                                <input  class="emergency_address" type="text" placeholder="Email address"
                                       name="emergency_address" 
                                       value="<?php echo $passenger ->emergency_address;?>"  />
                            </div>
          </div>
		     </div>
		     
		     </div>
			 </div>
	 <hr/>			
                <input type="hidden" name="controller" value="customtripinfor">
                <input type="hidden" name="task" value="editpassenger">
                <input type="hidden" name="order_id" value="<?php echo $this->order_id; ?>"/>
                <input type="hidden" name="id" value ="<?php echo $id;?>" />
                <div style="text-align: center;" class="">
			          <input type="submit" value="Submit" class="btn btn-primary" /> 
			        <a href="index.php?option=com_bookpro&view=customtripinfor&order_id=<?php echo $this->order_id; ?>" > <input type="button" value="Cancel" class="btn" /></a> 
			    </div>
			   <?php echo JHtml::_('form.token'); ?>
       </form>	
   

       <br/> <br/>
       </div>    
                
 <!-- JavaScript -->
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
 $( "#birthday" ).datepicker({
	         dateFormat:"dd-mm-yy",
	        changeMonth: true,
	        changeYear: true,
	        showButtonPanel: false,
	        maxDate: new Date(),
	        buttonImageOnly: true,
	        buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
	        showOn: "both"
	   });
 $( "#passport_issue" ).datepicker({
     dateFormat:"dd-mm-yy",
    changeMonth: true,
    changeYear: true,
    showButtonPanel: false,
    maxDate: new Date(),
    buttonImageOnly: true,
    buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
    showOn: "both"
   });
 $( "#passport_expiry" ).datepicker({
     dateFormat:"dd-mm-yy",
    changeMonth: true,
    changeYear: true,
    showButtonPanel: false,
    buttonImageOnly: true,
    buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
    showOn: "both"
});

});
 </script>
 
<?php
$document = JFactory::getDocument ();
// $document->addStyleSheet(JURI::base() . 'example.css');
$style = '.passenger_detail {
            color: #016596;
            font-size: 15px;
            font-weight: normal;
}
.form-horizontal .control-label {
	text-align:left;
		}		
		.passenger_info{
		text-transform: uppercase; background-color: #EEEEEE; padding:5px; text-align: left;
		}
		.expand{
		background: #EEEEEE;padding: 5px;cursor: pointer;text-transform: uppercase; font-weight: bold;
		}
		.input_detail{
		padding-left:14px;
		}
		';
$document->addStyleDeclaration ( $style );
?>