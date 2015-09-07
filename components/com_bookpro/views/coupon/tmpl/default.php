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

JHtml::_('jquery.framework');
JHTML::_('behavior.tooltip'); 
AImporter::helper('hotel'); 

/* validate using jquery validate plugin */
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if($local !='en'){
    $document->addScript(JURI::root().'components/com_bookpro/assets/js/validatei18n/messages_'.$local.'.js');
}
/* end valdiate*/

?>
<div class="row-fluid">
    <div class="span12"> 
    <?php
        $layout = new JLayoutFile('suppliermenu', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
        $html = $layout->render(array());
        echo $html;
    ?>
        <fieldset>
            <legend>
                <?php
                    echo $this->obj->id ? JText::_('COM_BOOKPRO_EDIT_COUPON'): JText::_('COM_BOOKPRO_ADD_COUPON');

                ?>                               
            </legend>
                        
<form action="index.php" method="post" name="couponeditForm" id="couponeditForm">
		 <div class="form-horizontal">
			 
			 	<div class="control-group">
					<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_HOTEL')?>
					</label>
					<div class="controls">
						<?php echo $this->hotels; ?>
					</div>
				</div> 
			
				<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_COUPON_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="title" id="title"
						size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" required/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="code"><?php echo JText::_('COM_BOOKPRO_COUPON_CODE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="code"
						id="code" size="20" maxlength="255"
						value="<?php echo $this->obj->code; ?>" required/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="subtract_type"><?php echo JText::_('COM_BOOKPRO_COUPON_SUBTRACT_TYPE'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('subtract_type','',$this->obj->subtract_type,JText::_('COM_BOOKPRO_PERCENTAGE'),JText::_('COM_BOOKPRO_FIX_AMOUNT'))?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="amount"><?php echo JText::_('COM_BOOKPRO_COUPON_AMOUNT'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="amount"
						id="amount" size="60" maxlength="255"
						value="<?php echo $this->obj->amount; ?>" required/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="total"><?php echo JText::_('COM_BOOKPRO_COUPON_TOTAL'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="total"
						id="total" size="60" maxlength="255"
						value="<?php echo $this->obj->total; ?>" required/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="remain"><?php echo JText::_('COM_BOOKPRO_COUPON_REMAIN'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="remain"
						id="remain" size="60" maxlength="255"
						value="<?php echo $this->obj->remain; ?>" required/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="publish_date"><?php echo JText::_('COM_BOOKPRO_PUBLISH_DATE'); ?>
					</label>
					<div class="controls">
						<?php echo JHtml::calendar($this->obj->publish_date, 'publish_date', 'publish_date','%Y-%m-%d') ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="unpublish_date"><?php echo JText::_('COM_BOOKPRO_UNPUBLISH_DATE'); ?>
					</label>
					<div class="controls">
						<?php echo JHtml::calendar($this->obj->unpublish_date, 'unpublish_date', 'unpublish_date','%Y-%m-%d') ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="state" value="1"
						id="state_active"
						<?php if ($this->obj->state == 1) echo 'checked="checked"'; ?> />
						<label for="state_active"><?php echo JText::_('Active'); ?> </label>
						<input type="radio" class="inputRadio" name="state" value="0"
						id="state_inactive"
						<?php if ($this->obj->state == 0) echo 'checked="checked"'; ?> />
						<label for="state_deleted"><?php echo JText::_('Inactive'); ?> </label>
					</div>
				</div>
			</div>  
            
        <div class="center-button span5">
            <input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>"/>         
            
            <?php $linkr = ARoute::view('coupons',null,null,array('Itemid'=>JRequest::getVar('Itemid')));?>
            <a href="<?php echo $linkr;?>" title="<?php echo JText::_('COM_BOOKPRO_ROOM_MANAGER');?>">
                <input type="button" class="btn" name="cancel" value="<?php echo JText::_('COM_BOOKPRO_CANCEL');?>"/>
            </a>
        </div>  
	
	    <input type="hidden" name="option" value="<?php echo OPTION; ?>" /> 
        <input type="hidden" name="controller" value="<?php echo CONTROLLER_COUPON; ?>" /> 
        <input type="hidden" name="task" value="save" /> 
        <input type="hidden" name="boxchecked"value="1" /> 
        <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid" />
        <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>
		<?php echo JHTML::_('form.token'); ?>
</form>

        </fieldset>        
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($){
    
$("#couponeditForm").validate({
    lang: '<?php echo $local ?>',
     rules: {
     'hotel_id': {
      required: true,
      number: true
    }
  }
});
});

</script>