<?php 
    defined( '_JEXEC' ) or die( 'Restricted access' );
    $doc = JFactory::getDocument();
    $action=JURI::base().'index.php?option=com_bookpro&controller=customer&task=bplogin';
    $action_show_order=JURI::base().'index.php?option=com_bookpro&controller=customer&task=show_order';
?>
<div class="span12">
<?php echo $this->loadTemplate('login')?>
</div>



