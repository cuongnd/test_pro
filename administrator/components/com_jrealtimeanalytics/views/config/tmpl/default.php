<?php 
/** 
 * @package JREALTIMEANALYTICS::CONFIG::administrator::components::com_jrealtimeanalytics
 * @subpackage views
 * @subpackage config
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?> 
<script type="text/javascript">
//<![CDATA[ 
  jQuery(function($) { 
		$('a[data-toggle="tab"]', '#adminForm').on('shown', function (e) { 
			var selectedTab = $(this).attr('data-cookie');
			Cookie.write('jrealtimeanalytics_tab_config', selectedTab);
		});
	}); 
//]]>
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">  
	 <?php 
		//API nuova JForm da config.xml con fields personalizzati in sostituzione di JElement 	  
	$fieldSets = $this->params_form->getFieldsets();
	$tabs = array();
	$contents = array();
	foreach ($fieldSets as $name => $fieldSet) :
		$label = JText::_(empty($fieldSet->label) ? 'COM_CONFIG_'.$name.'_FIELDSET_LABEL' : $fieldSet->label); 
		$activeTab = $this->app->input->getString('jrealtimeanalytics_tab_config', 'preferenze');
		$activeClass = $fieldSet->id === $activeTab ? 'class="active"' : null;
		$activeClassContent = $fieldSet->id === $activeTab ? 'class="tab-pane active"' : 'class="tab-pane"';
		$tabs[] = "<li $activeClass><a href='#$fieldSet->id' data-toggle='tab' data-cookie='$fieldSet->id'>$label</a></li>";
		ob_start(); ?>
		<div <?php echo $activeClassContent;?> id="<?php echo $fieldSet->id;?>" class="tab-pane">
		<?php  
		foreach ($this->params_form->getFieldset($name) as $field):?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?></div>
				<div class="controls"><?php echo $field->input; ?></div>
			</div>
		<?php endforeach; ?>
		</div>
		<?php $contents[] = ob_get_clean();?>
	<?php endforeach; ?>
		
	<ul class="nav nav-tabs"><?php echo implode('', $tabs);?></ul>
	<div id="config-realtimeanalytics" class="tab-content"><?php echo implode('', $contents);?></div> 
	
	<input type="hidden" name="option" value="<?php	echo JRequest::getVar('option');?>" /> 
	<input type="hidden" name="task" value="" />
</form> 