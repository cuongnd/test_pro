<?php
/**
 * abstract controller class containing get,store,delete,publish and pagination
 *
 *
 * This class provides the functions for the calculations
 *
 * @package	VirtueMart
 * @subpackage Helpers
 * @author Max Milbers
 * @copyright Copyright (c) 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
 *
 * http://virtuemart.net
 */
// Load the view framework
jimport( 'joomla.application.component.viewlegacy');


// Load default helpers
if (!class_exists('ShopFunctions')) require(JPATH_VM_SITE.DS.'helpers'.DS.'shopfunctions.php');
if (!class_exists('AdminUIHelper')) require(JPATH_VM_SITE.DS.'helpers'.DS.'adminui.php');

class VmView extends JViewLegacy{

	var $_cidName	= null;
	var $tmpl ='';
	/**
	 * Sets automatically the shortcut for the language and the redirect path
	 *
	 * @author Max Milbers
	 */
	// public function __construct() {
		// parent::construct();
	// }
	var $lists = array();
	function __construct() {

		parent::__construct();
		// always use same method for cidName
		$vName = $this->getName();
		$this->_cidName = 'virtuemart_'.$vName.'_id';
	// var_dump($this);
		//Template path and helper fix for Front-end editing
		$this->addTemplatePath(JPATH_VM_SITE.DS.'views'.DS.$this->_name.DS.'tmpl');
		$this->addHelperPath(JPATH_VM_SITE.DS.'helpers');
		$this->frontEdit = jRequest::getvar('tmpl') ==='component' ? true : false ;
		if ($this->frontEdit) {
			if (!class_exists('JToolBarHelper')) {
				jimport( 'joomla.html.toolbar');
				require(JPATH_VM_SITE.'/helpers/front/button.php');
				require(JPATH_VM_SITE.'/helpers/front/toolbar.php');
				require(JPATH_VM_SITE.'/helpers/front/toolbarhelper.php');
			}
			$this->tmpl = '&tmpl=component';
			$this->tmpl = '&tmpl=component';
		}
	}
	function gridPublished($name, $i)
	{
		if (JVM_VERSION < 3) {
			$published = JHtml::_('grid.published', $name, $i);
		} else {
			$published = JHtml::_('jgrid.published', $name->published, $i);
		}
		return $published;
	}

	/*
	 * set all commands and options for BE default.php views
	* return $list filter_order and
	*/
	function addStandardDefaultViewCommands($showNew=true, $showDelete=true, $showHelp=true) {


		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::divider();
		JToolBarHelper::editList();
		if ($showNew) {
			JToolBarHelper::addNew();
		}
		if ($showDelete) {
			JToolBarHelper::deleteList();
		}
		self::showHelp ( $showHelp);
	}

	/*
	 * set pagination and filters
	* return Array() $list( filter_order and dir )
	*/
	function linkIcon($link,$altText ='',$boutonName,$verifyConfigValue=false, $modal = true, $use_icon=true,$use_text=false,$class = ''){
		if ($verifyConfigValue) {
			if ( !VmConfig::get($verifyConfigValue, 0) ) return '';
		}
		$folder = 'media/system/images/'; //shouldn't be root slash before media, as it automatically tells to look in root directory, for media/system/ which is wrong it should append to root directory.
		$text='';
		if ( $use_icon ) $text .= JHtml::_('image', $folder.$boutonName.'.png',  vmText::_($altText), null, false, false); //$folder shouldn't be as alt text, here it is: image(string $file, string $alt, mixed $attribs = null, boolean $relative = false, mixed $path_rel = false) : string, you should change first false to true if images are in templates media folder
		if ( $use_text ) $text .= '&nbsp;'. vmText::_($altText);
		if ( $text=='' )  $text .= '&nbsp;'. vmText::_($altText);
		if ($modal) return '<a '.$class.' class="modal" rel="{handler: \'iframe\', size: {x: 700, y: 550}}" title="'. vmText::_($altText).'" href="'.JRoute::_($link, FALSE).'">'.$text.'</a>';
		else 		return '<a '.$class.' title="'. vmText::_($altText).'" href="'.JRoute::_($link, FALSE).'">'.$text.'</a>';
	}

