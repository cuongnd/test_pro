<?php 
    defined( '_JEXEC' ) or die( 'Restricted access' );
    $doc = JFactory::getDocument();
    $action=JURI::base().'index.php?option=com_bookpro&controller=customer&task=bplogin';
?>
<div class="span6">

<?php $this->addTemplatePath( JPATH_COMPONENT_FRONT_END_SITE.'/views/login/tmpl' );
	echo $this->loadTemplate('login') ?>
</div>



