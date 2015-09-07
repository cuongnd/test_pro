<?php


defined('_JEXEC') or die('Restricted access');

/* @var $this BookingViewCustomer */

JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::back();
JToolBarHelper::title('Customer edit');
JHtml::_('behavior.modal');
AImporter::js('view-customer');
$config = &AFactory::getConfig();
jimport('joomla.html.html.bootstrap');
JHtml::_('behavior.formvalidation');

?>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

<?php echo JHtml::_('bootstrap.startTabSet', 'myTab',array('active'=>'tab1'));?>
<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab1', JText::_('Details')); ?>

    		<div class="form-horizontal">

    			<div class="control-group">
					<label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="firstname" id="firstname" size="60"  value="<?php echo $this->customer->firstname; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="lastname" id="lastname" size="60" maxlength="255" value="<?php echo $this->customer->lastname; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="gender"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GENDER'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('gender','',$this->customer->gender,JText::_('COM_BOOKPRO_MALE'),JText::_('COM_BOOKPRO_FEMALE'))?>
					</div>
				</div>
				<?php if($this->customer->user){

					$user=JUser::getInstance($this->customer->user);
					if(in_array($config->customersUsergroup,$user->groups)){
					?>
				<div class="control-group">
					<label class="control-label" for="cgroup_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GROUP'); ?>
					</label>
					<div class="controls">
						<?php echo $this->cgroups ?>
					</div>
				</div>
				<?php }} ?>

    			<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="state" value="<?php echo 1; ?>" id="state_active" <?php if ($this->customer->state == 1) echo 'checked="checked"'; ?>/>
						<label for="state_active"><?php echo JText::_('JPUBLISHED'); ?></label>
						<input type="radio" class="inputRadio" name="state" value="<?php echo 0; ?>" id="state_deleted" <?php if ($this->customer->state == 0) echo 'checked="checked"'; ?>/>
						<label for="state_deleted"><?php echo JText::_('JUNPUBLISHED'); ?></label>
					</div>
				</div>


    		</div>
    	<?php echo JHtml::_('bootstrap.endTab');?>
    	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab2', JText::_('COM_BOOKPRO_CUSTOMER_SYSTEM_DATA')); ?>


    			<?php if ($this->customer->id) { ?>

    			<div class="form-horizontal">

        			<?php if ($this->user->id) { ?>


		    			<div class="control-group">
							<label class="control-label" for="id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_USER'); ?>
							</label>
							<div class="controls">
								<a href="<?php echo ARoute::editUser($this->user->id); ?>" title=""><?php echo $this->user->username; ?></a>
							</div>
						</div>


		    			<div class="control-group">
							<label class="control-label" for="usertype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_USER_TYPE'); ?>
							</label>
							<div class="controls">
								<?php echo $this->customer->usertype ; ?>
							</div>
						</div>

						<div class="control-group">
							<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>
							</label>
							<div class="controls">
								<input class="text_area required" type="text" name="email" id="email" size="60" maxlength="255" value="<?php echo $this->user->email; ?>" />
							</div>
						</div>



       				<?php } else { ?>
							<div class="control-group" id="user1">
        					<label class="control-label" for="username"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SELECT_USER'); ?>
							 </label>
        					<div class="controls">
        						<?php echo $this->get('FormFieldUser'); ?>
        					</div>
    						</div>

       				<?php } ?>

       			</div>

        <?php } else { ?>

        	      <div class="control-group" id="user1">
        					<label class="control-label" for="username"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SELECT_USER'); ?>
							 </label>
        					<div class="controls">
        						<?php echo $this->get('FormFieldUser'); ?>
        					</div>
    						</div>

        	      		<?php } ?>

      		<?php echo JHtml::_('bootstrap.endTab');?>

      		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab3', JText::_('Contact')); ?>

    			<div class="form-horizontal">

    			<div class="control-group">
					<label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="telephone" id="telephone" size="60" maxlength="255" value="<?php echo $this->customer->telephone; ?>" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="fax"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FAX'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="fax" id="fax" size="60" maxlength="255" value="<?php echo $this->customer->fax; ?>" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="address" id="address" size="60" maxlength="255" value="<?php echo $this->customer->address; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="city"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CITY'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="city" id="city" size="60" maxlength="255" value="<?php echo $this->customer->city; ?>" />
					</div>
				</div>


				<div class="control-group">
					<label class="control-label" for="states"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATES'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="states" id="states" size="60" maxlength="255" value="<?php echo $this->customer->states; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="zip"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ZIP'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="zip" id="zip" size="60" maxlength="255" value="<?php echo $this->customer->zip; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="countries"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY'); ?>
					</label>
					<div class="controls">
						<?php echo $this->countries; ?>
					</div>
				</div>
				</div>
    	<?php echo JHtml::_('bootstrap.endTab');?>
    	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab4', JText::_('COM_BOOKPRO_CUSTOMER_REWARDS')); ?>

    			<div class="form-horizontal">

	    			<div class="control-group">
						<label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_POINT'); ?>
						</label>
						<div class="controls">
							<?php echo $this->customer->point ?><a style="margin: 0 10px" href="index.php?option=com_bookpro&view=pointlog&customer_id=<?php echo $this->customer->id ?>" class="btn"><?php echo JText::_('COM_BOOKPRO_POINTLOG')?></a>
						</div>
					</div>


				</div>
    	<?php echo JHtml::_('bootstrap.endTab');?>
    	<?php echo JHtml::_('bootstrap.endTabSet');?>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CUSTOMER; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->customer->id; ?>"/>
	<!-- Use for display customers reservations -->
	<input type="hidden" name="filter_customer-id" value="<?php echo $this->customer->id; ?>"/>
	<?php echo JHTML::_('form.token'); ?>
</form>
