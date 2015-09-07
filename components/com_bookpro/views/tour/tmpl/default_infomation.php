<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  2-04-2014 6:16:16
 **/

// No direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$customer =$this ->loadCustomer ();
?>
<div class="row-fluid ">
	<h3 class="text-info"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_ADD_INF'); ?></h3>
</div>
<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label class="control-label" for="traveldate"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_ANTICIPATED_TRAVE');?>* </label>
			<div class="controls">
				<input id="traveldate" name="traveldate" type="text" placeholder=""
					class="span11  required traveldate" />
			</div>
		</div>
		<!-- -->
		<div class="control-group">
			<label class="control-label" for="day"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_DAY_PLANTRAVEL'); ?>*</label>
			<div class="controls">
				<input id="day" name="day" type="text" placeholder=""
					class="span11  required validate-numeric">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="number1"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_PEOPLE_TRAVELING'); ?>*</label>
			<div class="controls">
				<select name="number1" id ="number1" class="span5  required">
					<option value="">Adult</option>
					
					<?php for ($i=1;$i<=10;$i++){?>
					<option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php };?>
					
				</select> <select name="number2" id="number2" class="span6  required">
					<option value="">Child less 12 y</option>
					
					<?php for ($i=1;$i<=10;$i++){?>
					<option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php };?>
                    
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="select01"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_PLACE_START'); ?>*</label>
			<div class="controls">
				<select name="select01" id="select01" class="span5 required" onchange="madeSelection(this);">
					<option value="">Select country</option>
					<option value="vietnam">Vietnam</option>
					<option value="laos">Laos</option>
					<option value="cambodia">Cambodia</option>
					<option value="thailand">Thailand</option>
					<option value="myanmar">Myanmar</option>
				</select> 
				<select name="select02" id="select02" onchange=""
					class="span6  required">
					<option value="">Select city</option>
					
				</select>

			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="select001"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_PLACE_ENDTRIP'); ?></label>
			<div class="controls">
				<select name="select001" id="select001" class="span5" onchange="madeSelection1(this);">
					<option value="">Select country</option>
					<option value="vietnam">Vietnam</option>
					<option value="laos">Laos</option>
					<option value="cambodia">Cambodia</option>
					<option value="thailand">Thailand</option>
					<option value="myanmar">Myanmar</option>
				</select> <select name="select002" id="select002" onchange=""
					class="span6">
					<option value="">Select city</option>
				</select>

			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="budget"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_BUDGET_PERDAY'); ?>*</label>
			<div class="controls">
				<select name="budget" id ="budget" class="span5  required">
					<option value="">select budget</option>
					<option value="US$65 per day">US$ 65 per day</option>
					<option value="US$80 per day">US$ 80 per day</option>
					<option value="US$100 per day">US$ 100 per day</option>
					<option value="US$120 per day">US$ 120 per day</option>
					<option value="US$140 per day">US$ 140 per day</option>
					<option value="US$160 per day">US$ 160 per day</option>
					<option value="US$180 per day">US$ 180 per day</option>
					<option value="US$200 per day">US$ 200 per day</option>
					<option value="US$250 per day">US$ 250 per day</option>
					<option value="US$300 per day">US$ 300 per day</option>
					<option value="US$350 per day">US$ 350 per day</option>
				</select>&nbsp;Other <input type="text" name="other8" class="span5">
              
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="search"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_HEAR_ABOUT'); ?></label>
			<div class="controls">
				<select name="search" id="search" class="span11">
					<option value="">Please select one</option>
					<option value="Yahoo">Search Engine: Yahoo!</option>
					<option value="Infoseek">Search Engine: Infoseek</option>
					<option value="Excite">Search Engine: Excite</option>
					<option value="Lycos">Search Engine: Lycos</option>
					<option value="Webcrawler">Search Engine: Webcrawler</option>
					<option value="Hot Bot">Search Engine: Hot Bot</option>
					<option value="Alta Vista">Search Engine: Alta Vista</option>
					<option value="Voila">Search Engine: Voila</option>
					<option value="Other Search Engine ">Search Engine: Other</option>
					<option value="Web Site Link/Button">Web Site Link/Button</option>
					<option value="Magazine">Magazine</option>
					<option value="Word of Mouth">Word of Mouth</option>
					<option value="Other">Other</option>
				</select>

			</div>
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label class="control-label" for="gender"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_NAME_SURNAME'); ?>*</label>
			<div class="controls">
				<select name="gender" style="width: 85px" class="required">
					<option value="">Gender</option>
					<?php
					 for($i=1;$i<=3;$i++){
						if ($customer->gender ==$i)
							echo "<option selected value='".$i."'>".$this ->getGender($i)."</option>";
						else echo "<option value='".$i."'>".$this ->getGender($i)." </option>";
					} ?>
					
				</select>&nbsp;<input type="text" name="firstname" size="26"  value="<?php echo $customer->firstname;?>"
					class="required">

			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="nationality"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_NATIONALITY'); ?>*</label>
			<div class="controls">
				<?php echo BookProHelper::getCountryTourBookSelect( $customer->country_id, 'nationality', "nationality"); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_COUNTRY_RESIDENT'); ?>*</label>
			<div class="controls">
			   <?php echo BookProHelper::getCountryTourBookSelect( $customer->country_id, 'country_id', "country_id"); ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_HOME_ADDRESS'); ?>*</label>
			<div class="controls">
				<input type="text" name="address" class="span12  required" value="<?php echo $customer->address;?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_HOME_CELLPHONE'); ?>*
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_WORK_PHONE'); ?>*</label>
			<div class="controls">
				<input type="text" name="telephone" class="span6  required"  value="<?php echo $customer->telephone;?>"> 
				<input type="text" name="mobile" class="span6  required"  value="<?php echo $customer->mobile;?>"
				>
			</div>
		</div>
		<!-- Email -->
		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_EMAIL_ADDRESS'); ?>*</label>
			<div class="controls">
				<input type="text" name="email" id="email" class="span12  inputbox required <?php if (!$this->checkIsLogin()) echo 'validate-email'?>" 
				 value="<?php echo $customer->email;?>"
				>
			</div>
		</div>
		
		<!-- Re email -->
		<div class="control-group">
			<label class="control-label" for="cemail"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_RETYPE_EMAIL'); ?>*</label>
			<div class="controls">
				<input type="text" name="cemail" class="span12  required validate-cemail " id="cemail">
			</div>
		</div>
	</div>
</div>
<!--  -->
<div class="row-fluid">
	<div class="control-group">
		<label class="control-label" for="comment"><?php echo JText::_('COM_BOOKPRO_CUSTOMTRIP_OTHER_REQUIREMENT'); ?></label>
		<div class="controls">
			<textarea name="comment" cols="165" rows="3" class="span12"></textarea>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="control-group">
		<div class="controls">
			<input type="checkbox" name="newsletter" class="required"> Subscribe to receive our
			newsletters on travel events, special offers,..
		</div>
	</div>
</div>

