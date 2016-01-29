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
class JFormFieldFieldNamebindingsourceselect2 extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'fieldnamebindingsourceselect2';

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
	protected function getInput()
	{
		$doc=JFactory::getDocument();
		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size         = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
		$class='form-control';
		$class        = !empty($this->class) ? $class.' '.$this->class: $class;
		$class='class="'.$class.'"';
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
		$maximumSelectionLength=$this->element['maximumSelectionLength'] ? (string) $this->element['maximumSelectionLength'] : 10;
		$maximumSelectionSize=$this->element['maximumSelectionSize'] ? (string) $this->element['maximumSelectionSize'] : 10;
		$tags=$this->element['tags']==1 ?true : false;
		$selectOPtion=array(
			maximumSelectionLength=>$maximumSelectionLength,
			maximumSelectionSize=>$maximumSelectionSize
		);
		$doc->addStyleSheet(JUri::root().'/media/jui_front_end/css/select2.css');
		$doc->addScript(JUri::root().'/media/jui_front_end/js/select2.jquery.js');
		$doc->addScript(JUri::root().'/libraries/joomla/form/fields/fieldnamebindingsourceselect2.js');
		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		// Including fallback code for HTML5 non supported browsers.
		JHtml::_('jquery.framework');
		JHtml::_('script', 'system/html5fallback.js', false, true);
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
		$dataSourceModal=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
		$currentDataSource=$dataSourceModal->getCurrentDataSources();
		$template_list=array();
		$a_item=new stdClass();
		$a_item->id='';
		$a_item->text="None";
		$options[]=$a_item;
		$data=$this->form->getData();
		foreach($currentDataSource as $item){
			foreach($item->listField as $key=> $field){
				if($template_list[$key]!=1) {
					$a_item = new stdClass();
					$a_item->id = "{$key}";
					$a_item->text = "{$key}";
					$options[] = $a_item;


					$template_list[$key]=1;
				}
			}
		}



		//get list button
		$app=JFactory::getApplication();
		$menu=$app->getMenu();
		$menu_active=$menu->getActive();
		$website=JFactory::getWebsite();
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('position.*')
			->from('#__position_config AS position')
			->where('position.website_id='.(int)$website->website_id)
			->where('position.type='.$query->q('button'))
			->where('position.menu_item_id='.(int)$menu_active->id);
		$lits_button=$db->setQuery($query)->loadObjectList();
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		foreach($lits_button as $button)
		{
			$params = new JRegistry;
			$params->loadString($button->params);
			$config_update=$params->get('config_update_data');
			$config_update = up_json_decode($config_update, false, 512, JSON_PARSE_JAVASCRIPT);
			JFormFieldFieldNamebindingsourceselect2::tree_node_config_update($config_update,$options);
		}

		$selectOPtion['tags']=$options;
		$selectOPtion['width']= 'resolve';
		//end get list style
		$scriptId = "script_field_fieldnamebindingsourceselect2_" . JUserHelper::genRandomPassword();
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('#field_fieldnamebindingsourceselect2_<?php echo $data->get('id',0) ?>').field_fieldnamebindingsourceselect2({
					select2_option:<?php echo json_encode($selectOPtion) ?>,
					field_name:"<?php echo $this->name ?>"
				});


			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);




		$html='';
		ob_start();
		?>
		<div id="field_fieldnamebindingsourceselect2_<?php echo $data->get('id',0) ?>">
			<input style="width:250px" value="<?php echo htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') ?>"  name="<?php echo $this->name ?>" class=""   />
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
	public function tree_node_config_update($nodes,&$list_post=array())
	{
		foreach ($nodes as $node) {
			$post_name=$node->post_name;
			if($post_name!='') {
				$a_item = new stdClass();
				$a_item->id = $post_name;
				$a_item->text = $post_name;
				$list_post[] = $a_item;
			}
			if(is_array($node->children)&&count($node->children))
			{
				JFormFieldFieldNamebindingsourceselect2::tree_node_config_update($node->children,$list_post);
			}
		}
	}
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