	function addStandardDefaultViewLists($model, $default_order = 0, $default_dir = 'DESC',$name = 'search') {

		//This function must be used after the listing
// 		$pagination = $model->getPagination();
// 		$this->assignRef('pagination', $pagination);

		/* set list filters */
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view', JRequest::getCmd('controller','virtuemart'));

		$app = JFactory::getApplication();
		$this->lists[$name] = $app->getUserStateFromRequest($option . '.' . $view . '.'.$name, $name, '', 'string');
		$this->lists['filter_order'] = $this->getValidFilterOrder($app,$model,$view,$default_order);

// 		if($default_dir===0){
			$toTest = $app->getUserStateFromRequest( 'com_virtuemart.'.$view.'.filter_order_Dir', 'filter_order_Dir', $default_dir, 'cmd' );

		$this->lists['filter_order_Dir'] = $model->checkFilterDir($toTest);

	}
	function grid_delete_in_line($name, $i, $key)
	{
		$save_in_line = JHtml::_('jgrid.delete_in_line', $name->$key, $i);
		return $save_in_line;
	}

	function grid_cancel_in_line($name, $i, $key)
	{
		$save_in_line = JHtml::_('jgrid.cancel_in_line', $name->$key, $i);
		return $save_in_line;
	}


	function getValidFilterOrder($app,$model,$view,$default_order){

		if($default_order===0){
			$default_order = $model->getDefaultOrdering();
		}

		$toTest = $app->getUserStateFromRequest( 'com_virtuemart.'.$view.'.filter_order', 'filter_order', $default_order, 'cmd' );

// 		vmdebug('getValidFilterOrder '.$toTest.' '.$default_order, $model->_validOrderingFieldName);
		return $model->checkFilterOrder($toTest);
	}


	/*
	 * Add simple search to form
	* @param $searchLabel text to display before searchbox
	* @param $name 	lists and id name
	* @param $id 	alternative HTML Id
	* ??JText::_('COM_VIRTUEMART_NAME')
	*/

	function displayDefaultViewSearch($searchLabel='COM_VIRTUEMART_NAME',$name ='search',$id='search') {
		return '<div class="filter-search btn-group pull-left">
				<!--<label for="filter_search" class="element-invisible">'.JText::_($searchLabel).'</label>-->
				<input type="text" name="' . $name . '" id="' . $id . '" placeholder="'.JText::_('COM_VIRTUEMART_FILTER') . ' ' . JText::_($searchLabel).'" value="'. $this->escape( $this->lists[$name] ).'" title="'.JText::_('COM_VIRTUEMART_FILTER') . ' '.JText::_($searchLabel).'" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" id="searchsubmit" class="btn hasTooltip" title="'.JText::_('JSEARCH_FILTER_SUBMIT').'"><i class="icon-search"></i></button>
				<button type="button" id="searchreset" class="btn hasTooltip hidden-phone" title="'.JText::_('JSEARCH_FILTER_CLEAR').'" onclick=\'document.id("' . $id . '").value="";this.form.submit();\'><i class="icon-remove"></i></button>
			</div>';
	}

