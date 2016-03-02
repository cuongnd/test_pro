<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>"
	  lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">


<?php
require_once JPATH_ROOT . '/components/com_utility/helper/utility.php';

$this->debugScreen = 0;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$isAdminSite = UtilityHelper::isAdminSite();
$enableEditWebsite = UtilityHelper::getEnableEditWebsite();
$ajaxGetContent = $app->input->get('ajaxgetcontent', 0, 'int');

$menu = JMenu::getInstance('site');
$menuItemActiveId = $menu->getActive()->id;

$menuItemActiveId = $menuItemActiveId ? $menuItemActiveId : 0;
$menuItemActive = $menu->getItem($menuItemActiveId);
$lessContent = $menuItemActive->lesscontent;
if (trim($lessContent) != '') {
	require_once JPATH_ROOT . '/libraries/f0f/less/less.php';
	$less = new F0FLess;
	$doc->addStyleDeclaration($less->compile($lessContent));
}
//get property website
$website = JFactory::getWebsite();
$websiteTable = JTable::getInstance('Website', 'JTable');
$websiteTable->load($website->website_id);


if ($isAdminSite && !$enableEditWebsite) {
	$app->redirect('index.php?option=com_users&view=login&template=system');
	return;
}

$doc = JFactory::getDocument();

if ($enableEditWebsite) {
	$preview = UtilityHelper::getStatePreview();
	$preview = $preview != '' ? $preview : 0;
	$user = JFactory::getUser();
	$uri = JFactory::getURI();
	$listScreenSize1 = UtilityHelper::getListScreenSize();
	$currentScreenSize = UtilityHelper::getCurrentScreenSizeEditing();
	$listScreenSize = array();
	$listScreenSizeX = array();
	foreach ($listScreenSize1 as $screenSize) {
		$screenSize1 = explode('x', strtolower($screenSize));
		$item = new stdClass();
		$item->width = $screenSize1[0];
		$item->height = $screenSize1[1];
		$listScreenSize[$screenSize] = $item;
		$listScreenSizeX[] = $screenSize1[0];
	}
	$this->listPositions = UtilityHelper::getListPositions();
	$host = $uri->toString(array('scheme', 'host', 'port'));
	$scriptId="index_".JUserHelper::genRandomPassword();
	ob_start();
	?>
	<script type="text/javascript">
		var url_root="<?php echo JUri::root() ?>";
		var preview=<?php echo $preview ?>;
		var this_host="<?php echo $host ?>";
		var currentLink="<?php echo $uri->toString()?>";
		jQuery.noConflict();
		var listPositions=<?php echo json_encode($this->listPositions) ?>;
		var menuItemActiveId=<?php echo $menuItemActiveId?>;
		var currentScreenSizeEditing="<?php echo $currentScreenSize ?>";
		var listScreenSizeX=<?php echo json_encode($listScreenSizeX) ?>;
		var listScreenSize=<?php echo json_encode($listScreenSize) ?>;
		var currentLink="<?php echo $uri->toString() ?>";
		var enableEditWebsite="<?php echo ($enableEditWebsite ? $enableEditWebsite : 0) ?>";
		var optionsGridIndex = {
			cell_height: 80,
			vertical_margin: 0,
			placeholder_class:"holder-and-move"


		};
		var source_less="<?php echo str_replace('.less','.css',$websiteTable->source_less) ?>";
	</script>
	<?php
	$script=ob_get_clean();
	$script=JUtility::remove_string_javascript($script);
	$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

	JHTML::_('behavior.core');
	require_once JPATH_ROOT . '/components/com_website/helpers/website.php';
	require_once JPATH_ROOT . '/templates/sprflat/helper/template.php';
	JHtml::_('jquery.framework');


	//JHtml::_('jquery.ui', array('core','widget', 'sortable'));
	if (!$ajaxGetContent) {
		//$doc->addScript(JUri::root() . '/media/system/js/firebug-lite/build/firebug-lite-debug.js');
		$doc->addScript(JUri::root() . '/media/system/js/contextmenueditwebsite.js');
		$doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/position.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/button.js');
		$doc->addScript(JUri::root() . '/media/system/js/base64.js');
		$doc->addScript(JUri::root() . '/media/system/js/popline-master/scripts/jquery.popline.js');

		$doc->addStyleSheet(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css');

		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/sortable.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/resizable.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/dialog.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/droppable.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/js/jquery.ui.touch-punch.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/libs/excanvas.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/libs/html5.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/libs/respond.min.js');
		// $doc->addScript(JUri::root() . '/templates/sprflat/js/jquery.windowscroll.js');



		/*        $doc->addScript(JUri::root().'/ckfinder/ckfinder.js');
                $doc->addScript(JUri::root().'/media/editors/ckeditor/ckeditor.js');
                $doc->addScript(JUri::root().'/media/editors/ckeditor/adapters/jquery.js');*/
		/*
        //kendo editor
        $doc->addScript(JUri::root().'/media/kendotest/kendo.all.js');
        $doc->addScript(JUri::root().'/media/kendotest/kendo.core.js');
        $doc->addScript(JUri::root().'/media/kendotest/kendo.web.js');
        $doc->addScript(JUri::root().'/media/kendotest/kendo.editor.js');*/

		/* $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.common.min.css');
            $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.default.min.css');

            $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.material.min.css');
            $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.material.mobile.min.css');
            $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.min.css');
            $doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.default.min.css');*/

		JHtml::_('formbehavior.chosen', 'select');
		//$doc->addScript(JUri::root().'/media/system/js/jquery.ba-bbq.js');
		$doc->addScript(JUri::root() . '/media/system/js/lodash.min.js');
		$doc->addScript(JUri::root() . '/media/system/js/gridstack/src/gridstack.js');
		//$doc->addScript(JUri::root().'/media/system/js/Nestable-master/jquery.nestable.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/js/jquery.editstyle.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jRespond.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/quicksearch/jquery.quicksearch.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/misc/countTo/jquery.countTo.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/forms/icheck/jquery.icheck.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.horizontal.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/charts/sparklines/jquery.sparkline.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jquery.sprFlat.js');

		//$doc->addScript(JUri::root().'/media/system/js/ion.rangeSlider-1.9.1/js/ion-rangeSlider/ion.rangeSlider.js');
		$doc->addScript(JUri::root() . '/media/system/js/joyride-master/jquery.joyride-2.1.js');
		$doc->addStyleSheet(JUri::root() . '/media/system/js/joyride-master/joyride-2.1.css');

		$doc->addLessStyleSheet(JUri::root() . '/templates/sprflat/less/csswheneditsite.less');


		$lessInput = JPATH_ROOT . "/layouts/website/less/$websiteTable->source_less";
		if(JFile::exists($lessInput))
		{
			$doc->addLessStyleSheet(JUri::root(). "/layouts/website/less/$websiteTable->source_less");
		}


		$doc->addScript(JUri::root() . '/media/system/js/jquery-neon-border/js/jquery.neon_border.js');
		$doc->addLessStyleSheet(JUri::root() . '/media/system/js/jquery-neon-border/less/jquery.neon_border.less');


		$lessInput = JPATH_ROOT . '/templates/sprflat/less/template.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/css/template.css';
		templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . "/templates/sprflat/css/template.css");

		//css for gridstack
		$lessInput = JPATH_ROOT . '/media/system/js/gridstack/less/gridstack.less';
		$cssOutput = JPATH_ROOT . '/media/system/js/gridstack/src/gridstack.css';
		templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . "/media/system/js/gridstack/src/gridstack.css");
		//end css for gridstack

		$lessInput = JPATH_ROOT . '/templates/sprflat/assets/less/main.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/assets/css/main.css';
		//templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/assets/css/main.css');


		$lessInput = JPATH_ROOT . '/templates/sprflat/assets/less/icons.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/assets/css/icons.css';
		//templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/assets/css/icons.css');


		$lessInput = JPATH_ROOT . '/templates/sprflat/assets/less/plugins.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/assets/css/plugins.css';
		//templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/assets/css/plugins.css');
		require_once JPATH_ROOT . '/libraries/less.php-master/lessc.inc.php';
		$options = array('cache_dir' => JPATH_ROOT . '/media/jui_front_end/bootstrap-3.3.0/cache');

		try {
			$parser = new Less_Parser($options);
			$parser->parseFile(JPATH_ROOT . '/media/jui_front_end/bootstrap-3.3.0/less/bootstrap.less');
//$parser->ModifyVars( array('font-size-base'=>'16px') );
			/*$parser->ModifyVars(array(
                'grid-gutter-width' => '30px',
                'container-large-desktop' => '1024px',

            ));*/
//		    $css = $parser->getCss();
//		    JFile::write(JPATH_ROOT.'/media/jui_front_end/bootstrap-3.3.0/dist/css/bootstrap3.css',$css);
		} catch (Exception $e) {
			$error_message = $e->getMessage();
		}
		echo $error_message;

		$doc->addStyleSheet(JUri::root() . '/media/jui_front_end/bootstrap-3.3.0/dist/css/bootstrap3.css');

//bootrap2
		$options = array('cache_dir' => JPATH_ROOT . '/media/jui_front_end/bootstrap-3.3.0/cache');

		try {
			$parser = new Less_Parser($options);
			//$parser->parseFile(JPATH_ROOT . '/media/jui_front_end/bootstrap-2.3.2/less/bootstrap.less');
//$parser->ModifyVars( array('font-size-base'=>'16px') );
			/*$parser->ModifyVars(array(
                'grid-gutter-width' => '30px',
                'container-large-desktop' => '1024px',

            ));*/
//			$css = $parser->getCss();
//			JFile::write(JPATH_ROOT.'/media/jui_front_end/bootstrap-2.3.2/css/bootstrap2.css',$css);
		} catch (Exception $e) {
			$error_message = $e->getMessage();
		}
		echo $error_message;
		//$doc->addStyleSheet(JUri::root().'/media/jui_front_end/bootstrap-2.3.2/css/bootstrap2.css');


		$doc->addLessStyleSheet("$this->baseurl/templates/$this->template/less/custom.less");

	}


} else {

	$this->listPositions = UtilityHelper::getListPositions();
	$listScreenSize1 = UtilityHelper::getListScreenSize();

	$listScreenSizeX = array();
	foreach ($listScreenSize1 as $screenSize) {
		$screenSize1 = explode('x', strtolower($screenSize));

		$listScreenSizeX[] = $screenSize1[0];
	}

	$uri = JFactory::getURI();
	$currentScreenSize = UtilityHelper::getScreenSize();
	$this->currentScreenSize = $currentScreenSize;
	$host = $uri->toString(array('scheme', 'host', 'port'));
	$enableEditWebsite=false;
	$js = '
		jQuery.noConflict();
		var url_root="' . JUri::root() . '";
		var this_host="' . $host . '";
		var currentScreenSize="' . $currentScreenSize . '";
		var listPositions=' . json_encode($this->listPositions) . ';
		var listScreenSizeX=' . json_encode($listScreenSizeX) . ';
		var currentLink="' . $uri->toString() . '";
		var enableEditWebsite="' . ($enableEditWebsite ? true : false) . '";
		 var optionsGridIndex = {
				cell_height: 80,
				destroy_resizable: 1,
				vertical_margin: 0,
				placeholder_class:"holder-and-move",
				handle:"holder-and-move"

			};
		';
	$doc->addScriptDeclaration($js);
	if (!$ajaxGetContent) {

		JHtml::_('jquery.framework');
		JHtml::_('bootstrap.framework');
		JHtml::_('formbehavior.chosen', 'select');
		//$doc->addScript(JUri::root() . '/media/system/js/firebug-lite/build/firebug-lite-debug.js');
		$doc->addScript(JUri::root() . '/media/system/js/jquery-cookie-master/src/jquery.cookie.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/position.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/button.js');

		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/resizable.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/dialog.js');
		$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/droppable.js');

		require_once JPATH_ROOT . '/components/com_website/helpers/website.php';
		require_once JPATH_ROOT . '/templates/sprflat/helper/template.php';
		JHtml::_('jquery.framework');
		JHtml::_('jquery.ui', array('core', 'sortable'));
		$doc->addLessStyleSheet(JUri::root()."templates/$this->template/less/custom.less");
		$doc->addScript(JUri::root() . '/media/system/js/lodash.min.js');
		$doc->addScript(JUri::root() . '/media/system/js/gridstack/src/gridstack.js');

		$lessInput = JPATH_ROOT . '/media/system/js/gridstack/less/gridstack.less';
		$cssOutput = JPATH_ROOT . '/media/system/js/gridstack/src/gridstack.css';
		templateSprflatHelper::compileLess($lessInput, $cssOutput);


		$doc->addStyleSheet(JUri::root() . '/media/system/js/gridstack/src/gridstack.css');

		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jRespond.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/quicksearch/jquery.quicksearch.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/misc/countTo/jquery.countTo.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/forms/icheck/jquery.icheck.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.horizontal.min.js');
		$doc->addScript(JUri::root() . '/templates/sprflat/assets/js/jquery.sprFlatFrontEnd.js');

		$doc->addScript(JUri::root() . '/templates/sprflat/js/javascriptdisableedit.js');

		$lessInput = JPATH_ROOT . '/templates/sprflat/less/disableedit.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/css/disableedit.css';
		templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/css/disableedit.css');


		$lessInput = JPATH_ROOT . '/templates/sprflat/assets/less/mainFrontEnd.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/assets/css/mainFrontEnd.css';
		JUtility::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/assets/css/mainFrontEnd.css');


		$lessInput = JPATH_ROOT . "/layouts/website/less/$websiteTable->source_less";
		$lessInputInfo = pathinfo($lessInput);
		$cssOutput = JPATH_ROOT . '/layouts/website/css/' . $lessInputInfo['filename'] . '.css';
		JUtility::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/layouts/website/css/' . $lessInputInfo['filename'] . '.css');


		$lessInput = JPATH_ROOT . '/templates/sprflat/less/template.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/css/template.css';
		templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . "/templates/sprflat/css/template.css");


		require_once JPATH_ROOT . '/libraries/less.php-master/lessc.inc.php';
		$options = array('cache_dir' => JPATH_ROOT . '/media/jui_front_end/bootstrap-3.3.0/cache');

		try {
			$parser = new Less_Parser($options);
			$parser->parseFile(JPATH_ROOT . '/media/jui_front_end/bootstrap-3.3.0/less/bootstrap.less');
//$parser->ModifyVars( array('font-size-base'=>'16px') );
			/*$parser->ModifyVars(array(
                'grid-gutter-width' => '30px',
                'container-large-desktop' => '1024px',

            ));*/
//			$css = $parser->getCss();
//			JFile::write(JPATH_ROOT.'/media/jui_front_end/bootstrap-3.3.0/dist/css/bootstrap3.css',$css);
		} catch (Exception $e) {
			$error_message = $e->getMessage();
		}
		echo $error_message;

		$doc->addStyleSheet(JUri::root() . '/media/jui_front_end/bootstrap-3.3.0/dist/css/bootstrap3.css');

//bootrap2
		$options = array('cache_dir' => JPATH_ROOT . '/media/jui_front_end/bootstrap-3.3.0/cache');

		try {
			$parser = new Less_Parser($options);
			//$parser->parseFile(JPATH_ROOT . '/media/jui_front_end/bootstrap-2.3.2/less/bootstrap.less');
//$parser->ModifyVars( array('font-size-base'=>'16px') );
			/*$parser->ModifyVars(array(
                'grid-gutter-width' => '30px',
                'container-large-desktop' => '1024px',

            ));*/
//			$css = $parser->getCss();
//			JFile::write(JPATH_ROOT.'/media/jui_front_end/bootstrap-2.3.2/css/bootstrap2.css',$css);
		} catch (Exception $e) {
			$error_message = $e->getMessage();
		}
		echo $error_message;
		//$doc->addStyleSheet(JUri::root() . '/media/jui_front_end/bootstrap-2.3.2/css/bootstrap2.css');

		$lessInput = JPATH_ROOT . '/templates/sprflat/assets/less/icons.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/assets/css/icons.css';
		//templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/assets/css/icons.css');


		$lessInput = JPATH_ROOT . '/templates/sprflat/assets/less/plugins.less';
		$cssOutput = JPATH_ROOT . '/templates/sprflat/assets/css/plugins.css';
		//templateSprflatHelper::compileLess($lessInput, $cssOutput);
		$doc->addStyleSheet(JUri::root() . '/templates/sprflat/assets/css/plugins.css');


	}
}
?>
<?php
if ($ajaxGetContent) {
	echo websiteHelperFrontEnd::displayLayout($this, $enableEditWebsite);
	return;

}
?>
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
	<jdoc:include type="head"/>
</head>
<body class="">

<?php if ($enableEditWebsite) {

	?>

	<?php
	include JPATH_ROOT.'/templates/sprflat/html/layouts/joomla/system/contextmenu.php';
	?>

	<jdoc:include type="message" />
	<jdoc:include type="component" />


	<!-- End #content -->
<?php } else { ?>

	<?php echo websiteHelperFrontEnd::displayLayout($this, 0) ?>
<?php } ?>
<!-- Javascripts -->
<!-- Load pace first -->
</body>
</html>
