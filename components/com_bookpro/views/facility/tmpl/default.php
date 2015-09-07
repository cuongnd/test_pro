<?php     


    defined('_JEXEC') or die('Restricted access');
    JHtml::_('jquery.framework');
    JHtml::_('behavior.formvalidation');  	
    AImporter::helper('hotel');

    /* validate using jquery validate plugin */
    $lang=JFactory::getLanguage();
    $local=substr($lang->getTag(),0,2);
    $document = JFactory::getDocument();
    $document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
    if($local !='en'){
        $document->addScript(JURI::root().'components/com_bookpro/assets/js/validatei18n/messages_'.$local.'.js');
    }
    JHtml::_('behavior.modal','a.jbmodal');
    AImporter::helper('image');
    AImporter::css('general');
    AImporter::js('view-images','common');
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
                    echo $this->obj->id ? JText::_('COM_BOOKPRO_EDIT_ROOM'): JText::_('COM_BOOKPRO_ADD_FACILITY');
                ?>                               
            </legend>

            <form action="index.php" method="post" name="adminForm" id="roomForm" enctype="multipart/form-data" class="form-validate">    		
                <div class="form-horizontal"> 

                <div class="control-group">
                    <label class="control-label" for="hotels"><?php echo JText::_('COM_BOOKPRO_HOTEL'); ?>
                    </label>
                    <div class="controls">
                        <?php
                            echo $this->hotels;

                        ?> 
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('ftype'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('ftype'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('price'); ?></div>
                </div>               

                <div class="control-group">
                    <label class="control-label" for="images"><?php echo JText::_('COM_BOOKPRO_IMAGE'); ?>
                    </label>
                    <div class="controls">
                        <?php echo $this->form->getInput('image'); ?>
                        
                    </div>
                </div>

                <div class="center-button span5">
                    <input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>"/>         

                    <?php $linkr = ARoute::view('facilities',null,null,array('hotel_id'=>JRequest::getVar('hotel_id'), 'Itemid'=>JRequest::getVar('Itemid')));?>
                    <a href="<?php echo $linkr;?>" title="<?php echo JText::_('COM_BOOKPRO_ROOM_MANAGER');?>">
                        <input type="button" class="btn" name="cancel" value="<?php echo JText::_('COM_BOOKPRO_CANCEL');?>"/>
                    </a>
                </div>       
                <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
                <input type="hidden" name="controller" value="facility"/>
                <input type="hidden" name="task" value="save"/>
                <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" id="id"/>
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>    
                <?php echo JHTML::_('form.token'); ?>
            </form>

        </fieldset>        
    </div>
</div>  
<style type="text/css">
    fieldset { overflow:hidden }
    .some-class { float:left; clear:none; }
    .some-class label { float:left; clear:none; display:block; padding: 2px 1em 0 0;
        padding-left:0
    }
    .some-class input[type=radio], .some-class input.radio { float:left; clear:none; margin: 2px 5px 0 2px; }
</style>
<script type="">
    jQuery(document).ready(function(){
        $counter = 0; // initialize 0 for limitting textboxes

        jQuery('#child').change(function(){
            jQuery('#childtb').html(""); // when the dropdown change set the div to empty
            $loopcount = jQuery(this).val(); // get the selected value
            for (var i = 1; i <= $loopcount; i++)
            {
                jQuery('#childtb').append('<div class="control-group" id="input_text"><div id="title_name" class="control-label"><?php echo JText::_("COM_BOOKPRO_CHILD_");?>'+i+' <?php echo JText::_("COM_BOOKPRO_PRICE");?></div><div id="input_tx" class="controls"><input type="text" name="child_price[]" class="textbox2" value="" /></div><div class="clear"></div></div>');
            }
        });
    });
</script>

<script type="text/javascript">
    jQuery(document).ready(function($){

        $("#roomForm").validate({
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
                           
