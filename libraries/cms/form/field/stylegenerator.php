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
class JFormFieldStyleGenerator extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'stylegenerator';

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
	public function get_attribute_config()
	{
		return array(
			element_apply_style=>''
		);
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
	function stree_node_xml($fields,$block_id=0,$key_path='',$indent='',$form,$registry_item_form,$maxLevel = 9999, $level = 0)
	{
		if($level<=$maxLevel)
		{

			?>
			<div class="panel-group list_css_property" id="accordion<?php echo $indent ?>" role="tablist" aria-multiselectable="true">
				<?php
				$i=0;
				foreach ($fields as $item) {
					$indent1= $indent!=''?$block_id.'_'.$indent.'_'.$i:$block_id.'_'.$i;
					$key_path1=$key_path!=''?($key_path.'.'.$item->name):$item->name;
					$key_path1=strtolower($key_path1);
					if(is_array($item->children)&&count($item->children)>0 ) {
						?>
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading<?php echo $indent1 ?>">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion<?php echo $indent1 ?>" href="#collapse<?php echo $indent1 ?>" aria-expanded="false" aria-controls="collapse<?php echo $indent1 ?>">
										<?php echo $item->name; ?>
									</a>
								</h4>
							</div>
							<div id="collapse<?php echo $indent1 ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?php echo $indent1 ?>">
								<div class="panel-body">
									<?php JFormFieldStyleGenerator::stree_node_xml($item->children,  $block_id,$key_path1,$indent1,$form,$registry_item_form, $maxLevel, $level++); ?>
								</div>
							</div>
						</div>
						<?php
					}else{
						?>
						<div class="item">
						<?php
						for($i=0;$i<=1;$i++) {
							if($i==0)
							{
								$type='';
							}
							if($i==1)
							{
								$type='hover';
							}

							?>
							<div class="form-horizontal property-item <?php echo $type ?>">
								<?php
								$group = explode('.', $key_path);

								if (strtolower($group[0]) == 'option') {
									$name = array_reverse($group);
									array_pop($group);
									$group = array_reverse($group);
								}

								$group = implode('.', $group);
								$item->name = strtolower($item->name);
								$name = $type !== '' ? $item->name . '_' . $type : $item->name;
								//echo "$item->name $group";
								$setup_value = $registry_item_form->get('jform.' . $group . '.' . $name);
								$setup_value_enable = $registry_item_form->get('jform.' . $group . '.enable_' . $name);
								$setup_value_enable = $setup_value_enable == 'on' ? 1 : 0;
								$item_field = $form->getField($item->name, $group);
								$item_field->setValue($setup_value);
								$title=$item_field->getTitle();
								$title=$type=='hover'?$title."(hover)":$title;
								$item_field->setTitle($title);
								$item_field->__set('name', $name);
								$radio_yes_no = new JFormFieldRadioYesNo();
								$string_radio_yes_no = <<<XML

<field
		name="enable_$name"
		type="radioyesno"
		class="btn-group btn-group-yesno disable_post"
		onchange="$item->onchange"
		default="0"
		label=""
		description="is publich">
	<option class="btn" value="1">JYES</option>
	<option class="btn" value="0">JNO</option>
</field>

XML;
								$element_yes_no = simplexml_load_string($string_radio_yes_no);
								$radio_yes_no->setup($element_yes_no, $setup_value_enable, 'jform.' . $group);

								?>
								<div class="row">
									<div class="col-md-3">
										<?php echo $radio_yes_no->renderField(); ?>
									</div>
									<div class="col-md-8">
										<div class="row">
											<?php
											//echo "$item->name ---------- $group";
											if ($item_field) {
												$item_field->class = $item_field->class . ' ' . 'disable_post';
												echo $item_field->renderField(array(), true);
											}
											?>
										</div>

									</div>
								</div>

							</div>

							<?php
						}
						?>
						</div>
						<?php
					}
					?>
					<?php
					$i++;
				}
				?>
			</div>
			<?php

		}

	}


	protected function getInput()
	{
		$app=JFactory::getApplication();

		$tmpl=$app->input->get('tmpl','','string');
		JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		JHtml::_('bootstrap.modal');
		$out_put_name= $this->element['out_put_name'];
		$doc=JFactory::getDocument();
		$lessInput = JPATH_ROOT . '/libraries/cms/form/field/stylegenerator.less';
		$cssOutput = JPATH_ROOT . '/libraries/cms/form/field/stylegenerator.css';
		JUtility::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root().'/libraries/cms/form/field/stylegenerator.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/button-generator-master/css/jquery.slider.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/button-generator-master/js/colorpicker/css/colorpicker.css');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/menu.js');
		$doc->addScript(JUri::root() . "/media/system/js/base64.js");
		$doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.css");
		$doc->addStyleSheet(JUri::root() . "/media/system/js/ion.rangeSlider-master/css/ion.rangeSlider.skinHTML5.css");
		$doc->addScript(JUri::root().'/media/system/js/ion.rangeSlider-master/js/ion.rangeSlider.js');
		$doc->addScript(JUri::root().'/media/system/js/base64.js');

		$doc->addScript(JUri::root().'/media/system/js/jQuery.serializeObject-master/jquery.serializeObject.js');
		$doc->addScript(JUri::root().'/media/system/js/button-generator-master/js/colorpicker/js/colorpicker.js');
		$doc->addScript(JUri::root().'/media/system/js/sticky-master/jquery.sticky.js');
		$doc->addScript(JUri::root().'/libraries/cms/form/field/stylegenerator.js');
		$doc->addScript(JUri::root().'/libraries/cms/form/field/jquery.stylegenerator.js');
		$element_apply_style =$this->element['element_apply_style']?$this->element['element_apply_style']:JUserHelper::genRandomPassword();
		require_once JPATH_ROOT.'/libraries/joomla/form/fields/radioyesno.php';
		$data=$this->form->getData();
		require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
		$db=JFactory::getDbo();
		$table_control=new JTableUpdateTable($db,'control');
		$table_control->load(array(
			"element_path"=>'libraries/cms/form/field/stylegenerator.php',
			'type'=>'field'
		));
		$element_type=$app->input->get('element_type','','string');
		if($element_type=='module')
		{
			$element_apply_style='div[ data-block-id="'.$data->get('id').'"][ data-block-parent-id="'.$data->get('position').'"] '.$element_apply_style;
		}
		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_utility/models');
		$model_field=JModelForm::getInstance('Field','UtilityModel');
		$model_field->setState('field.id',$table_control->id);
		$model_field->getForm();
		$item=$model_field->getItem();
		$form=JForm::getInstance('com_utility.field', JPATH_ROOT.'/components/com_utility/models/forms/field.xml', array('control' => 'jform', 'load_data' => $loadData),false,JPATH_ROOT.'/components/com_utility/models/forms/field.xml');
		$form->loadFile(JPATH_ROOT.'/components/com_utility/models/forms/field.xml');
		$form->loadFile(JPATH_ROOT.'/libraries/cms/form/field/stylegenerator.xml',false, '//config');
		$form->bind($item);
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';

		$this->value=base64_decode($this->value);
		$registry_item_form = new JRegistry;
		$registry_item_form->loadString($this->value);
		$fields=$table_control->fields;
		$fields=base64_decode($fields);
		$field_block_output=$fields;

		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		if(!count($fields))
		{
			$fields=array(new stdClass());
		}
		$idTextArea= str_replace(array('[',']'),'_',$this->name);
		$scriptId="lib_cms_form_fields_stylegenerator".$idTextArea.'_'.JUserHelper::genRandomPassword();

		ob_start();
		?>
		<script type="text/javascript">
				jQuery(document).ready(function($){

					$('.stylegenerator').stylegenerator({
						onchange:function(style){
							var find = '.demo_button';
							var re = new RegExp(find, 'g');
							style = style.replace(re, '<?php echo $element_apply_style ?>');
							var element_apply_style='dynamic-styles-<?php echo $data->get('id',0)?>';
							$('#'+element_apply_style).remove();
							$('head').append('<style id="'+element_apply_style.toString()+'" type="text/css"></style>');
							var styleString = '<style id="'+element_apply_style.toString()+'" type="text/css">'+style+'</style>';
							// replace the head styles
							$('#'+element_apply_style).replaceWith(styleString);
						},
						input:'input[name="<?php echo $this->name ?>"]'
					});
					stylegenerator.init_stylegenerator();
				});
		</script>
		<?php
		$script=ob_get_clean();
		$script=JUtility::remove_string_javascript($script);
		$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


		$db=JFactory::getDbo();
		$listFunction=JFormFieldDatasource::getListAllFunction();
		$tables=$db->getTableList();
		$html='';
		ob_start();
		?>
		<div class="row">

			<div id="wrapper" class="col-md-12 stylegenerator">
				<div class="row">
					<div class="col-md-12">
						<h2>the settings<button type="button" data-element_path="libraries/cms/form/field/stylegenerator.php" data-module-id="1214" class="btn btn-danger config-field-stylegenerator pull-right"><i class="im-paragraph-justify"></i>Config field</button></h2>
						<div class="row">
							<div class="checkbox">
								<label>
									<input class="noStyle" type="checkbox"> None hover
								</label>
								<label>
									<input class="noStyle" type="checkbox"> hover
								</label>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 preview">
								<div id="preview" class="preview">
									<div class="demo_button" >content</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 stree_node_xml">
								<?php echo JFormFieldStyleGenerator::stree_node_xml($fields,$item->id,'','',$form,$registry_item_form); ?>
							</div>
						</div>
						<div class="row">
							<div id="css-display" class="col-md-12">
							</div>
						</div>

					</div>
				</div>

			</div>

		</div>
		<input type="hidden" name="<?php echo $this->name ?>" value="" name=""  />







		<?php
		$html.=ob_get_clean();
		return $html;
	}

	/**
	 * Method to get a JEditor object based on the form field.
	 *
	 * @return  JEditor  The JEditor object.
	 *
	 * @since   1.6
	 */
	public function getListAllFunction()
	{
		$listFunction=array(
			'aggregate'=>array(
				'avg(expr)'
				,'count(expr)'
				,'group_concat(expr)'
			),
			'cast'=>array(
				'cast(expr)'

			),
			'date and time'=>array(
				'adddate(date,interval,exprunit)'
				,'adddate(expr,days)'
				,'addtime(expr1,expr2)'
			),
			'mathematical'=>array(),
			'other'=>array(),
			'string'=>array()
		);
		return $listFunction;
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
