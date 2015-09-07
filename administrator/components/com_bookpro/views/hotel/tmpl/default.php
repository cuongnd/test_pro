<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 84 2012-08-17 07:16:08Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
JHtml::_('behavior.modal','a.jbmodal');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::back();
JToolBarHelper::title($this->obj->id ? JText::_('COM_BOOKPRO_HOTEL_EDIT') : JText::_('COM_BOOKPRO_ADD_HOTEL'), 'object');


JHtml::_('behavior.formvalidation');	
?>
<script type="text/javascript">       
 Joomla.submitbutton = function(task) {
      var form = document.adminForm;
      if (task == 'cancel') {
         form.task.value = task;
         form.submit();
         return;
      }
      if (document.formvalidator.isValid(form)) {
         form.task.value = task;
         form.submit();
       }else {
         alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');
         return false;
       }
   }
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

			<div class="form-horizontal">
            
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab',array('active'=>'tab1'));?> 
 <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab1', JText::_('Basic')); ?> 

		<div class="form-horizontal">
        
			<div class="control-group">
				<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_SELECT_SUPPLIER'); ?>
				</label>
				<div class="controls">
					 <?php echo HotelHelper::getSupplierSelect($this->obj->userid); ?>
				</div>
			</div>
            
           <div class="control-group">
                <label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_HOTEL_TITLE'); ?>
                </label>
                <div class="controls">
                    <input class="text_area required" type="text" name="title" id="title"
                        size="60" maxlength="255"  value="<?php echo $this->obj->title; ?>" />
                </div>
            </div>
            <div class="control-group">
				<label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_ALIAS'); ?>
				</label>
				<div class="controls">
					<input class="text_area" type="text" name="alias" id="alias"
						size="60" maxlength="255"  value="<?php echo $this->obj->alias; ?>" />
				</div>
			</div>
             <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN_TIME'); ?>
                </label>
                <div class="controls">
                    <?php echo $this->getTimeSelect('checkin_time',$this->obj->checkin_time)?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT_TIME'); ?>
                </label>
                <div class="controls">
                    <?php echo $this->getTimeSelect('checkout_time',$this->obj->checkout_time)?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_ACCOMMONDATION_TYPE'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="accommondation_type" id="accommondation_type" size="60" maxlength="255" required value="<?php echo $this->obj->accommondation_type; ?>" />
                </div>
            </div> 
			
			  <div class="control-group">
                <label class="control-label" for="address2"><?php echo JText::_('COM_BOOKPRO_HOTEL_TERM_CONDITIONS'); ?>
                </label>
                
                <div class="controls">
                    <?php
                    $editor=JFactory::getEditor();
                        echo $editor->display('term_conditions', $this->obj->term_conditions, '100%', '300', '60', '20', false);?>
                </div>
                
                
                
            </div>
			 <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_MOBILE_NUMBER'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="mobile" id="mobile" size="60" maxlength="255" required value="<?php echo $this->obj->mobile; ?>" />
                </div>
            </div> 

			<div class="control-group">
				<label class="control-label" for="address1"><?php echo JText::_('COM_BOOKPRO_HOTEL_ADDRESS1'); ?>
				</label>
				<div class="controls">
					<input class="text_area" type="text" name="address1" id="address1"
						size="60" maxlength="255" required value="<?php echo $this->obj->address1; ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="address2"><?php echo JText::_('COM_BOOKPRO_HOTEL_ADDRESS2'); ?>
				</label>
				<div class="controls">
					<input class="text_area" type="text" name="address2" id="address2"
						size="60" maxlength="255"
						value="<?php echo $this->obj->address2; ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="categories"><?php echo JText::_('COM_BOOKPRO_HOTEL_CATEGORY'); ?>
				</label>
				<div class="controls">
					<?php echo $this->categories ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="rank"><?php echo JText::_('COM_BOOKPRO_HOTEL_RANK'); ?>
				</label>
				<div class="controls">
					<?php echo JHtmlSelect::integerlist(1, 5, 1, 'rank',"",$this->obj->rank)?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="cities"><?php echo JText::_('COM_BOOKPRO_HOTEL_CITY'); ?>
				</label>
				<div class="controls">
					<?php echo $this->cities ?>
				</div>
			</div>
			
			<div class="control-group">
					<label class="control-label" for="featured"><?php echo JText::_('COM_BOOKPRO_FEATURED'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="featured" value="1"
							id="featured_active"
							<?php if ($this->obj->featured == 1) echo 'checked="checked"'; ?> />
							<label for="featured_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?> </label>
							<input type="radio" class="inputRadio" name="featured" value="0"
							id="featured_inactive"
							<?php if ($this->obj->featured == 0) echo 'checked="checked"'; ?> />
							<label for="featured_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?> </label>
					</div>
				</div>
				
					<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATE'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="state" value="1"
							id="state_active"
							<?php if ($this->obj->state == 1) echo 'checked="checked"'; ?> />
							<label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?> </label>
							<input type="radio" class="inputRadio" name="state" value="0"
							id="state_inactive"
							<?php if ($this->obj->state == 0) echo 'checked="checked"'; ?> />
							<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?> </label>
					</div>
				</div>
          
			</div>
			<?php echo JHtml::_('bootstrap.endTab');?> 
            
           <?php //echo JHtml::_('bootstrap.addTab', 'myTab', 'tab2', JText::_('COM_BOOKPRO_HOTEL_FACILITY')); ?> 
                     <?php //echo $this->facilities ?>
           <?php // echo JHtml::_('bootstrap.endTab');?>
              
              
			 <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab3', JText::_('COM_BOOKPRO_HOTEL_GALLERY_MAP')); ?> 
			 <div class="control-group">
				<label class="control-label" for="images"><?php echo JText::_('COM_BOOKPRO_HOTEL_GALLERY'); ?>
				</label>
				<div class="controls">
					<?php  AImporter::tpl('images', 'form', 'images',SITE_VIEWS); ?>
			 	</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="geo"><?php echo JText::_('COM_BOOKPRO_GEOLOCATION'); ?>
				</label>
				<div class="controls">
					<?php 
					$this->longitude=$this->obj->longitude;
					$this->latitude=$this->obj->latitude;
					  AImporter::tpl('geolocalization', 'form', 'geo',SITE_VIEWS); 
                     ?>
				</div>
			</div>
			
			<?php echo JHtml::_('bootstrap.endTab');?> 
			
    	   <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab4', JText::_('COM_BOOKPRO_HOTEL_DETAIL')); ?> 
			<div class="control-group">
				<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_HOTEL_EMAIL'); ?>
				</label>
				<div class="controls">
					<input class="text_area" type="text" name="email" id="email"
						size="60" maxlength="255" required value="<?php echo $this->obj->email; ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="website"><?php echo JText::_('COM_BOOKPRO_HOTEL_WEBSITE'); ?>
				</label>
				<div class="controls">
					<input class="text_area " type="text" name="website" id="website"
						size="60" maxlength="255" required value="<?php echo $this->obj->website; ?>" />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="phone"><?php echo JText::_('COM_BOOKPRO_HOTEL_PHONE'); ?>
				</label>
				<div class="controls">
					<input class="text_area" type="text" name="phone" id="phone"
						size="60" maxlength="255" value="<?php echo $this->obj->phone; ?>" />
				</div>
			</div>              

			<div class="control-group">
				<label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_HOTEL_DESCRIPTION'); ?>
				</label>
				<div class="controls">
					<?php
					$editor=JFactory::getEditor();
						echo $editor->display('desc', $this->obj->desc, '100%', '300', '60', '20', false);?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="cancel_policy"><?php echo JText::_('COM_BOOKPRO_HOTEL_CANCEL_POLICY'); ?>
				</label>
				<div class="controls">
					<?php
					$editor=JFactory::getEditor();
						echo $editor->display('cancel_policy', $this->obj->cancel_policy, '100%', '300', '60', '20', false);?>
				</div>
			</div>
		<?php echo JHtml::_('bootstrap.endTab');?> 
        
        
         <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab5', JText::_('COM_BOOKPRO_HOTEL_FEE')); ?> 
         
            <div class="control-group">
                <label class="control-label" for="address2"><?php echo JText::_('COM_BOOKPRO_HOTEL_PROMO_FEE'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="promo_fee" id="promo_fee" size="60" maxlength="255" value="<?php echo $this->obj->promo_fee; ?>"/>
                </div>
            </div>
            
             <div class="control-group">
                <label class="control-label" for="address2"><?php echo JText::_('COM_BOOKPRO_HOTEL_PREMIUM'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="premium" id="premium" size="60" maxlength="255" value="<?php echo $this->obj->premium; ?>"/>
                </div>
            </div>
            
             <div class="control-group">
                <label class="control-label" for="address2"><?php echo JText::_('COM_BOOKPRO_HOTEL_AGENT_COMISSION'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="agent_comission" id="agent_comission" size="60" maxlength="255" value="<?php echo $this->obj->agent_comission; ?>"/>
                </div>
            </div>
            
             <div class="control-group">
                <label class="control-label" for="address2"><?php echo JText::_('COM_BOOKPRO_HOTEL_ADDITIONAL_COMISSION'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="add_comission" id="add_comission" size="60" maxlength="255" value="<?php echo $this->obj->add_comission; ?>"/>
                </div>
            </div>
            
        <?php echo JHtml::_('bootstrap.endTab');?> 
          
        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab6', JText::_('COM_BOOKPRO_HOTEL_LEGAL_INFORMATION')); ?> 
        
          
            
            
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_VAT_NO'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="vat_no" id="vat_no" size="60" maxlength="255" required value="<?php echo $this->obj->vat_no; ?>" />
                </div>
            </div> 
            
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_PAN_ID'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="pan_no" id="pan_no" size="60" maxlength="255" required value="<?php echo $this->obj->pan_no; ?>" />
                </div>
            </div> 
            
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_REGISTRATION_NUMBER'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="code" id="code" size="60" maxlength="255" required value="<?php echo $this->obj->code; ?>" />
                </div>
            </div> 
            
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_EXCISE_NUMBER'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="excise_no" id="excise_no" size="60" maxlength="255" required value="<?php echo $this->obj->excise_no; ?>" />
                </div>
            </div> 
            
            <div class="control-group">
                <label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_HOTEL_SERVICE_TAX_NO'); ?>
                </label>
                <div class="controls">
                    <input class="text_area" type="text" name="service_tax_no" id="service_tax_no" size="60" maxlength="255" required value="<?php echo $this->obj->service_tax_no; ?>" />
                </div>
            </div>
       
        <?php echo JHtml::_('bootstrap.endTab');?> 
                     
    	<?php echo JHtml::_('bootstrap.endTabSet');?>
	</div>
	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
	 <input type="hidden" name="controller"	value="<?php echo CONTROLLER_HOTEL; ?>" />
	  <input type="hidden"	name="task" value="save" /> 
	  <input type="hidden" name="boxchecked"value="1" /> <input type="hidden" name="cid[]"	value="<?php echo $this->obj->id; ?>" id="cid" />

	 <?php echo JHTML::_('form.token'); ?>
</form>

