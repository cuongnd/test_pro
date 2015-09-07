<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 91 2012-08-24 16:29:55Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
jimport( 'joomla.html.editor' );
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('jquery.framework');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('behavior.formvalidation');
JToolBarHelper::title(JText::_('COM_BOOKPRO_ITINERARY_EDIT'), 'user.png');
  $cids = ARequest::getCids();
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

	$.ajax({
        url:'index.php?option=com_bookpro&controller=itinerary&task=ajaxDest&dest_id='+$('#dest_id').val()+'&cid=<?php echo $cids[0]; ?>'+'&tmpl=component',
        beforeSend: function(){
            jQuery("#destchilds").html('<div align="center"><img src="<?php echo JUri::root(); ?>components/com_bookpro/assets/images/loader.gif" /><div>');
        },
        success:function(data){
            $('#destchilds').html(data);
        }
    });

    $('#dest_id').change(function(){
       
    	$.ajax({
            url:'index.php?option=com_bookpro&controller=itinerary&task=ajaxDest&dest_id='+$(this).val()+'&cid=<?php echo $cids[0]; ?>'+'&tmpl=component',
            beforeSend: function(){
                jQuery("#destchilds").html('<div align="center"><img src="<?php echo JUri::root(); ?>components/com_bookpro/assets/images/loader.gif" /><div>');
            },
            success:function(data){
                $('#destchilds').html(data);
            }
        });
    });

    


});
</script>
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
       }
       else {
         alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');
         return false;
       }
   }
 window.addEvent('domready', function() {
	    
    document.formvalidator.setHandler('select', function (value) { return (value != 0); } );
});
 </script>
      <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
			
		
			<div class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="amount"><?php echo JText::_('COM_BOOKPRO_TOUR'); ?>
					</label>
					<div class="controls">
						<?php echo $this->tours; ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('COM_BOOKPRO_DEST') ?></label>
					<div class="controls">
						<?php
			                 echo $this->dests;
			             ?>
					</div>
				</div>	
				<div class="control-group">
					<label class="control-label">
						<?php echo JText::_('COM_BOOKPRO_DEST_CHILDREN') ?>
					</label>
					<div id="destchilds" class="controls">
						
					</div>
				</div>	
				
				<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_ITINERARY_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="title" id="title"
						size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>
				
				
				<div class="control-group">
                    <label class="control-label" for="meal"><?php echo JText::_('COM_BOOKPRO_ITINERARY_MEAL'); ?>
                    </label>
                    <div class="controls">
                        <?php
                             echo $this->meal;
                        ?>
                    </div>
                </div>
				
				<div class="control-group">
                    <label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_ITINERARY_SHORT_DESC'); ?>
                    </label>
                    <div class="controls">
                    <?php
                        //$editor=JFactory::getEditor();
                        $editor=JEditor::getInstance();
                        echo $editor->display('short_desc', $this->obj->short_desc, '100%', '100px', '30', '20', true,null,null,null,array('mode' => 'advanced'));?>
                       
                    </div>
                </div>
				<div class="control-group">
					<label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_ITINERARY_DESCRIPTION'); ?>
					</label>
					<div class="controls">
						<?php
					$editor=JFactory::getEditor();
					echo $editor->display('desc', $this->obj->desc, '100%', '400', '60', '20', true);?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('state','class="btn-group"',$this->obj->state,'Publish','UnPublish','id_state') ?>
					</div>
				</div>
				
		</div>
		

	
	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_ITINERARY; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />
	<!-- Use for display customers reservations -->

		<?php echo JHTML::_('form.token'); ?>
</form>
