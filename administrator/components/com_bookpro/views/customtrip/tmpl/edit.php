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
                            <label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
                            </label>
                            <div class="controls">
                                <input class=" input-medium required firstname" type="text" 
                                       name="firstname" 
                                       value="<?php echo $passenger->firstname;?>"  />
                            </div>
               </div>
                <div class="control-group ">
                            <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
                            </label>
                            <div class="controls">
                                <input class="input-medium required lastname" type="text" 
                                       name="lastname" 
                                       value="<?php echo $passenger ->lastname;?>" />
                            </div>
                        </div>
                <div class="control-group ">
                            <label class="control-label" for="gender"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GENDER'); ?>
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
                            <label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_DATE_OF_BIRTH'); ?>
                            </label>
                            <div class="controls">
                                <?php echo JHtml::calendar($passenger->birthday, 'birthday', 'birthday', '%Y-%m-%d', 'readonly="readonly"') ?>
                              </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>
                            </label>
                            <div class="controls">
                                <input  class="inputbox required email" type="text"  placeholder="Email"
                                       name="email" 
                                       value="<?php echo $passenger->email;?>"  />
                            </div>
                        </div>
                         <div class="control-group ">
                            <label class="control-label" for="confirm_email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONFIRM_EMAIL'); ?>
                            </label>
                            <div class="controls ">
                                <input  class="inputbox required confirm_email" type="text"  placeholder="Confirm email"
                                       name="confirm_email" 
                                       value=""  />
                            </div>
                        </div>
		     </div>
		     
		     <!-- Span6 _2 -->
		      <div class="span6">
		     <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
		      <div class="control-group ">
                            <label class="control-label" for="homephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>
                            </label>
                            <div class="controls">
                                <input class=" input-small required " type="text"  placeholder="Home Phone"
                                       name="homephone" id="homephone" 
                                       value="<?php echo $passenger->homephone;?>"  />
                            </div>
               </div>
                <div class="control-group ">
                            <label class="control-label" for="country1"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_RES_COUNTRY'); ?>
                            </label>
                            <div class="controls">
                                <?php echo BookProHelper::getCountryTourBookSelect( $passenger->country1, 'country_id',"country1"); ?>
                  
                            </div>
                        </div>
                <div class="control-group ">
                            <label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAITIONALITY'); ?>
                            </label>
                            <div class="controls">
                                   <?php echo BookProHelper::getCountryTourBookSelect( $passenger->country_id, 'country_id', "country_id"); ?>
                  
                            </div>
                </div>
                         <div class="control-group ">
                            <label class="control-label" for="passport"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_NO'); ?>
                            </label>
                            <div class="controls">
                                <input  class="inputbox required passport error" type="text" placeholder="Number Passport"
                                       name="passport" 
                                       value="<?php echo $passenger->passport;?>"  />
                            </div>
                        </div>
                        <div class="control-group ">
                            <label class="control-label" for="passport_issue"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_ISSUE_DATE'); ?>
                            </label>
                            <div class="controls">
                                      <?php echo JHtml::calendar($passenger->passport_issue, 'passport_issue', 'passport_issue', '%Y-%m-%d', 'readonly="readonly"') ?>
                            </div>
                        </div>
                         <div class="control-group ">
                            <label class="control-label" for="passport_expiry"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_EXPIRY_DATE'); ?>
                            </label>
                            <div class="controls">
                               
                                    <?php echo JHtml::calendar($passenger->passport_expiry, 'passport_expiry', 'passport_expiry', '%Y-%m-%d', 'readonly="readonly"') ?>
                         
                            </div>
                        </div>
		     </div>
		     </div>
		     <div class="expand row-fluid " >
					Add order infomation			
			  </div>
			  <div class="row-fluid">
			 
			     <div class="input_detail span6">
			      <h5 class="passenger_detail">Order detail</h5>
			    <div class="control-group ">
                            <label class="control-label" for="price"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_PRICE'); ?>
                            </label>
                            <div class="controls">
                                <input  class="required price" type="text" placeholder="Price"
                                       name="price" 
                                       value="<?php  echo $passenger ->price; ?>"  />
                            </div>
                        </div></div>
			  </div>
		     <div class="expand row-fluid " >
					Add orther infomation			
			  </div>
		     <div class="row-fluid">
		     <div class="input_detail span6">
		     
		     <h5 class="passenger_detail">Orther detail</h5>
		     <div class="control-group ">
                            <label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_AGENT_MOBILE'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" input-small required mobile" type="text" placeholder="Mobile"
                                       name="mobile" 
                                       value="<?php echo $passenger->mobile;?>"  />
                            </div>
             </div>
                        
            <div class="control-group ">
                            <label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STREET_ADDRESS'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required address" type="text" placeholder="Street address"
                                       name="address" 
                                       value="<?php echo $passenger->address;?>"  />
                            </div>
           </div>
           <div class="control-group ">
                            <label class="control-label" for="suburb"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SUBURB_TOWN'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required address" type="text" placeholder="Suburb/town"
                                       name="suburb" 
                                       value="<?php echo $passenger->suburb;?>"  />
                            </div>
           </div>
         
           <div class="control-group ">
                            <label class="control-label" for="province"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE_PROVINCE'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required address" type="text" placeholder="State/province"
                                       name="province" 
                                       value="<?php echo $passenger->province;?>"  />
                           </div>
          </div>
          <div class="control-group ">
                            <label class="control-label" for="code_zip"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_POST_CODE_ZIP'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required address" type="text" placeholder="Postcode/Zip"
                                       name="code_zip" 
                                       value="<?php echo $passenger->code_zip;?>"  />
                            </div>
          </div>
		  </div>
		  
		    <div class="span6">
		      <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_EMERGENCY_CONTACT'); ?></h5>
		       <div class="control-group ">
                            <label class="control-label" for="emergency_name"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONTACT_NAME'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required emergency_name" type="text" placeholder="Contact name"
                                       name="emergency_name" 
                                       value="<?php echo $passenger->emergency_name;?>"  />
                            </div>
           </div>
           <div class="control-group ">
                            <label class="control-label" for="emergency_mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE_PHONE'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required emergency_mobile" type="text" placeholder="Mobile phone"
                                       name="emergency_mobile" 
                                       value="<?php echo $passenger->emergency_mobile;?>"  />
                            </div>
           </div>
         
           <div class="control-group ">
                            <label class="control-label" for="emergency_homephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOMEPHOME'); ?>
                            </label>
                            <div class="controls">
                                <input  class=" required emergency_homephone" type="text" placeholder="Home phone"
                                       name="emergency_homephone" 
                                       value="<?php echo $passenger->emergency_homephone;?>"  />
                           </div>
          </div>
          <div class="control-group ">
                            <label class="control-label" for="emergency_address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAI_ADDRESS'); ?>
                            </label>
                            <div class="controls">
                                <input  class="emergency_address" type="text" placeholder="Email address"
                                       name="emergency_address" 
                                       value="<?php echo $passenger->emergency_address;?>"  />
                            </div>
          </div>
		     </div>
		     
		     </div>
			 </div>
	 <hr/>			
                <input type="hidden" name="controller" value="customtrip">
                <input type="hidden" name="task" value="editpassenger">
                <input type="hidden" name="order_id" value="<?php echo $this ->order_id; ?>"/>
                <input type="hidden" name="id" value ="<?php echo $id;?>" />
			   <?php echo JHtml::_('form.token'); ?>
       </form>	
   

       <br/> <br/>
       </div>    
                
 

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
		padding-left:7px;
		}
		';
$document->addStyleDeclaration ( $style );
?>