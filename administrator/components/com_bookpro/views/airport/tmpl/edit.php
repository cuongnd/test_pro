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
	
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('type'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
                </div>

                <div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('parent_id'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('parent_id'); ?>
					</div>
				</div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('pickup'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('pickup'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('image'); ?></div>
                </div>

                
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('value'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('value'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('code'); ?></div>
                </div>
                
                
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('country_id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('country_id'); ?></div>
                </div>
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('airport_code'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('airport_code'); ?></div>
                </div>
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('location_airport'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('location_airport'); ?></div>
                </div>
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('capacity_airport'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('capacity_airport'); ?></div>
                </div>
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('airport_lodging'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('airport_lodging'); ?></div>
                </div>
                
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('intro'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('intro'); ?></div>
                </div>
                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desc'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desc'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('asian_air'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('asian_air'); ?></div>
                </div>
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('worldwide_air'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('worldwide_air'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('air_carries'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('air_carries'); ?></div>
                </div>
                
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
                </div>
                
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('air'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('air'); ?></div>
                </div>

				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('tour'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('tour'); ?></div>
                </div>
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('bus'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('bus'); ?></div>
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