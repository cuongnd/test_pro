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
 * Provides radio button inputs
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/command.radio.html#command.radio
 * @since       11.1
 */
class JFormFieldRadioYesNo extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'RadioYesNo';

	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$html = '';
		// Initialize some field attributes.
	 	$class     = !empty($this->class) ? ' class="radio ' . $this->class . ' noStyle"' : ' class="radio noStyle"';
		$required  = $this->required ? ' required aria-required="true"' : '';
		$autofocus = $this->autofocus ? ' autofocus' : '';
		$disabled  = $this->disabled ? ' disabled' : '';
		$readonly  = $this->readonly;
		$doc=JFactory::getDocument();
		$doc->addScript(JUri::root() . '/media/system/js/jQuery-Toggle-Button-Plugin-For-Bootstrap-Bootstrap-Checkbox/js/bootstrap-checkbox.js');
		$scriptId = "script_libraries_joomla_form_fields_radioyesno" . '_' . $this->name;
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('input[name="<?php echo $this->name ?>"]').checkboxpicker({

				}).change(function() {
					<?php echo $this->onchange ?>;
				});

			});
		</script>
		<?php
		$script=ob_get_clean();
		$script=JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
		$this->value=JUtility::toStrictBoolean($this->value);
		ob_start();

		?>
		<input <?php echo $class ?>   <?php echo $this->value?'checked':'' ?> type="checkbox" id="<?php echo $this->id ?>" name="<?php echo $this->name ?>">



		<?php
		$html=ob_get_clean();
		return $html;
	}

	/**
	 * Method to get the field options for radio buttons.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			$disabled = (string) $option['disabled'];
			$disabled = ($disabled == 'true' || $disabled == 'disabled' || $disabled == '1');

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
				$disabled
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];
			$tmp->onchange = (string) $option['onchange'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
