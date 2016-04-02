<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('textarea');

/**
 * Form Field class for the Joomla CMS.
 * A textarea field for content creation
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @see         JEditor
 * @since       1.6
 */
class afaJFormFieldEditor extends JFormFieldTextarea
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Editor';

	/**
	 * The JEditor object.
	 *
	 * @var    JEditor
	 * @since  1.6
	 */
	protected $editor;

	/**
	 * The height of the editor.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $height;

	/**
	 * The width of the editor.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $width;

	/**
	 * The assetField of the editor.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $assetField;

	/**
	 * The authorField of the editor.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $authorField;

	/**
	 * The asset of the editor.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $asset;

	/**
	 * The buttons of the editor.
	 *
	 * @var    mixed
	 * @since  3.2
	 */
	protected $buttons;

	/**
	 * The hide of the editor.
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $hide;

	/**
	 * The editorType of the editor.
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $editorType;

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
			case 'height':
			case 'width':
			case 'assetField':
			case 'authorField':
			case 'asset':
			case 'buttons':
			case 'hide':
			case 'editorType':
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
			case 'height':
			case 'width':
			case 'assetField':
			case 'authorField':
			case 'asset':
				$this->$name = (string) $value;
				break;

			case 'buttons':
				$value = (string) $value;

				if ($value == 'true' || $value == 'yes' || $value == '1')
				{
					$this->buttons = true;
				}
				elseif ($value == 'false' || $value == 'no' || $value == '0')
				{
					$this->buttons = false;
				}
				else
				{
					$this->buttons = explode(',', $value);
				}
				break;

			case 'hide':
				$value = (string) $value;
				$this->hide = $value ? explode(',', $value) : array();
				break;

			case 'editorType':
				// Can be in the form of: editor="desired|alternative".
				$this->editorType  = explode('|', trim((string) $value));
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
			$this->height      = $this->element['height'] ? (string) $this->element['height'] : '500';
			$this->width       = $this->element['width'] ? (string) $this->element['width'] : '100%';
			$this->assetField  = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
			$this->authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
			$this->asset       = $this->form->getValue($this->assetField) ? $this->form->getValue($this->assetField) : (string) $this->element['asset_id'];

			$buttons    = (string) $this->element['buttons'];
			$hide       = (string) $this->element['hide'];
			$editorType = (string) $this->element['editor'];

			if ($buttons == 'true' || $buttons == 'yes' || $buttons == '1')
			{
				$this->buttons = true;
			}
			elseif ($buttons == 'false' || $buttons == 'no' || $buttons == '0')
			{
				$this->buttons = false;
			}
			else
			{
				$this->buttons = !empty($hide) ? explode(',', $buttons) : array();
			}

			$this->hide        = !empty($hide) ? explode(',', (string) $this->element['hide']) : array();
			$this->editorType  = !empty($editorType) ? explode('|', trim($editorType)) : array();
		}

		return $result;
	}

	/**
	 * Method to get the field input markup for the editor area
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.6
	 */
	public function get_attribute_config()
	{
		return array(
			filter_field=>''
		);
	}

	protected function getInput()
	{
		// Get an editor object.

		$doc=JFactory::getDocument();
		JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		$doc->addScript(JUri::root().'/media/system/js/jquery.base64.js');
		$doc->addScriptNotCompile(JUri::root().'/ckfinder/ckfinder.js');
		$doc->addScriptNotCompile(JUri::root().'/media/editors/ckeditor/ckeditor.js');
		$doc->addScript(JUri::root().'/media/editors/ckeditor/adapters/jquery.js');
		$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.js");
		$doc->addScript(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/show-hint.js");
		$doc->addScript(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/html-hint.js");
		$doc->addScript(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/xml-hint.js");
		$doc->addStyleSheet(JUri::root() . "/media/editors/ckeditor/plugins/codemirror/addon/hint/show-hint.css");
		//$doc->addScript(JUri::root().'/media/editors/ckeditor/config.js');

		$doc->addScript(JUri::root().'/libraries/cms/form/field/editor.js');
		$doc->addLessStyleSheet(JUri::root() . '/libraries/cms/form/field/editor.less');
		if ( base64_encode(base64_decode($this->value)) === $this->value){

		} else {
			$this->value='';
		}
		$content=$this->value;
		$content=base64_decode($content);
		$data=$this->form->getData();
		$filter_field=$this->element['filter_field'];
		$binding_source=$data->get($filter_field,'');
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
		$dataSourceModal=JModelLegacy::getInstance('DataSource','phpMyAdminModel');
		$block_id=$data->get('id',0);
		$list_var=$dataSourceModal->list_field_by_data_source($binding_source,$block_id);

		//get menu select
		$db=JFactory::getDbo();
		$website=JFactory::getWebsite();
		$query=$db->getQuery(true);
		$query->from('#__menu As menu');
		$query->select('CONCAT("{",menu.alias,"}")');
		$query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
		$query->where('menuType.website_id=' . (int)$website->website_id);
		$query->where('menuType.client_id=0');
		$query->where('menu.alias!="root"');
		$query->order('menu.title');

		$db->setQuery($query);
		$list_menu=$db->loadColumn();
		$list_menu[]='{:this_host:}';
		//end get menu

		//get list style
		$list_style='';
		require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
		$db=JFactory::getDbo();
		$table_control=new JTableUpdateTable($db,'control');
		$table_control->load(array(
			"element_path"=>'libraries/cms/form/field/stylegenerator.php',
			'type'=>'field'
		));


		$fields=$table_control->fields;
		$fields=base64_decode($fields);
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		$list_style=array();
		$fields=JFormFieldEditor::get_list_style($fields,$list_style);


		//end get list style
		$scriptId = "script_field_editor_" . $data->get('id',0);
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready(function ($) {
				$('#field_editor_<?php echo $data->get('id',0) ?>').field_editor({
					input_name:"<?php echo $this->name ?>",
					list_var:<?php echo json_encode($list_var) ?>,
					list_menu:<?php echo json_encode($list_menu) ?>,
					list_style:<?php echo json_encode($list_style) ?>
				});


			});
		</script>
		<?php
		$script = ob_get_clean();
		$script = JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
		ob_start();
		$html='';
		?>
		<div>
			"Ctrl-Space": "autocomplete",
			"Ctrl-M": "list_menu",
			"Ctrl-E": "list_style",
			"Ctrl-B": "autocomplete1",
		</div>
		<div id="field_editor_<?php echo $data->get('id',0) ?>" >
			<textarea  class="editor"  cols="<?php echo  $this->cols ?>" rows="<?php echo $this->rows ?>"><?php echo $content ?></textarea>
			<input type="hidden" id="<?php echo $this->id ?>" name="<?php echo $this->name ?>" value="<?php echo $this->value ?>"/>

		</div>
		<?php
		$html=ob_get_clean();
		return $html;
	}

	/**
	 * Method to get a JEditor object based on the form field.
	 *
	 * @return  JEditor  The JEditor object.
	 *
	 * @since   1.6
	 */
	function get_list_style($fields,&$list_style=array(),$maxLevel = 9999, $level = 0)
	{
		if($level<=$maxLevel) {
			foreach ($fields as $item) {

				if (is_array($item->children) && count($item->children) > 0) {
					JFormFieldEditor::get_list_style($item->children,$list_style,$maxLevel,$level++);
				} else {
					$list_style[] =str_replace('_','-',$item->name);
				}
			}
		}

	}


	protected function getEditor()
	{
		// Only create the editor if it is not already created.
		if (empty($this->editor))
		{
			$editor = null;

			if ($this->editorType)
			{
				// Get the list of editor types.
				$types = $this->editorType;

				// Get the database object.
				$db = JFactory::getDbo();

				// Iterate over teh types looking for an existing editor.
				foreach ($types as $element)
				{
					// Build the query.
					$query = $db->getQuery(true)
						->select('element')
						->from('#__extensions')
						->where('element = ' . $db->quote($element))
						->where('folder = ' . $db->quote('editors'))
						->where('enabled = 1');

					// Check of the editor exists.
					$db->setQuery($query, 0, 1);
					$editor = $db->loadResult();

					// If an editor was found stop looking.
					if ($editor)
					{
						break;
					}
				}
			}

			// Create the JEditor instance based on the given editor.
			if (is_null($editor))
			{
				$conf = JFactory::getConfig();
				$editor = $conf->get('editor');
			}

			$this->editor = JEditor::getInstance($editor);
		}

		return $this->editor;
	}

	/**
	 * Method to get the JEditor output for an onSave event.
	 *
	 * @return  string  The JEditor object output.
	 *
	 * @since   1.6
	 */
	public function save()
	{
		return $this->getEditor()->save($this->id);
	}
}
