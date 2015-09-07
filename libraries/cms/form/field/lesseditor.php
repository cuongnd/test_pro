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
class JFormFieldlesseditor extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'lesseditor';

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
		$doc=JFactory::getDocument();
		$lessInput = JPATH_ROOT . '/libraries/cms/form/field/lesseditor.less';
		$cssOutput = JPATH_ROOT . '/libraries/cms/form/field/lesseditor.css';
		JUtility::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root()."/libraries/cms/form/field/lesseditor.css");
		$doc->addStyleSheet(JUri::root()."/media/system/js/CodeMirror-master/lib/codemirror.css");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/lib/codemirror.js");
		$doc->addStyleSheet(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/show-hint.css");
		$doc->addStyleSheet(JUri::root()."/media/system/js/CodeMirror-master/addon/display/fullscreen.css");
		$doc->addStyleSheet(JUri::root()."/media/system/js/fseditor-master/fseditor.css");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/mode/sql/sql.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/show-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/sql-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/css-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/html-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/xml-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/javascript-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/anyword-hint.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/mode/php/php.js");
		$doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/display/fullscreen.js");
		$doc->addScript(JUri::root()."/media/system/js/fseditor-master/jquery.fseditor.js");

		$idTextArea= str_replace(array('[',']'),'_',$this->name);
		$scriptId="lib_cms_form_fields_lesscontent".$idTextArea.'_'.JUserHelper::genRandomPassword();
		ob_start();
		?>
		<script type="text/javascript" id="<?php echo $scriptId ?>">
			<?php
				ob_get_clean();
				ob_start();
			?>
			var grid_result=createGridDataByQuery();
			function createGridDataByQuery()
			{
				grid_result=$('#grid_result').kendoGrid({
					height: 300,
					groupable: true,
					scrollable: true,
					pageable: {
						refresh: true,
						pageSizes: true,
						buttonCount: 5
					}
				}).data("kendoGrid");
				return grid_result;
			}

			$('#table-result a:first').tab('show');


			$('#table-result a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				query=window.editor.getValue();
				var target = $(e.target).attr("href");
				switch(target) {
					case '#stander_query':
						//code block here
						ajaxGetStanderQuery=$.ajax({
							type: "GET",
							url: this_host+'/index.php',

							data: (function () {

								dataPost = {
									option: 'com_phpmyadmin',
									task: 'datasource.ajaxGetStanderQuery',
									query:query

								};
								return dataPost;
							})(),
							beforeSend: function () {


								// $('.loading').popup();
							},
							success: function (response) {

								$('.stander_query').html(response);
							}
						});



						break;
					case '#result':
						//code block
						getDataByQuery();

						break;
					default:
					//default code block
				}
			});
			function getDataByQuery()
			{
				query=window.editor.getValue();
				ajaxGetStanderQuery=$.ajax({
					type: "GET",
					url: this_host+'/index.php',

					data: (function () {

						dataPost = {
							option: 'com_phpmyadmin',
							task: 'datasource.ajaxGetDataByQuery',
							query:query

						};
						return dataPost;
					})(),
					beforeSend: function () {
						$('.div-loading').css({
							display: "block"


						});

						// $('.loading').popup();
					},
					success: function (response) {
						$('.div-loading').css({
							display: "none"


						});
						response= $.parseJSON(response);

						if(response.e==1)
						{
							$('#grid_result_error').html(response.m).show();
							$('#grid_result').hide();
						}
						else {
							grid_result.destroy();
							grid_result.wrapper.empty();
							$('#grid_result_error').hide();
							$('#grid_result').show();
							grid_result = createGridDataByQuery();
							grid_result.dataSource.data(response.r);
						}
					}
				});

			}
			query=$('#<?php echo $idTextArea ?>').val();
			query=query.replace("/^\s*|\s*$/g",'');
			$('#<?php echo $idTextArea ?>').val(query.trim());
			jQuery(document).ready(function($){
				var mime = 'text/x-mariadb';
				if (window.location.href.indexOf('mime=') > -1) {
					mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
				};
				window.editor = CodeMirror.fromTextArea(document.getElementById('<?php echo $idTextArea ?>'), {
					mode: mime,
					indentWithTabs: true,
					smartIndent: true,
					lineNumbers: true,
					matchBrackets : true,
					fullScreen:false,
					autofocus: true,
					extraKeys: {"Ctrl-Space": "autocomplete"},
					hintOptions: {tables: {
						users: {name: null, score: null, birthDate: null},
						countries: {name: null, population: null, size: null}
					}}

				});

				window.getValueFromTextMirror=function (self)
				{
					self.val(window.editor.getValue());
					console.log(window.editor.getValue());
				};
				$('.datasource-result').fseditor({
					overlay: true,
					expandOnFocus: false,
					transition: '', // 'fade', 'slide-in',
					placeholder: '',
					maxWidth: '', // maximum width of the editor on fullscreen mode
					maxHeight: '', // maximum height of the editor on fullscreen mode,
					onExpand: function() {}, // on switch to fullscreen mode callback
					onMinimize: function() {} // on switch to inline mode callback
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
			<div class="tables">
				<div><input class="table" name="table" type="text"></div>
				<ul>
					<?php foreach($tables as $table){ ?>
						<li><a class="item-table" data-table="<?php echo $table ?>"><?php echo $table ?></a></li>
					<?php } ?>
				</ul>
			</div>
			<div id="drag-drop-demo" class="diagrams demo drag-drop-demo">
				<div class="" id="list"></div>

			</div>

		</div>
		<div class="row">
			<div class="query">

				<div class="filter">
					<div class="tabs">
						<ul id="myTab" class="nav nav-tabs tabdrop">
							<li><a href="#selection" data-toggle="tab">Selection</a></li>

							<li><a href="#joins" data-toggle="tab">Joins</a></li>

							<li><a href="#where" data-toggle="tab">Where</a></li>

							<li><a href="#group_by" data-toggle="tab">Group by</a></li>

							<li><a href="#having" data-toggle="tab">Having</a></li>

							<li><a href="#oder_by" data-toggle="tab">Oder by</a></li>
						</ul>

						<div id="set-query" class="tab-content">
							<div class="tab-pane fade active in" id="selection">
								<table class="table table-bordered select-column">
									<thead>
									<tr>

										<th class="per40">Column</th>

										<th class="per40">Alias</th>

										<th class="per15">Table</th>
										<th class="per15">Aggregate</th>
										<th class="per15">Sort</th>
										<th class="per15">Filter</th>
									</tr>
									</thead>

									<tbody>
									<tr>
										<td class="per40  " >
											<div>
												<div class="edit-row pull-left show-select-table-and-function" contenteditable="true" >test</div>
												<div class="select-popup-column pull-right">
													<a href="javascript:void(0)" class="show-table-and-function" ><i class="fa-circle-arrow-down"></i></a>
												</div>
											</div>
											<div class="table-and-function  table-and-function-hide">
												<div class="pull-left list-table">
													<select class="select-tables pull-left">
														<option value="0"> all table</option>
													</select>
													<ul class="list-field pull-left">

													</ul>
												</div>
												<div class="pull-left list-function">
													<select class="select pull-left group-function" name="group_function">
														<option value="0">all function</option>
														<?php  foreach($listFunction as $groupFunction=> $functions){ ?>
															<option value="<?php echo $groupFunction ?>"><?php echo $groupFunction ?></option>
														<?php } ?>
													</select>
													<ul class="functions pull-left">
														<?php foreach($listFunction as $groupFunction=> $functions){ ?>
															<?php foreach($functions as $function){ ?>
																<li data-group-function="<?php echo $groupFunction ?>"><?php echo $function ?></li>
															<?php } ?>
														<?php } ?>
													</ul>
												</div>
											</div>
										</td>

										<td class="per40 edit-row" contenteditable="true">Alias</td>

										<td class="per15 edit-row" contenteditable="true">Table</td>
										<td class="per15 edit-row" contenteditable="true">Aggregate</td>
										<td class="per15 edit-row" contenteditable="true">Sort</td>
										<td class="per15 edit-row" contenteditable="true">Filter</td>
									</tr>



									</tbody>
								</table>

							</div>

							<div class="tab-pane fade" id="joins">
								joins
							</div>

							<div class="tab-pane fade" id="where">
								where
							</div>

							<div class="tab-pane fade" id="group_by">
								group_by
							</div>

							<div class="tab-pane fade" id="having">
								having
							</div>

							<div class="tab-pane fade" id="oder_by">
								oder_by
							</div>
						</div>
					</div>
				</div>

				<div class="result datasource-result">
					<div class="tabs">
						<ul id="table-result" class="nav nav-tabs tabdrop">
							<li><a href="#query" data-toggle="tab">Query</a></li>
							<li><a href="#stander_query" data-toggle="tab">Stander Query</a></li>

							<li><a href="#mode_select_column" data-toggle="tab">mode select column</a></li>
							<li><a href="#show_column" data-toggle="tab">show column</a></li>
							<li><a href="#result" data-toggle="tab">Result</a></li>
							<li><a href="#config" data-toggle="tab">Config</a></li>


						</ul>

						<div id="table-result-content" class="tab-content">
							<div class="tab-pane fade  active in" id="query">
								<textarea style="width: 100%; height: 300px" function-call-before-save="getValueFromTextMirror" id="<?php echo $idTextArea ?>" name="<?php echo $this->name ?>">
									<?php echo trim($this->value) ?>
								</textarea>
							</div>
							<div class="tab-pane fade  active in" id="stander_query">
								<div class="stander_query"></div>
								</textarea>
							</div>

							<div class="tab-pane fade  active in" id="mode_select_column">

								<textarea style="width: 50%; height: 300px" class="pull-left" name="jform[params][mode_select_column]">
									<?php
									echo trim($this->form->getValue('params')->mode_select_column);
									?>
								</textarea>
								<div class="pull-left" style="width: 50%">
									{
									"column1":{
									"type":"type",
									"editable":false
									},
									"column2":{
									"type":"type"
									}
									}
								</div>
							</div>
							<div class="tab-pane fade  active in" id="show_column">
								<textarea style="width: 50%; height: 300px" class="pull-left" name="jform[params][show_column]">
									<?php
									echo trim($this->form->getValue('params')->show_column);
									?>
								</textarea>
								<div style="width: 50%" class="pull-left">
									{
									"column1":{
									"title":"title",
									"width":"width(int)"
									},
									"column2":{
									"title":"title",
									"width":"width(int)"
									}
									}
								</div>
							</div>
							<div class="tab-pane fade" id="result">
								<div id="grid_result"></div>
								<div id="grid_result_error"></div>
							</div>


							<div class="tab-pane fade" id="config">
								config
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<script src="<?php echo JUri::root().'/media/system/js/jsPlumb-master/src/jquery.jsPlumb-1.4.1-all-min.js' ?>"></script>
		<script src="<?php echo JUri::root().'/media/system/js/jQuery-Plugin-For-Generating-Random-Colors-Autumn-js/autumn.js' ?>"></script>

		<script type="text/javascript" src="<?php echo JUri::root() ?>/libraries/cms/form/field/lesseditor.js"/>
		<!--		<script type="text/javascript" src="--><?php //echo JUri::root() ?><!--/jsPlumb-master/demo/draggableConnectors/demo.js"/>-->


		<!--<link href="<?php /*echo JUri::root() */?>/media/system/js/jsPlumb-master/css/jsplumb.css" rel="stylesheet"/>-->
		<!--<link href="<?php /*echo JUri::root() */?>/media/system/js/jsPlumb-master/demo/draggableConnectors/demo.css" rel="stylesheet"/>-->
		<link href="<?php echo JUri::root() ?>/libraries/cms/form/field/lesseditor.css" rel="stylesheet"/>
		<style>

			.item {
				height:80px; width: 80px;
				border: 1px solid blue;
				float: left;
			}
		</style>







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
