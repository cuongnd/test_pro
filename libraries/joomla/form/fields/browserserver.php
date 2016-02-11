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
class JFormFieldBrowserServer extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'BrowserServer';

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
	public function get_attribute_config()
	{
		return array(
			get_image_type=>''
		);
	}
	protected function getInput()
	{
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size         = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
		$class        = !empty($this->class) ? ' class="' . $this->class . ' form-control"' : 'class="form-control"';


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
		$get_image_type= $this->element['get_image_type']?$this->element['get_image_type']:'url';
		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		$doc=JFactory::getDocument();
		$doc->addScriptNotCompile(JUri::root().'/ckfinder/ckfinder.js');
		$doc->addScriptNotCompile(JUri::root().'/ckfinder/config.js');

		$datalist = '';
		$list     = '';
		$uri=JFactory::getURI();
		require_once JPATH_ROOT.'/libraries/joomla/user/helper.php';
		$scriptId = "libraries_joomla_form_fields_browserserver_".JUserHelper::genRandomPassword();
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$(document).on('click','.browser-server',function(){
					data_object_id=$(this).closest('.properties').attr('data-object-id');
					var finder = new CKFinder();

					finder.basePath = '<?php echo $uri->toString().'/images/stories/' ?>';
					inputName=$(this).attr('data-input-name');

					finder.selectActionFunction = function(fileUrl){
						<?php if($get_image_type=='url'){ ?>
						fileUrl='url('+fileUrl+')';
						<?php } ?>
						$('input[name="'+inputName+'"]').val(fileUrl);
					};
					finder.popup();
				});



			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
		$html='';
		ob_start();

		/* Get the field options for the datalist.
		Note: getSuggestions() is deprecated and will be changed to getOptions() with 4.0. */
		?>

		<div class="input-group">
			<input type="text" name="<?php echo $this->name ?>" id="<?php echo $this->id ?>" <?php echo $class ?> value="<?php echo htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8')  ?>"  />
			<span class="input-group-btn">
				<button data-type="<?php echo $this->type ?>" data-input-name="<?php echo $this->name ?>" data-field="<?php echo $this->fieldname ?>" class="btn btn-primary browser-server" type="button">Browser server</button>
			</span>
		</div>
			<?php
		$html=ob_get_clean();
		return $html;
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
