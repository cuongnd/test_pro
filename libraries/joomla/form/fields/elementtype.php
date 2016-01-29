<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldElementType extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'elementtype';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		JHtml::_('jquery.framework');
		$html = array();
		$attr = '';
		JHtml::_('formbehavior.chosen', 'select',null,array(),JUserHelper::genRandomPassword());
		$doc=JFactory::getDocument();
		$data=$this->form->getData();


		$type= $data->get('type','');
		$ui_path= $data->get('ui_path','');
		$scriptId='lib_joomla_form_fields_element_type';
		ob_start();
		?>
		<script type="text/javascript" id="<?php echo $scriptId ?>">
			<?php
				ob_get_clean();
				ob_start();
			?>

			<?php
			 $script=ob_get_clean();
			 ob_start();
			  ?>
		</script>
		<?php
		ob_get_clean();
		$doc->addScriptDeclaration($script,"text/javascript",$scriptId);


		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$attr .= $this->multiple ? ' multiple' : '';
		$attr .= $this->required ? ' required aria-required="true"' : '';
		$attr .= $this->autofocus ? ' autofocus' : '';

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';
		jimport('joomla.filesystem.folder');
		$listFolder=JFolder::folders(JPATH_ROOT.'/media/elements');

		$option=array();
		$options[] = JHTML::_('select.option', "","Select element");
		foreach($listFolder as $folder)
		{
			$options[] = JHTML::_('select.option', '<OPTGROUP>',$folder);
			$listElement=JFolder::files(JPATH_ROOT.'/media/elements/'.$folder,'.php');
			foreach($listElement as $element)
			{
				$current_ui_path='/media/elements/'.$folder.'/'.$element;
				$element=str_replace('.php','',$element);
				$options[] = JHTML::_('select.option', $current_ui_path,$element);
			}


		}
		// Merge any additional options in the XML definition.
		return JHtml::_('select.genericlist', $options, $this->name,  trim($attr),'value','text', $ui_path);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
}
