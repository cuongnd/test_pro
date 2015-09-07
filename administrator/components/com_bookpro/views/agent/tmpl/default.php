<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 47 2012-07-13 09:43:14Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');


JToolBarHelper::custom('Edit', 'edit', 'edit', 'Edit', false);
$bar = &JToolBar::getInstance('toolbar');
JToolBarHelper::cancel();
JToolBarHelper::save();
JToolBarHelper::apply();
JHtml::_('behavior.formvalidation');

?>
	

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

<fieldset class="adminform"><legend><?php echo JText::_('COM_BOOKPRO_AGENT_DETAIL'); ?></legend>
<div class="form-horizontal">
	<div class="control-group">
		<label class="control-label" for="company"><?php echo JText::_('COM_BOOKPRO_AGENT_COMPANY_NAME'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->company; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_AGENT_EMAIL'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->email; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_AGENT_TELPHONE'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->telephone; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_AGENT_MOBILE'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->mobile; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="fax"><?php echo JText::_('COM_BOOKPRO_AGENT_FAX'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->fax; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_AGENT_ADDRESS'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->address; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="states"><?php echo JText::_('COM_BOOKPRO_AGENT_STATES'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->states; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_AGENT_COUNTRY'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->country_id; ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="created"><?php echo JText::_('COM_BOOKPRO_AGENT_CREATED_DATE'); ?>
		</label>
		<div class="controls">
			<?php echo $this->agent->created; ?>
		</div>
	</div>
</div>	
	

</fieldset>
 


        				

<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> 
<input	type="hidden" name="controller"	value="<?php echo CONTROLLER_AGENT; ?>" /> 
<input type="hidden"	name="boxchecked" value="1" /> 
	<input type="hidden" name="cid[]"	value="<?php echo $this->agent->id; ?>" /> 
	<input type="hidden"	name="task" value="" /> 
<input type="hidden" name="filter_agent-id"	value="<?php echo $this->agent->id; ?>" /> 
<?php echo JHTML::_('form.token'); ?>
</form>