	function addStandardEditViewCommands($id = 0,$save2new = true ) {
		// if (JRequest::getCmd('tmpl') =='component' ) {
			// if (!class_exists('JToolBarHelper')) require(JPATH_ADMINISTRATOR.DS.'includes'.DS.'toolbarhelper.php');
		// } else {
// 		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::divider();
		if ($id) JToolBarHelper::save2copy('save2copy', 'JTOOLBAR_SAVE_AS_COPY');
		if ($save2new) JToolbarHelper::save2new('save2new', 'JTOOLBAR_SAVE_AND_NEW');
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		// todo add filter by view
		if ($id) JToolBarHelper::custom('preview','eye',null,'COM_VIRTUEMART_PREVIEW',false);
		// }
		// javascript for cookies setting in case of press "APPLY"
		$document = JFactory::getDocument();
		$name = $this->_name;
		if ($name == 'product') $name='productdetails';


		// LANGUAGE setting

		$editView = JRequest::getWord('view',JRequest::getWord('controller','' ) );

		$params = JComponentHelper::getParams('com_languages');
		//$config =JFactory::getConfig();$config->get('language');
		$selectedLangue = $params->get('site', 'en-GB');

            $lang = JFactory::getLanguage();
        if($this->frontEdit)    $selectedLangue = $lang->getTag();

		$lang = strtolower(strtr($selectedLangue,'-','_'));
		// only add if ID and view not null
		if ($editView and $id and (count(vmconfig::get('active_languages'))>1) ) {

			if ($editView =='user') $editView ='vendor';
			//$params = JComponentHelper::getParams('com_languages');
			jimport('joomla.language.helper');
			$lang = JRequest::getVar('vmlang', $lang);
			$languages = JLanguageHelper::createLanguageList($selectedLangue, constant('JPATH_SITE'), true);
			$activeVmLangs = (vmconfig::get('active_languages') );

			foreach ($languages as $k => &$joomlaLang) {
				if (!in_array($joomlaLang['value'], $activeVmLangs) )  unset($languages[$k] );
			}
			$langList = JHTML::_('select.genericlist',  $languages, 'vmlang', 'class="inputbox"', 'value', 'text', $selectedLangue , 'vmlang');
			$this->assignRef('langList',$langList);
			$this->assignRef('lang',$lang);



			$token = JSession::getFormToken();

			$j = '
			jQuery(function($) {
				var oldflag = "";
				$("select#vmlang").chosen().change(function() {
					langCode = $(this).find("option:selected").val();
					flagClass = "flag-"+langCode.substr(0,2) ;
					$.getJSON( "index.php?option=com_virtuemart&view=translate&task=paste&format=json&lg="+langCode+"&id='.$id.'&editView='.$editView.'&'.$token.'=1'.$this->tmpl.'" ,
						function(data) {
							var items = [];

							if (data.fields !== "error" ) {
								if (data.structure == "empty") alert(data.msg);
								$.each(data.fields , function(key, val) {
									cible = jQuery("#"+key);
									if (oldflag !== "") cible.parent().removeClass(oldflag)
									if (cible.parent().addClass(flagClass).children().hasClass("mce_editable") && data.structure !== "empty" ) {
										if (tinyMCE.execInstanceCommand) tinyMCE.execInstanceCommand(key,"mceSetContent",false,val);
										else tinymce.editors[key].setContent(val);
									}
									else if (data.structure !== "empty") cible.val(val);
									});
								oldflag = flagClass ;
							} else alert(data.msg);
						}
					)
				});
			})';
			$document->addScriptDeclaration ( $j);
		} else {
			// $params = JComponentHelper::getParams('com_languages');
			// $lang = $params->get('site', 'en-GB');
			$jlang = JFactory::getLanguage();
			$langs = $jlang->getKnownLanguages();
			$defautName = $langs[$selectedLangue]['name'];
			$flagImg =JURI::root( true ).'/administrator/components/com_virtuemart/assets/images/flag/'.substr($lang,0,2).'.png';
			$langList = '<input name ="vmlang" type="hidden" value="'.$selectedLangue.'" ><img style="vertical-align: middle;" alt="'.$defautName.'" src="'.$flagImg.'"> <b> '.$defautName.'</b>';
			$this->assignRef('langList',$langList);
			$this->assignRef('lang',$lang);
		}

		//I absolutly do not understand for that should be for, note by Max
/*		if ($object) {
		   if(Vmconfig::get('multix','none')!=='none'){
				$this->loadHelper('permissions');
				if(!Permissions::getInstance()->check('admin')) {
					if (!$object->virtuemart_vendor_id) {
						if(!class_exists('VirtueMartModelVendor')) require(JPATH_VM_SITE.DS.'models'.DS.'vendor.php');
						$object->virtuemart_vendor_id = VirtueMartModelVendor::getLoggedVendor();
					}
					$vendorList = '<input type="hidden" name="virtuemart_vendor_id" value="'.$object->virtuemart_vendor_id.'" />';
				} else 	$vendorList= ShopFunctions::renderVendorList($object->virtuemart_vendor_id,false);
		   } else {
				$vendorList = '<input type="hidden" name="virtuemart_vendor_id" value="1" />';
		   }
		   $this->assignRef('vendorList', $vendorList);
		}*/

	}


