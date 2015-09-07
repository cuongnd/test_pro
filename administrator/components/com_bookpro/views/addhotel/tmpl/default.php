
<?php     
    defined('_JEXEC') or die('Restricted access');

    JHtml::_('behavior.calendar');
    JHtmlBehavior::modal('a.jbmodal');
    JHtml::_('behavior.formvalidation');     
    JToolBarHelper::title(JText::_('COM_BOOKPRO_ADD_HOTEL'), 'addhotel');     
    JToolBarHelper::apply();
    JToolBarHelper::save();
    JToolBarHelper::cancel();
    JHtml::_('jquery.framework');     
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

    <div class="form-horizontal">      
        <div class="control-group">       
                <?php echo $this -> data; ?>  
    </div>

    <input type="hidden" name="option" value="<?php echo OPTION; ?>" /> 
    <input type="hidden" name="controller" value="<?php echo CONTROLLER_ADDHOTEL; ?>" /> 
    <input type="hidden" name="task" value="save" /> 
    <input type="hidden" name="boxchecked" value="1" /> 
    <input type="hidden" name="tour_id" value="<?php echo $this->tour_id; ?>" id="tour_id" />
    <input type="hidden" name="itinerary_id" value="<?php echo $this->itinerary_id; ?>" id="itinerary_id" />
   <?php echo JHTML::_('form.token'); ?>       
</form>
