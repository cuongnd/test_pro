<?php     


    defined('_JEXEC') or die('Restricted access');
    JHtml::_('jquery.framework');
    JHtml::_('behavior.formvalidation');  	
    AImporter::helper('hotel');
    /* upload image*/
    JHtml::_('behavior.modal','a.jbmodal');
    AImporter::helper('image');
    AImporter::css('general');
    AImporter::js('view-images','common');
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
                    echo $this->obj->id ? JText::_('COM_BOOKPRO_EDIT_ROOM'): JText::_('COM_BOOKPRO_ADD_ROOM');
                ?>                               
            </legend>

            <form action="index.php" method="post" name="adminForm" id="roomForm" class="form-validate">    		
                <div class="form-horizontal"> 
                    <?php echo JHtml::_('bootstrap.startTabSet', 'myTab',array('active'=>'tab1'));?> 
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab1', JText::_('Basic')); ?> 

                    <div class="control-group">
                        <label class="control-label" for="room_type"><?php echo JText::_('COM_BOOKPRO_HOTEL'); ?>
                        </label>
                        <div class="controls">
                            <?php
                                echo $this->hotels;
                            ?>
                        </div>
                    </div>                      

                    <div class="control-group">
                        <label class="control-label" for="room_type"><?php echo JText::_('COM_BOOKPRO_ROOM_TYPE_NAME'); ?>
                        </label>
                        <div class="controls">
                            <input class="text_area required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
                        </div>
                    </div>            

                    <div class="control-group">
                        <label class="control-label" for="room_type"><?php echo JText::_('COM_BOOKPRO_NO_OF_ADULT'); ?>
                        </label>
                        <div class="controls">
                            <?php
                                echo JHtmlSelect::integerlist(1, 10, 1, 'adult', 'id="adult"', $this->obj->adult);
                            ?>
                            <span style="color:blue; cursor:pointer; margin-left: 10px;" onclick="addNewAtt()"  id="files"><?php echo JText::_('COM_BOOKPRO_ALLOW_MORE_ADULTS_WITH_ADDITIONAL_PRICE'); ?></span>
                        </div> 
                    </div>


                    <?php
                        echo $this->adult_price;
                    ?>
                    <input type="hidden" class="attachBA" name=""/>
                    <script type="text/javascript">        
                        j = <?php echo $this->adultnumber; ?>;
                        function addNewAtt()
                        {
                            j++;
                            jQuery('<div class="control-group divAtt'+j+'" id="input_text"><div id="title_name" class="control-label"><?php echo JText::_("COM_BOOKPRO_ADULT_");?>'+j+' <?php echo JText::_("COM_BOOKPRO_PRICE");?></div><div id="input_tx" class="controls"><input type="text" name="adult_price[]" class="textbox2" value=""/><span class="attachB" onClick="removeAtt(\''+j+'\')" style="color: blue; cursor: pointer; margin-left: 10px;"><?php echo JText::_('COM_BOOKPRO__TO_REMOVE_ADULT'); ?></span></div><div class="clear"></div></div>').insertBefore('.attachBA');
                        }

                        function removeAtt(index)
                        {
                            jQuery(".divAtt"+index).remove();
                        }
                        function _delete(id)
                        {
                            jQuery('#'+id).remove();
                            jQuery('#att_check').val('delete');
                        }
                    </script>


                    <div class="control-group">
                        <label class="control-label" for="room_type"><?php echo JText::_('COM_BOOKPRO_NO_OF_CHILD'); ?>
                        </label>
                        <div class="controls">
                            <?php
                                echo JHtmlSelect::integerlist(0, 10, 1, 'child', 'id="child"', $this->obj->child);
                            ?>
                        </div>
                    </div>                                

                    <div id="childtb">
                        <?php
                            echo $this->child_price;
                        ?>
                    </div>                

                    <div class="control-group">
                        <label class="control-label" for="room_type"><?php echo JText::_('COM_BOOKPRO_MAXIMUM_AGE_ALLOW_FOR_CHILDREN'); ?>
                        </label>
                        <div class="controls">
                            <?php
                                echo JHtmlSelect::integerlist(1, 17, 1, 'child_age', 'id="child_age"', $this->obj->child_age);
                            ?>
                        </div>
                    </div>                 

                    <!--				<div class="control-group">
                    <label class="control-label" for="roomlabel_id"><?php echo JText::_('COM_BOOKPRO_ROOM_LABEL'); ?>
                    </label>
                    <div class="controls">
                    <?php //echo $this->roomlabels;?>
                    </div>
                    </div>		-->		

                    <div class="control-group">
                        <label class="control-label" for="quantity"><?php echo JText::_('COM_BOOKPRO_ROOM_TOTAL'); ?>
                        </label>
                        <div class="controls">
                            <input class="text_area required" type="text" name="quantity" id="quantity" size="60" maxlength="255" value="<?php echo $this->obj->quantity; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="price_weekday"><?php echo JText::_('COM_BOOKPRO_PRICE_WEEKDAY'); ?>
                        </label>
                        <div class="controls">
                            <input class="text_area required" type="text" name="price_weekday" id="price_weekday" size="60" maxlength="255" value="<?php echo $this->obj->price_weekday; ?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="price_weekend"><?php echo JText::_('COM_BOOKPRO_PRICE_WEEKEND'); ?>
                        </label>
                        <div class="controls">
                            <input class="text_area required" type="text" name="price_weekend" id="price_weekend" size="60" maxlength="255" value="<?php echo $this->obj->price_weekend; ?>" />
                        </div>
                    </div>				

                    <div class="control-group">
                        <label class="control-label" for="images"><?php echo JText::_('COM_BOOKPRO_ROOM_MAIN_IMAGE'); ?>
                        </label>
                        <div class="controls">
                            <?php  AImporter::tpl('images', 'form', 'images',SITE_VIEWS); ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="hotelfacility"><?php echo JText::_('COM_BOOKPRO_ROOM_FACILITIES'); ?>
                        </label>
                        <div class="controls">
                            <?php echo $this->roomfacility ?>
                        </div>
                    </div>                           



                    <div class="control-group">
                        <label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_ROOM_DESCRIPTION'); ?>
                        </label>
                        <div class="controls">
                            <?php
                                $editor =& JFactory::getEditor();
                                echo $editor->display('desc', $this->obj->desc, '550', '200', '60', '20', false);
                            ?>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATE'); ?>
                        </label>
                        <div class="controls">
                            <div class="form-inline">
                                <input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->obj->state == 1) echo 'checked="checked"'; ?>/>
                                <label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?></label>
                                <input type="radio" class="inputRadio" name="state" value="0" id="state_inactive" <?php if ($this->obj->state == 0) echo 'checked="checked"'; ?>/>
                                <label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?></label>
                            </div>
                        </div>
                    </div>
                    <?php echo JHtml::_('bootstrap.endTab');?> 
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab2', JText::_('COM_BOOKPRO_HOTEL_FACILITY')); ?> 
                    <?php echo $this->facilities ?>
                    <?php echo JHtml::_('bootstrap.endTab');?>
                    <?php echo JHtml::_('bootstrap.endTabSet');?>

                </div>    	

                <div class="center-button span5">
                    <input type="submit" class="btn btn-primary" name="submit" id="submit" value="<?php echo JText::_('COM_BOOKPRO_SUBMIT');?>"/>         

                    <?php $linkr = ARoute::view('rooms',null,null,array('hotel_id'=>JRequest::getVar('hotel_id'), 'Itemid'=>JRequest::getVar('Itemid')));?>
                    <a href="<?php echo $linkr;?>" title="<?php echo JText::_('COM_BOOKPRO_ROOM_MANAGER');?>">
                        <input type="button" class="btn" name="cancel" value="<?php echo JText::_('COM_BOOKPRO_CANCEL');?>"/>
                    </a>
                </div>       

                <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
                <input type="hidden" name="controller" value="<?php echo CONTROLLER_ROOM; ?>"/>
                <input type="hidden" name="task" value="save"/>
                <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
                <input type="hidden" name="hotel_id" value="<?php echo $this->hotel_id;?>"/>
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar(Itemid);?>" id="Itemid"/>    
                <?php echo JHTML::_('form.token'); ?>
            </form>

        </fieldset>        
    </div>
</div>  
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
                           
