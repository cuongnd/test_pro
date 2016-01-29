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
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldAutocompletetext extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'autocompletetext';

	/**
	 * The allowable maxlength of the field.
	 *
	 * @var    integer
	 * @since  3.2
	 */
	protected $maxLength;

	/**
	 * The mode of input associated with the field.
	 *
	 * @var    mixed
	 * @since  3.2
	 */
	protected $inputmode;

	/**
	 * The name of the form field direction (ltr or rtl).
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $dirname;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.2
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'maxLength':
			case 'dirname':
			case 'inputmode':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'maxLength':
				$this->maxLength = (int) $value;
				break;

			case 'dirname':
				$value = (string) $value;
				$value = ($value == $name || $value == 'true' || $value == '1');

			case 'inputmode':
				$this->name = (string) $value;
				break;

			default:
				parent::__set($name, $value);
		}
	}
	public function get_attribute_config()
	{
		return array(
			filter_field=>''
		);
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   3.2
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{
			$inputmode = (string) $this->element['inputmode'];
			$dirname = (string) $this->element['dirname'];

			$this->inputmode = '';
			$inputmode = preg_replace('/\s+/', ' ', trim($inputmode));
			$inputmode = explode(' ', $inputmode);

			if (!empty($inputmode))
			{
				$defaultInputmode = in_array('default', $inputmode) ? JText::_("JLIB_FORM_INPUTMODE") . ' ' : '';

				foreach (array_keys($inputmode, 'default') as $key)
				{
					unset($inputmode[$key]);
				}

				$this->inputmode = $defaultInputmode . implode(" ", $inputmode);
			}

			// Set the dirname.
			$dirname = ((string) $dirname == 'dirname' || $dirname == 'true' || $dirname == '1');
			$this->dirname = $dirname ? $this->getName($this->fieldname . '_dir') : false;

			$this->maxLength = (int) $this->element['maxlength'];
		}

		return $result;
	}

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size         = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
		$class        = !empty($this->class) ? ' class=" form-control typeahead' . $this->class . '"' : 'class="form-control typeahead"';
		$readonly     = $this->readonly ? ' readonly' : '';
		$disabled     = $this->disabled ? ' disabled' : '';
		$required     = $this->required ? ' required aria-required="true"' : '';
		$hint         = $hint ? ' placeholder="' . $hint . '"' : '';
		$autocomplete = !$this->autocomplete ? ' autocomplete="off"' : ' autocomplete="' . $this->autocomplete . '"';
		$autocomplete = $autocomplete == ' autocomplete="on"' ? '' : $autocomplete;
		$autofocus    = $this->autofocus ? ' autofocus' : '';
		$spellcheck   = $this->spellcheck ? '' : ' spellcheck="false"';
		$pattern      = !empty($this->pattern) ? ' pattern="' . $this->pattern . '"' : '';
		$inputmode    = !empty($this->inputmode) ? ' inputmode="' . $this->inputmode . '"' : '';
		$dirname      = !empty($this->dirname) ? ' dirname="' . $this->dirname . '"' : '';
		$binding_source_name=$this->element['filter_field'];
		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);

		$datalist = '';
		$list     = '';

		/* Get the field options for the datalist.
		Note: getSuggestions() is deprecated and will be changed to getOptions() with 4.0. */
		$doc=JFactory::getDocument();


		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
		$dataSourceModal=JModelLegacy::getInstance('DataSource','phpMyAdminModel');
		$data=$this->form->getData();
		$binding_source=$data->get($binding_source_name,'');
		$block_id=$data->get('id',0);
		$list_field=$dataSourceModal->list_field_by_data_source($binding_source,$block_id);
		$app=JFactory::getApplication();
		$input=$app->input;
		$list_input=$input->getArray();

		$doc->addScript(JUri::root().'/media/system/js/twitter-typeahead.js/dist/typeahead.jquery.js');
		$doc->addScript(JUri::root().'/libraries/joomla/form/fields/autocompletetext.js');

		$doc->addStyleSheet(JUri::root() . '/libraries/joomla/form/fields/autocompletetext.less','text/css','',array('rel'=>'stylesheet/less'));
		$doc->addScript(JUri::root().'/media/Kendo_UI_Professional_Q2_2015/src/build/less-js/dist/less-1.5.0.js');

		$scriptId="lib_joomla_form_fields_autocomplete_text".JUserHelper::genRandomPassword();
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($){

				$('input[name="<?php echo $this->name ?>"]').autocomplete_text({
					data:<?php echo json_encode($list_field) ?>,
					binding_source_name:"<?php echo $binding_source_name ?>",
					block_id:<?php echo $block_id ?>,
					list_input:<?php echo json_encode($list_input) ?>
				});
			});
		</script>
		<?php
		$script_content=ob_get_clean();
		$script_content=JUtility::remove_string_javascript($script_content);
		$doc->addScriptDeclaration($script_content, "text/javascript", $scriptId);

		$html[] = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . $dirname . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $list
			. $hint . $onchange . $maxLength . $required . $autocomplete . $autofocus . $spellcheck . $inputmode . $pattern . ' />';
		$html[] = $datalist;

		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   3.4
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

			// Create a new option object based on the <option /> element.
			$options[] = JHtml::_(
				'select.option', (string) $option['value'],
				JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text'
			);
		}

		return $options;
	}

	/**
	 * Method to get the field suggestions.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since       3.2
	 * @deprecated  4.0  Use getOptions instead
	 */
	protected function getSuggestions()
	{
		return $this->getOptions();
	}
}