	function SetViewTitle($name ='', $msg ='') {
		$view = JRequest::getWord('view', JRequest::getWord('controller'));
		if ($name == '')
		$name = $view;
		if ($msg) {
			$msg = ' <span style="color: #666666; font-size: large;">' . $msg . '</span>';
		}
		//$text = strtoupper('COM_VIRTUEMART_'.$name );
		$viewText = JText::_('COM_VIRTUEMART').' '.ShopFunctions::altText($name);
		if (!$task = JRequest::getWord('task')) $task = 'list';
		$taskName = ShopFunctions::altText($task);
		$taskName = ' <small><small>[ ' . $taskName . ' ]</small></small>';
		JToolBarHelper::title($viewText . ' ' . $taskName . $msg, 'head vm_' . $view . '_48');
		$document = JFactory::getDocument();
		$title = $document->getTitle();
		$document->setTitle( trim(strip_tags($title)) );
		$this->assignRef('viewName',$viewText); //was $viewName?
	}

	function sort($orderby ,$name=null ){
		if (!$name) $name= 'COM_VIRTUEMART_'.strtoupper ($orderby);
		return JHTML::_('grid.sort' , JText::_($name) , $orderby , $this->lists['filter_order_Dir'] , $this->lists['filter_order']);
	}

	public function addStandardHiddenToForm($controller=null, $task=''){
		if (!$controller)	$controller = $this->_name;
		$option = JRequest::getCmd('option','com_virtuemart' );
		$hidden ='';
		if (array_key_exists('filter_order',$this->lists)) $hidden ='
			<input type="hidden" name="filter_order" value="'.$this->lists['filter_order'].'" />
			<input type="hidden" name="filter_order_Dir" value="'.$this->lists['filter_order_Dir'].'" />';
		// fix for front-end editing.
		if (JRequest::getCmd('tmpl') =='component' )
			 $hidden.='<input type="hidden" name="tmpl" value="component" />';
		return  $hidden.'
		<input type="hidden" name="task" value="'.$task.'" />
		<input type="hidden" name="option" value="'.$option.'" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="controller" value="'.$controller.'" />
		<input type="hidden" name="view" value="'.$controller.'" />
		'. JHTML::_( 'form.token' );
	}

	function getToolbar() {

		// add required stylesheets from admin template
		$document    = JFactory::getDocument();
		$document->addStyleSheet('administrator/templates/system/css/system.css');
		//now we add the necessary stylesheets from the administrator template
		//in this case i make reference to the bluestork default administrator template in joomla 1.6
		// $document->addCustomTag(
			// '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />'."\n\n".
			// '<!--[if IE 7]>'."\n".
			// '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />'."\n".
			// '<![endif]-->'."\n".
			// '<!--[if gte IE 8]>'."\n\n".
			// '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />'."\n".
			// '<![endif]-->'."\n".
			// '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />'."\n"
			// );
		//load the JToolBar library and create a toolbar
		jimport('joomla.html.toolbar');
		JToolBarHelper::divider();
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel();
		$bar = new JToolBar( 'toolbar' );
		//and make whatever calls you require
		$bar->appendButton( 'Standard', 'save', 'Save', 'save', false );
		$bar->appendButton( 'Separator' );
		$bar->appendButton( 'Standard', 'cancel', 'Cancel', 'cancel', false );
		//generate the html and return
		return $bar->render();
	}

