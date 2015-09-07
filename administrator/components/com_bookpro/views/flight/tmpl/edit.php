<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$params=JcomponentHelper::getParams('com_bookpro');	

?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desfrom'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desfrom'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desto'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desto'); ?></div>
                </div>

                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('airline_id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('airline_id'); ?></div>
                </div>
                
                
                <?php if($params->get('show_seat')) {?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('base_seat'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('base_seat'); ?></div>
                </div>
                <?php } ?>
                
                <!--  
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('eco_seat'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('eco_seat'); ?></div>
                </div>
                
                
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('bus_seat'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('bus_seat'); ?></div>
                </div>
                 -->
                
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('flightnumber'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('flightnumber'); ?></div>
                </div>
                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('start'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('end'); ?></div>
                </div>
				<div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('duration'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('duration'); ?></div>
                </div>
				<div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('frequency'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('frequency'); ?></div>
                </div>
            </fieldset>	
        </div>
<?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>

    </div>

    <div>
        <input type="hidden" name="task" value="" /> 
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
<?php echo JHtml::_('form.token'); ?>
    </div>
</form>