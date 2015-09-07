<?php 
/** 
 * @package JCHAT::CONFIG::administrator::components::com_jchat
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
			Cookie.write('jchat_tab_config', selectedTab);
		});
	}); 
//]]>
</script>

<form action="index.php" method="post"  id="adminForm"  name="adminForm">  
	<?php 
	$fieldSets = $this->params_form->getFieldsets();
	$tabs = array();
	$contents = array();
	foreach ($fieldSets as $name => $fieldSet) :
		$label = empty($fieldSet->label) ? 'COM_JCHAT_'. strtoupper($name) .'_FIELDSET_LABEL' : $fieldSet->label;
		$activeTab = $this->app->input->getString('jchat_tab_config', 'general');
		$activeClass = $fieldSet->id === $activeTab ? 'class="active"' : null;
		$activeClassContent = $fieldSet->id === $activeTab ? 'class="tab-pane active"' : 'class="tab-pane"';
		$tabs[] = "<li $activeClass><a href='#$fieldSet->id' data-toggle='tab' data-cookie='$fieldSet->id'>" . JText::_($label) . "</a></li>";
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
	<div id="config-jchat" class="tab-content"><?php echo implode('', $contents);?></div> 
	<input type="hidden" name="option" value="<?php	echo JRequest::getVar('option');?>" /> 
	<input type="hidden" name="task" value="" />
</form> 