	/**
	 * Additional grid function for custom toggles
	 *
	 * @return string HTML code to write the toggle button
	 */
	function toggle( $field, $i, $toggle, $cando= true, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' )
	{
		// $cando= true;
		$img 	= $field ? $imgY : $imgX;
		if ($toggle == 'published') {
			// Stay compatible with grid.published
			$task 	= $field ? 'unpublish' : 'publish';
			$ico = $field ? 'publish' : 'unpublish';
			$alt 	= $field ? JText::_('COM_VIRTUEMART_PUBLISHED') : JText::_('COM_VIRTUEMART_UNPUBLISHED');
			$action = $field ? JText::_('COM_VIRTUEMART_UNPUBLISH_ITEM') : JText::_('COM_VIRTUEMART_PUBLISH_ITEM');
		} else {
			$task 	= $field ? $toggle.'.0' : $toggle.'.1';
			$ico = $field ? 'ok' : 'remove';
			$alt 	= $field ? JText::_('COM_VIRTUEMART_PUBLISHED') : JText::_('COM_VIRTUEMART_DISABLED');
			$action = $field ? JText::_('COM_VIRTUEMART_DISABLE_ITEM') : JText::_('COM_VIRTUEMART_ENABLE_ITEM');
		}
		if ($cando) {
			return ('<a class="hasTooltip btn btn-mini" data-task="'. $task .'" href="#" onclick="return Joomla.taskJson(this, \'cb'. $i .'\')" title="'. $action .'">'
				.'<i class="icon-'.$ico.'"></i></a>');
				// .JHTML::_('image', 'admin/' .$img, $alt, null, true) .'</a>');
		} else return '<i class="icon-'.$ico.' hasTooltip" title="'.$alt.'"></i>' ;

	}

	function showhelp(){
		/* http://docs.joomla.org/Help_system/Adding_a_help_button_to_the_toolbar */

			$task=JRequest::getWord('task', '');
			$view=JRequest::getWord('view', '');
			if ($task) {
				if ($task=="add") {
					$task="edit";
				}
				$task ="_".$task;
			}
			if (!class_exists( 'VmConfig' )) require(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'config.php');
			VmConfig::loadConfig();
			VmConfig::loadJLang('com_virtuemart_help');
 		    $lang = JFactory::getLanguage();
 	        $key=  'COM_VIRTUEMART_HELP_'.$view.$task;
	         if ($lang->hasKey($key)) {
					$help_url  = JTEXT::_($key)."?tmpl=component";
 		            $bar = JToolBar::getInstance('toolbar');
					// echo $help_url;
					$bar->appendButton( 'Popup', 'help', 'JTOOLBAR_HELP', $help_url, 960, 400 );
	        }

	}


	/*
	 * render a Link to edit an item, auto add the Front-end Editing parameters
	 * @param $id the Id of the item
	 * @param $name 	alternative id name
	 * @param $view 	alternative view
	 * @param $attrib 	jhtml link attributes
	 */

	function editLink($id, $text, $name= null,$attrib='',$view = null,$task ='edit') {

		if ($view === null) $view = $this->_name;
		if ($name === null) $name = $this->_cidName;

		$editlink = $name. '=' . $id;
		$editlink .= $this->tmpl;

		$link = JROUTE::_('index.php?option=com_virtuemart&view='.$view.'&task='.$task.'&'.$editlink) ;
		// echo 'index.php?option=com_virtuemart&view='.$view.'&task=edit&'.$editlink ;
		return JHTML::_('link', $link, $text, $attrib);
	}
	// readd missing javascripts in new results
	// this must laways be after the RAW container
	// PLZ only add this in "RAW" list views result
	// @string  scripts  javascript to add after the results
	function AjaxScripts($scripts='') {
		// add ajax results script file
		include('ajax/results.raw.php');
	}
	/*
	 * compare creator with current logged vendor
	 * usefull for shared items
	 */
	public function canChange($created_by){
		static $user_id = null;
		static $vendor = null;
		if ($vendor === null) $vendor = Permissions::getInstance()->isSuperVendor();
		if ($vendor == 1) return true; // can change all
		if ($user_id === null) $user_id = JFactory::getUser()->get('id');
		return ($created_by === $user_id);

	}
	function DisplayFilterPublish() {
		$options = array( '' => JText::_('JOPTION_SELECT_PUBLISHED'),
						  '1' => JText::_('JPUBLISHED'),
						  '0' => JText::_('JUNPUBLISHED'));
		return VmHTML::selectList('filter_published', JRequest::getVar('filter_published'),$options,1,'','onchange="Joomla.ajaxSearch(this); return false;"');
		// return JHTML::_('select.genericlist', $options, 'filter.published', 'onChange="Joomla.ajaxSearch(this); return false;"', 'value', 'text', JRequest::getVar('filter.published'));

	}
}