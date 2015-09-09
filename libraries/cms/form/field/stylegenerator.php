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
	protected function getInput()
	{
		$app=JFactory::getApplication();

		$tmpl=$app->input->get('tmpl','','string');
		if($tmpl=='field')
		{
			JHtml::_('jquery.framework');
			JHtml::_('bootstrap.framework');
			JHtml::_('bootstrap.modal');
		}
		$out_put_name= $this->element['out_put_name'];
		$doc=JFactory::getDocument();
		$lessInput = JPATH_ROOT . '/libraries/cms/form/field/stylegenerator.less';
		$cssOutput = JPATH_ROOT . '/libraries/cms/form/field/stylegenerator.css';
		JUtility::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root().'/libraries/cms/form/field/stylegenerator.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/css-stylegenerator-generator-master/src/css-stylegenerator-generator.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/icomoon/sprites.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/bootstrap-colorpickersliders/bootstrap.colorpickersliders.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/syntaxhighlighter/3.0.83/shCore.css');
		$doc->addStyleSheet(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/syntaxhighlighter/3.0.83/shThemeDefault.css');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/bootstrap-touchspin/bootstrap.touchspin.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/tinycolor/tinycolor.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/bootstrap-colorpickersliders/bootstrap.colorpickersliders.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/src/css-stylegenerator-generator.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/jquery.base64/jquery.base64.min.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/qrcode/qrcode.min.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/zeroclipboard/ZeroClipboard.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/syntaxhighlighter/3.0.83/shCore.js');
		$doc->addScript(JUri::root().'/media/system/js/css-stylegenerator-generator-master/resources/syntaxhighlighter/3.0.83/shBrushCss.js');
		$idTextArea= str_replace(array('[',']'),'_',$this->name);
		$scriptId="lib_cms_form_fields_stylegenerator".$idTextArea.'_'.JUserHelper::genRandomPassword();
		ob_start();
		?>
		<script type="text/javascript" id="<?php echo $scriptId ?>">
			<?php
				ob_get_clean();
				ob_start();
			?>
			jQuery(document).ready(function($){

				//parser_url= $.url(currentLink).param();
				var ge = new CSSstylegeneratorEditor($('.css-stylegenerator-editor-container'));
				ZeroClipboard.setDefaults({
					moviePath: this_host+'/media/system/js/css-stylegenerator-generator-master/resources/zeroclipboard/ZeroClipboard.swf',
					trustedDomains: [window.location.host]
				});
				console.log(ge);
				var clip = new ZeroClipboard($(".css-stylegenerator-editor-copycss"));
				clip.on('dataRequested', function(client, args) {
					client.setText($("#cssoutput").data("output"));
				});
				clip.on('noflash wrongflash', function(client, args) {
					$(".css-stylegenerator-editor-copycss").hide();
					ZeroClipboard.destroy();
				});
			});
			<?php
			 $script=ob_get_clean();
			 ob_start();
			  ?>
		</script>
		<?php
		ob_get_clean();
		$doc->addScriptDeclaration($script,"text/javascript",$scriptId);


		$db=JFactory::getDbo();
		$listFunction=JFormFieldDatasource::getListAllFunction();
		$tables=$db->getTableList();
		$html='';
		ob_start();
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="css-stylegenerator-editor-container layout-init clearfix">

					<div class="row toolbar">

						<div class="col-md-12 text-right">

							<div class="btn-group btn-group-sm">
								<a href="#" class="btn btn-default css-stylegenerator-editor-configuration" data-toggle="modal" data-target="#configmodal"><span class="pngicon-wrench"></span> Config</a>
								<a href="" class="btn btn-default css-stylegenerator-editor-permalink"><span class="pngicon-share"></span> Share permalink</a>
								<a href="#" class="btn btn-default css-stylegenerator-editor-qrcode" title="stylegenerator permalink QR code" data-toggle="modal" data-target="#qrmodal"><span class="pngicon-qrcode"></span> QR Code</a>
								<a target="_blank" href="<?php echo JUri::root() ?>/media/system/js/css-stylegenerator-generator-master/stylegenerator.php" class="btn btn-default css-stylegenerator-editor-imagestylegenerator" style="margin-right:10px"><span class="pngicon-picture"></span> Get PNG</a>
								<button type="button" class="btn btn-primary css-stylegenerator-editor-copycss"><span class="pngicon-copy"></span> Copy CSS</button>
								<button type="button" class="btn btn-primary css-stylegenerator-editor-getcss" data-toggle="modal" data-target="#cssoutmodal" style="margin-right:10px"><span class="pngicon-file-css"></span> Get CSS</button>
								<button type="button" class="btn css-stylegenerator-editor-undo"><span class="pngicon-undo"></span> Undo</button>
								<button type="button" class="btn css-stylegenerator-editor-redo"><span class="pngicon-redo"></span> Redo</button>
							</div>

						</div>

					</div>

					<div class="row">

						<div class="col-md-6">

							<div class="panel css-stylegenerator-editor-swatches-wrapper">
								<div class="panel-heading"><h2 class="panel-title pull-left">Presets</h2>
									<div class="clearfix">
										<div class="pull-right btn-group">
											<button type="button" class="btn btn-default btn-sm css-stylegenerator-editor-export" title="Add stylegenerator to swatches" data-toggle="modal" data-target="#exportallmodal"><span class="pngicon-arrow-up2"></span> Export all</button>
											<button type="button" class="btn btn-default btn-sm css-stylegenerator-editor-import" title="Add stylegenerator to swatches" data-toggle="modal" data-target="#importmodal"><span class="pngicon-arrow-down2"></span> Import</button>
											<div class="css-stylegenerator-editor-preset current btn pull-left"><span></span></div>
											<button type="button" class="btn btn-primary btn-sm css-stylegenerator-editor-save" title="Add stylegenerator to swatches"><span class="pngicon-disk"></span> Save</button>
											<button type="button" class="btn btn-primary btn-sm css-stylegenerator-editor-delete" title="Remove stylegenerator from swatches"><span class="pngicon-remove"></span> Delete</button>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<div class="content css-stylegenerator-editor-swatches">
										<ul class="clearfix"></ul>
									</div>
								</div>
							</div>

						</div>

						<div class="col-md-6">

							<div class="css-stylegenerator-editor panel css-stylegenerator-editor-preview-panel">
								<div class="panel-heading"><h2 class="panel-title pull-left">Preview</h2>
									<div class="clearfix">
										<div class="pull-right btn-group">
											<button type="button" class="btn btn-primary btn-sm css-stylegenerator-editor-adjustcolor"><span class="pngicon-settings"></span> Adjust color</button>
											<button type="button" class="btn btn-primary btn-sm css-stylegenerator-editor-previewpopout"><span class="pngicon-popup"></span> Pop out</button>
										</div>
									</div>
								</div>
								<div class="panel-body">

									<div class="css-stylegenerator-editor-preview-container">
										<div class="css-stylegenerator-editor-preview">
											<div class="ajax-loader"><span class="css-stylegenerator-editor-preview-resize-handler"></span></div>
                                        <span class="css-stylegenerator-controls">
                                            <button type="button" class="btn btn-primary btn-sm css-stylegenerator-editor-previewpopout"><span class="pngicon-collapse"></span></button>
                                        </span>
										</div>
									</div>
								</div>
							</div>

						</div>

					</div>

					<div class="panel stylegenerator-properties">
						<div class="panel-heading"><h2 class="panel-title pull-left">stylegenerator properties</h2>
							<div class="clearfix">
								<div class="pull-right btn-group">
									<a href="" class="btn btn-hover btn-primary btn-sm css-stylegenerator-editor-layout-easy" title="<b>IE6+, Android 2.3+, iOS 3.2+</b><br>CSS, filter, old webkit<br><i>linear stylegenerators</i>">Simple</a>
									<a href="" class="btn btn-hover btn-primary btn-sm css-stylegenerator-editor-layout-advanced" title="<b>IE9+, Android 3.0+, iOS 3.2+, WP7.5+</b><br>CSS, SVG<br><i>dynamic radial stylegenerators</i>">Advanced</a>
									<a href="" class="btn btn-hover btn-primary btn-sm css-stylegenerator-editor-layout-expert" title="<b>IE10+, Android 4.0+, iOS 5.0+</b><br>Only for browsers with CSS3 support<br><i>experimental</i>">Expert</a>
								</div>
							</div>
						</div>
						<div class="panel-body nopadding">

							<div class="layout-warning-box">
								<div class="label-warning layout-warning-advanced">
									Current stylegenerator needs advanced features so the desired layout is overwritten! ... <a href="" class="force-layout-change">Force change</a>
								</div>

								<div class="label-warning layout-warning-expert">
									Current stylegenerator needs expert features so the desired layout is overwritten! ... <a href="" class="force-layout-change">Force change</a>
								</div>
							</div>

							<div class="row stylegenerator-preferences-easy">
								<div class="col-sm-5 col-xs-12">
									<input name="color_from">
								</div>
								<div class="col-sm-2 col-xs-12">
									<div class="css-stylegenerator-editor-linear-direction-implicit">
										<div class="btn-row">
											<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-top" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="top"><span class="pngicon-arrow-up"></span></button>
										</div>
										<div class="btn-row">
											<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-left" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="left"><span class="pngicon-arrow-left"></span></button>
											<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-right pull-right" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="right"><span class="pngicon-arrow-right"></span></button>
										</div>
										<div class="btn-row">
											<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-bottom" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="bottom"><span class="pngicon-arrow-down"></span></button>
										</div>
									</div>
								</div>
								<div class="col-sm-5 col-xs-12">
									<input name="color_to">
								</div>
							</div>

							<div class="row stylegenerator-preferences-advanced">
								<div class="col-md-6">

									<div class="css-stylegenerator-editor-preferences form-horizontal block">

										<div class="form-group">
											<label class="col-xs-3">Repeating: </label>
											<div class="col-xs-9 btn-group">
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="repeat" data-name="stylegenerator_repeat" data-value="on">repeat</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="repeat" data-name="stylegenerator_repeat" data-value="off">no repeat</button>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-3">stylegenerator type: </label>
											<div class="col-xs-9 btn-group">
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_type" data-name="stylegenerator_type" data-value="linear">linear</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_type" data-name="stylegenerator_type" data-value="radial">radial</button>
											</div>
										</div>

										<div class="css-stylegenerator-editor-linear-preferences form-group">
											<label class="col-xs-3">Direction:</label>
											<div class="col-xs-9">
												<div class="css-stylegenerator-editor-linear-direction">
													<div class="css-stylegenerator-editor-linear-direction-implicit">
														<div class="btn-row">
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-top-left" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="top left"><span class="pngicon-arrow-up-left"></span></button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-top" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="top"><span class="pngicon-arrow-up"></span></button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-top-right" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="top right"><span class="pngicon-arrow-up-right"></span></button>
														</div>
														<div class="btn-row">
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-left" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="left"><span class="pngicon-arrow-left"></span></button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-right" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="right"><span class="pngicon-arrow-right"></span></button>
														</div>
														<div class="btn-row">
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-bottom-left" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="bottom left"><span class="pngicon-arrow-down-left"></span></button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-bottom" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="bottom"><span class="pngicon-arrow-down"></span></button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller css-stylegenerator-editor-direction-bottom-right" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="bottom right"><span class="pngicon-arrow-down-right"></span></button>
														</div>
													</div>

													<div class="css-stylegenerator-editor-linear-direction-explicit">
														<span class="css-stylegenerator-editor-controller css-stylegenerator-editor-direction-angle" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="angle"><span></span></span>
														<span class="css-stylegenerator-editor-controller css-stylegenerator-editor-direction-angle-input" data-control-group="linear-direction" data-name="stylegenerator_direction" data-value="angle"><input type="text" name="angle" class="input-sm"></span>
													</div>
												</div>
											</div>
										</div>

										<div class="css-stylegenerator-editor-radial-preferences form-group">
											<div class="form-group css-stylegenerator-editor-radial-shape">
												<label class="col-xs-3">Shape: </label>
												<div class="col-xs-9 btn-group">
													<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_shape" data-name="stylegenerator_shape" data-value="circle">circle</button>
													<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_shape" data-name="stylegenerator_shape" data-value="ellipse">ellipse</button>
												</div>
											</div>

											<div class="form-group css-stylegenerator-editor-radial-size">
												<label class="col-xs-3">Size: </label>
												<div class="col-xs-9">
													<div class="controls">
														<label>Implicit</label>
														<div class="btn-group">
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_size" data-name="stylegenerator_size" data-value="closest-side">closest-side</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_size" data-name="stylegenerator_size" data-value="closest-corner">closest-corner</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_size" data-name="stylegenerator_size" data-value="farthest-side">farthest-side</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_size" data-name="stylegenerator_size" data-value="farthest-corner">farthest-corner</button>
														</div>
													</div>
													<div class="controls">
														<label>Explicit</label>
														<span class="span css-stylegenerator-editor-controller css-stylegenerator-editor-size-explicit" data-control-group="stylegenerator_size" data-name="stylegenerator_size" data-value="explicit"><input type="text" name="stylegenerator_size" data-units='["px"]' class="input-sm"> <input type="text" name="stylegenerator_size_major" data-units='["px"]' class="input-sm"></span>
													</div>
												</div>
											</div>

											<div class="form-group css-stylegenerator-editor-radial-horizontal-position">
												<label class="col-xs-3">Horizontal position: </label>
												<div class="col-xs-9">
													<div class="controls">
														<label>Implicit</label>
														<div class="btn-group">
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_position_horizontal" data-name="stylegenerator_position_horizontal" data-value="left">left</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_position_horizontal" data-name="stylegenerator_position_horizontal" data-value="center">center</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_position_horizontal" data-name="stylegenerator_position_horizontal" data-value="right">right</button>
														</div>
													</div>
													<div class="controls">
														<label>Explicit</label>
														<span class="css-stylegenerator-editor-controller css-stylegenerator-editor-position-horizontal-explicit" data-control-group="stylegenerator_position_horizontal" data-name="stylegenerator_position_horizontal" data-value="explicit"><input type="text" name="stylegenerator_position_horizontal" data-units='["%","px"]' class="input-sm"></span>
													</div>
												</div>
											</div>

											<div class="form-group css-stylegenerator-editor-radial-vertical-position">
												<label class="col-xs-3">Vertical position: </label>
												<div class="col-xs-9">
													<div class="controls">
														<label>Implicit</label>
														<div class="btn-group">
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_position_vertical" data-name="stylegenerator_position_vertical" data-value="top">top</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_position_vertical" data-name="stylegenerator_position_vertical" data-value="center">center</button>
															<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="stylegenerator_position_vertical" data-name="stylegenerator_position_vertical" data-value="bottom">bottom</button>
														</div>
													</div>
													<div class="controls">
														<label>Explicit</label>
														<span class="css-stylegenerator-editor-controller css-stylegenerator-editor-position-vertical-explicit" data-control-group="stylegenerator_position_vertical" data-name="stylegenerator_position_vertical" data-value="explicit"><input type="text" name="stylegenerator_position_vertical" data-units='["px","%"]' class="input-sm"></span>
													</div>
												</div>
											</div>
										</div>

									</div>

								</div>

								<div class="col-md-6">

									<div class="css-stylegenerator-editor-colorstops-easy">
										<div class="css-stylegenerator-editor-stopeditor"><span></span><div class="css-stylegenerator-editor-stoppointmarkers"></div></div>
									</div>

									<div class="css-stylegenerator-editor-colorstops-advanced clearfix">
										<div class="css-stylegenerator-editor-stoppointlist"></div>
										<button type="button" class="col-md-4 btn btn-sm btn-default css-stylegenerator-editor-reorder-stoppoints"><span class="pngicon-random"></span> Update order</button>
										<button type="button" class="col-md-offset-4 col-md-4 btn btn-sm btn-primary css-stylegenerator-editor-add-stoppoint"><span class="pngicon-plus"></span> Add stop point</button>
									</div>

								</div>
							</div>
						</div>

					</div>
					<div class="panel stylegenerator-properties">
						<div class="panel-heading"><h2 class="panel-title pull-left">stylegenerator output</h2>
						</div>
						<div class="panel-body nopadding">
								<textarea name="<?php echo $out_put_name ?>" style="width: 100%;height: 400px" class="css_output">
									<?php echo $this->value ?>
								</textarea>
						</div>
					</div>
					<!-- Modal -->
					<div class="modal bs-modal-lg" id="cssoutmodal" tabindex="-1" role="dialog" aria-labelledby="cssModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Generated CSS</h4>
								</div>
								<div class="modal-body">
									<div class="css-stylegenerator-editor-cssoutput-container">
										<pre class="css-stylegenerator-editor-cssoutput"></pre>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal bs-modal-lg" id="qrmodal" tabindex="-1" role="dialog" aria-labelledby="permalinkModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">stylegenerator permalink <small>Test the current stylegenerator in your mobile browser</small></h4>
								</div>
								<div class="modal-body">
									<div id="permalinkqr" class="text-center"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal bs-modal-lg" id="exportallmodal" tabindex="-1" role="dialog" aria-labelledby="permalinkModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Export presets <small>save the content to a file</small></h4>
								</div>
								<div class="modal-body">
									<textarea class="css-stylegenerator-editor-textarea-exportall" readonly="readonly"></textarea>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal bs-modal-lg" id="importmodal" tabindex="-1" role="dialog" aria-labelledby="permalinkModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Import presets <small>paste the previously saved data into the textarea</small></h4>
								</div>
								<div class="modal-body">
									<textarea class="css-stylegenerator-editor-textarea-import"></textarea>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-link loaddefaults">Load defaults</button>
									<button type="button" class="btn btn-default" data-dismiss="modal">Import</button>
								</div>
							</div>
						</div>
					</div>

					<div class="modal bs-modal-lg" id="configmodal" tabindex="-1" role="dialog" aria-labelledby="permalinkModalLabel" aria-hidden="true">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h4 class="modal-title">Configuration</h4>
								</div>
								<div class="modal-body">

									<form class="form-horizontal" role="form">

										<div class="form-group">
											<label class="col-xs-3 text-right">CSS selector:</label>
											<div class="col-xs-2">
												<input type="text" class="form-control" data-name="config_cssselector" name="config-cssselector" placeholder=".stylegenerator">
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-3 text-right">Color format: </label>
											<div class="col-xs-9 btn-group">
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="config_colorformat" data-name="config_colorformat" data-value="rgb">rgb</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="config_colorformat" data-name="config_colorformat" data-value="hsl">hsl</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="config_colorformat" data-name="config_colorformat" data-value="hex">hex</button>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-3 text-right">Color picker visible sliders: </label>
											<div class="col-xs-9 btn-group">
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_colorpicker_hsl">hsl</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_colorpicker_rgb">rgb</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_colorpicker_cie">cie</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_colorpicker_opacity">opacity</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_colorpicker_swatches">color swatches</button>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-3 text-right">Mixed stop point units: </label>
											<div class="col-xs-9 btn-group">
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="config_mixedstoppointunits" data-name="config_mixedstoppointunits" data-value="enabled">enabled</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-control-group="config_mixedstoppointunits" data-name="config_mixedstoppointunits" data-value="disabled">disabled</button>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-3 text-right">Code generation: </label>
											<div class="col-xs-9 btn-group">
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_generation_bgcolor" title="Weighted average background color fallback">bg color</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_generation_iefilter" title="IE 6-8">IE filter</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_generation_svg" title="IE 9, iOS 3.2-4.3, WP 7.5">SVG</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_generation_oldwebkit" title="SVG and SVG with old-webkit if justified (radial stylegenerators only, and only needed for Android 2.3 and older where SVG is not supported but the SVG definition deletes the old webkit syntax if placed after that, which is needed for radial stylegenerators because the `-webkit-stylegenerator` syntax with radial shape lacks support for percentage-defined size)">old webkit</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_generation_webkit" title="Android 3.0+, iOS 5.0+ - but they supports SVG as well">newer webkit</button>
												<button class="btn btn-default btn-sm css-stylegenerator-editor-controller" data-name="config_generation_ms" title="IE10 consumer preview - there is really no need to use it">-ms</button>
											</div>
										</div>

										<div class="form-group">
											<label class="col-xs-3 text-right">Fallback width:</label>
											<div class="col-xs-2">
												<div class="input-group">
													<input type="text" class="form-control" data-name="config_fallbackwidth" name="config-fallbackwidth">
													<span class="input-group-addon">px</span>
												</div>
											</div>
											<p class="help-block">Used in some circumstances with old webkit and SVG generation. Using preview size if not specified.</p>
										</div>

										<div class="form-group">
											<label class="col-xs-3 text-right">Fallback height:</label>
											<div class="col-xs-2">
												<div class="input-group">
													<input type="text" class="form-control" data-name="config_fallbackheight" name="config-fallbackheight">
													<span class="input-group-addon">px</span>
												</div>
											</div>
											<p class="help-block">Used in some circumstances with old webkit and SVG generation. Using preview size if not specified.</p>
										</div>

									</form>

								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>

				</div>

			</div>

		</div>







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
