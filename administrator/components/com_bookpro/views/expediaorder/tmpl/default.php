<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 23 2012-07-08 02:20:56Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency','date');
BookProHelper::setSubmenu($set);
//JToolBarHelper::custom('batchupdate','save.png','save.png','Save');
JToolBarHelper::cancel();
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
	</script>
	<div class="span10">
	<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	<div class="form-horizontal">
	<?php 
	
	echo $this->loadTemplate(strtolower($this->order->type))?>
	<?php $this->addTemplatePath( JPATH_COMPONENT_BACK_END.DS.'views' . DS . 'customer' . DS . 'tmpl' );
 	// echo $this->loadTemplate('customer'); ?>
 	 </div>
 	 </form>
 	 </div>
	
