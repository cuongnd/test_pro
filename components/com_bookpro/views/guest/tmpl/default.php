<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtmlBehavior::formvalidation();
$action_show_order=JURI::base().'index.php?option=com_bookpro&controller=customer&task=show_order';
?>
<form name="vieworder" method="post" action="<?php echo $action_show_order?>" class="form-validate form-horizontal">
    <fieldset>
        <legend>
            <?php echo JText::_("COM_BOOKPRO_MANAGE_SINGLE_BOOKING") ?>
        </legend>

		
           <div class="control-group">
			<label class="control-label"> <?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?></label>
			
			<div class="controls">
                <input type="text" class="required" id="email" name="email" value="" size="25" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>"  required/>
          		
            </div>
            </div>
            
            <div class="control-group">
			<label class="control-label"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?></label>
            	<div class="controls">
        
                <input type="text" class="required" id="order_number" name="order_number" value="" placeholder="<?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?>"  required/>
          	 	<span class="help-block"><a href="#" data-toggle="tooltip" title="Check booking information in your email">Where can I find this information?</a></span>
          	 </div>
            </div>   
            
             <div class="control-group">
            	<div class="controls">
               <button type="submit" class="btn btn-medium btn-primary"  type="submit"> <?php echo JText::_('COM_BOOKPRO_SUBMIT'); ?> </button>
          	 </div>
            </div>  
        <?php echo JHtml::_('form.token'); ?>
    </fieldset>    
</form>
