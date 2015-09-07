<?php

defined('_JEXEC') or die('Restricted access');




JToolBarHelper::title(JText::_(COMPONENT_NAME).": ".JText::_('Configuration'), 'configuration');

JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();

//	BookProHelper::setSubmenu(4);

ADocument::addDomreadyEvent('ViewConfig.setEvents(true);');

$array = $this->params->toArray();

$this->form = new JForm('form');

//load config and mask it as form
$xmlstr = file_get_contents(CONFIG); //Storing the xml file content to $xmlstr

$xmlstr = str_replace('<config','<form',$xmlstr);
$xmlstr = str_replace('</config>','</form>',$xmlstr);

//$xmlstr = str_replace(' name="',' name="params',$xmlstr);
$xmlstr = str_replace('<fields name="config">','<fields name="params">',$xmlstr);

$xml = new SimpleXMLElement($xmlstr);

$this->form->load($xml);
//$this->form->bind($array);
foreach($array as $k=>$v)
{
	$this->form->setValue($k, 'params', $v);
}
$this->form->setValue('asset_id', null, $array['asset_id']);

?>
<div class="main-subhead">

</div>
<div class="span10">
<form action="index.php" method="post" name="adminForm" id="adminForm">	
	<div>
	
	
			<?php
				echo JHtml::_('tabs.start', 'tabone', array('useCookie' => true));
				foreach ($this->form->getFieldsets() as $name=>$fieldset) {
					
									
					echo JHtml::_('tabs.panel', JText::_($fieldset->label), $name);
			?>
				<div class="ieHelper">&nbsp;</div>
				<div>
				<table class="admintable config">
			<?php
					foreach ($this->form->getFieldset($fieldset->name) as $field) {
						/* @var $field JFormField */
						if ($field->__get('labelClass') == 'hide') {
			?>
							<tr id="<?php echo $field->id;?>_tr">
								<td colspan="2">
									<?php echo $field->input; ?>
								</td>
							</tr>		
			<?php		
						} else { 
			?> 
							<tr id="<?php echo $field->id;?>_tr">
								<td valign="top" class="key">
									<?php echo $field->label; ?>
								</td>
								<td>
									<?php echo $field->input; ?>
								</td>
							</tr>
		  	<?php 
		  				}
			        }   
			?>
				</table>
				</div>
			<?php	
				}
				echo JHtml::_('tabs.end');
			?>
			<div class="clr"></div>

	</div>
	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CONFIG; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>
</form>
</div>