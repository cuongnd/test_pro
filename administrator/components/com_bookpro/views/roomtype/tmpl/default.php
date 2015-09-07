<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 84 2012-08-17 07:16:08Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.html.html.select');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::title(JText::_('COM_BOOKPRO_ROOMTYPE_EDIT'),'pencil-2');
JHtml::_('behavior.formvalidation');
AImporter::helper('image');
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
            alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>
            ');
            return false;
        }
    }
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

    <div class="form-horizontal">
        <div class="control-group">
            <label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_ROOMTYPE_TITLE'); ?></label>
            <div class="controls">
                <input class="text_area" type="text" name="title" id="title"
                       size="60" maxlength="255"
                       value="<?php echo $this->obj->title; ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="max_person"><?php echo JText::_('COM_BOOKPRO_ROOMTYPE_MAX_PERSON'); ?>
            </label>
            <div class="controls">
                <input class="text_area required" type="text" name="max_person" id="max_person" size="60" maxlength="255" value="<?php echo $this->obj->max_person; ?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="max_children"><?php echo JText::_('COM_BOOKPRO_ROOMTYPE_MAX_CHILDREN'); ?>
            </label>
            <div class="controls">
                <input class="text_area required" type="text" name="max_children" id="max_children" size="60" maxlength="255" value="<?php echo $this->obj->max_children; ?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label" for="max_extra_bed"><?php echo JText::_('COM_BOOKPRO_ROOMTYPE_MAX_EXTRA_BED'); ?>
            </label>
            <div class="controls">
                <input class="text_area required" type="text" name="max_extra_bed" id="max_extra_bed" size="60" maxlength="255" value="<?php echo $this->obj->max_extra_bed; ?>" />
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="image"><?php echo JText::_('COM_BOOKPRO_ROOM_MAIN_IMAGE'); ?>
            </label>
            <div class="controls">
                <?php
                $this->image = $this->obj->image;
                AImporter::tpl('images', $this->_layout, 'image');
                ?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_ROOM_DESCRIPTION'); ?>
            </label>
            <div class="controls">
               <textarea rows="5" cols="50"><?php echo $this->obj->desc ?></textarea>
            </div>
        </div>



        <div class="control-group">
            <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_STATE') ?>
            </label>
            <div class="form-inline">
                <?php echo JHtmlSelect::booleanlist('state', '', $this->obj->state, 'Active', 'Inactive', 'state') ?>
            </div>
        </div>


    </div>

    <input type="hidden" name="option" value="<?php echo OPTION; ?>" />
    <input type="hidden" name="controller"	value="<?php echo CONTROLLER_ROOMTYPE; ?>" />
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="boxchecked" value="1" />
    <input type="hidden" name="cid[]"	value="<?php echo $this->obj->id; ?>" id="cid" />

    <?php echo JHTML::_('form.token'); ?>
</form>

