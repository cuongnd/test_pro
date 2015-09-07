<?php
    jimport('joomla.html.html.select');

    defined('_JEXEC') or die('Restricted access');

    AHtml::title('Destination Edit','user');
    JToolBarHelper::save();
    JToolBarHelper::apply();
    JToolBarHelper::cancel();
    JHtml::_('behavior.modal','a.jbmodal');
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
        }
        else {
            alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');
            return false;
        }
    }
    jQuery(document).ready(function($){
        $('body').delegate('#parent_id', 'change', function() {
            $.ajax({
                type: "GET",
                url: 'index.php',
                data:{
                    option:'com_bookpro',
                    controller:'airport',
                    id:$(this).val(),
                    task:'getchildByParent'
                },
                crossDomain: true,
                async: false,
                dataType: "json",
                contentType: "application/json",
                beforeSend: function() {
                    $('#loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function(data) {
                    alert(data);
                    $('#loading').css({

                        display:"none"
                    });
                    $("#child").html(data);
                }
            });
        });
    });
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	 <div class="form-horizontal">
    	
    	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active'=>'general'));?>
   	 	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('General')); ?>
        <div class="control-group">
            <label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_AIRPORT_TITLE'); ?>
            </label>
            <div class="controls">
                <input class="text_area required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
            </div>
        </div>

        
        <div class="control-group">
            <label class="control-label" for="value"><?php echo JText::_('COM_BOOKPRO_DESTINATION_SHORT_TITLE'); ?>
            </label>
            <div class="controls">
                <input class="text_area required" type="text" name="value" id="value" size="60" maxlength="255" value="<?php echo $this->obj->value; ?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="value"><?php echo JText::_('COM_BOOKPRO_DESTINATION_PICKUP'); ?>
            </label>
            <div class="controls">
                <input class="text_area required" type="text" name="pickup" id="value" size="60" maxlength="255" value="<?php echo $this->obj->pickup; ?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="code"><?php echo JText::_('COM_BOOKPRO_AIRPORT_IATA_CODE'); ?>
            </label>
            <div class="controls">
                <input class="text_area" type="text" name="code" id="code" size="60" maxlength="255" value="<?php echo $this->obj->code; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="countries"><?php echo JText::_('COM_BOOKPRO_AIRPORT_PARENT'); ?>
            </label>
            <div class="controls">
                <?php echo $this->parents; ?>
            </div>
        </div>
		 
        <div class="control-group">
            <label class="control-label" for="countries"><?php echo JText::_('COM_BOOKPRO_AIRPORT_COUNTRY'); ?>
            </label>
            <div class="controls">
                <?php echo $this->countries; ?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="geo"><?php echo JText::_('COM_BOOKPRO_GEOLOCATION'); ?>
            </label>
            <div class="controls">
                <?php 
                    $this->longitude=$this->obj->longitude;
                    $this->latitude=$this->obj->latitude;
                    echo AImporter::tpl('geolocalization', $this->_layout, 'geo'); ?>
            </div>
        </div>

        

        <div class="control-group">
            <label class="control-label" for="air"><?php echo JText::_('COM_BOOKPRO_AIRPORT_IS_AIRPORT'); ?>
            </label>
            <div class="form-inline">
                <?php echo JHtmlSelect::booleanlist('air','',$this->obj->air,'Yes','No')?> 
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="bus"><?php echo JText::_('COM_BOOKPRO_AIRPORT_IS_BUS_STATION'); ?>
            </label>
            <div class="form-inline">
                <?php echo JHtmlSelect::booleanlist('bus','',$this->obj->bus,'Yes','No')?>  
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="province"><?php echo JText::_('COM_BOOKPRO_AIRPORT_IS_PROVINCE_CITY'); ?>
            </label>
            <div class="form-inline">
                <?php echo JHtmlSelect::booleanlist('province','class="btn-group"',$this->obj->province,'Yes','No')?>  
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_AIRPORT_STATUS'); ?>
            </label>
            <div class="form-inline">
                <?php echo JHtmlSelect::booleanlist('state','',$this->obj->state,'Publish','UnPublish','id_state') ?> 
            </div>
        </div>
        
       
        
        <?php echo JHtml::_('bootstrap.endTab');?>
    	<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab2', JText::_('COM_BOOKPRO_AIRPORT_GALLERY')); ?>
		     
	        <div class="control-group">
	            <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_AIRPORT_GALLERY')?>
	            </label>
	            <div class="controls">
	                <?php AImporter::tpl('images', $this->_layout, 'images'); ?>
	            </div>
	        </div>

        
       

   		<?php echo JHtml::_('bootstrap.endTab');?>
    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab3', JText::_('Detail')); ?>
    	<div class="control-group">
            <label class="control-label" for="intro"> <?php echo JText::_('COM_BOOKPRO_AIRPORT_INTRO')?>
            </label>
            <div class="controls">
                <?php
                    $editor =JFactory::getEditor();
                    echo $editor->display('intro', $this->obj->intro, '100%', '100', '50', '20', false);
                ?>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_AIRPORT_DESCRIPTION'); ?>
            </label>
            <div class="controls">
                <?php
                    $editor=JFactory::getEditor();
                    echo $editor->display('desc', $this->obj->desc, '100%', '400', '60', '20', false);?>
            </div>
        </div>
     <?php echo JHtml::_('bootstrap.endTab');?>
    <?php echo JHtml::_('bootstrap.endTabSet');?>

    <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
    <input type="hidden" name="controller" value="<?php echo CONTROLLER_AIRPORT; ?>"/>
    <input type="hidden" name="task" value="save"/>

    <input type="hidden" name="boxchecked" value="1"/>
    <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
    <!-- Use for display customers reservations -->

    <?php echo JHTML::_('form.token'); ?>
    </div>
</form>
