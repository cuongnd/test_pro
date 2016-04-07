<?php defined('_JEXEC') or die;
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$uri=JFactory::getURI();
$sitename = $uri->getHost();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
$enableEditWebsite=UtilityHelper::getEnableEditWebsite();
$listScreenSize=UtilityHelper::getListScreenSize();

$preview=UtilityHelper::getStatePreview();
if($preview)
{
    $previewScreenSize=$app->input->get('previewScreenSize','','string');
    $js='var previewScreenSize="'.$previewScreenSize.'"';
    $doc->addScriptDeclaration($js);
}

//$tyles=UtilityHelper::getStyles();
// Detecting Active Variables
$website=JFactory::getWebsite();
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_website/models');
$modelWebsite=JModelLegacy::getInstance('Website','WebsiteModel');
$website=$modelWebsite->getItem($website->website_id);
$this->styles=$website->style;



$itemid   = $app->input->getCmd('Itemid', '');

$doc->addScript(JUri::root().'/media/system/js/lodash.min.js');

$doc->addScript(JUri::root().'/media/jui/jquery-ui-1.11.0.custom/jquery-ui.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.ba-bbq.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/jquery.mobile-1.4.5/jquery.mobile-1.4.5.css');
$doc->addStyleSheet(JUri::root().'media/jui/jquery-ui-1.11.0.custom/jquery-ui.css');
$doc->addScript(JUri::root().'/media/system/js/gridstack/src/gridstack.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/gridstack/src/gridstack.css');
$doc->addScript(JUri::root().'/media/system/js/Nestable-master/jquery.nestable.js');
if($enableEditWebsite) {
    $doc->addScript(JUri::root() . '/templates/sprflat/js/design.js');

}else
{
    $doc->addScript(JUri::root() . '/templates/sprflat/js/javascriptdisableedit.js');
    $doc->addStyleSheet(JUri::root().'/templates/sprflat/css/disableedit.css');

}
$doc->addScript(JUri::root().'/media/system/js/malihu-custom-scrollbar-plugin-3.0.7/js/minified/jquery.mousewheel.min.js');
$doc->addScript(JUri::root().'/media/system/js/malihu-custom-scrollbar-plugin-3.0.7/jquery.mCustomScrollbar.js');
$doc->addScript(JUri::root().'/templates/sprflat/js/javascript.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/malihu-custom-scrollbar-plugin-3.0.7/jquery.mCustomScrollbar.css');
$uri=JFactory::getURI();
$host=$uri->getHost();
$this->listPositions=UtilityHelper::getListPositions();
$host=str_replace('www.','',$host);
$host='.'.$host;
$js='
var url_root="'.JUri::root().'";
var preview='.$preview.';
var this_host="'.$host.'";
var listPositions='.json_encode($this->listPositions).';
var currentLink="'.$uri->toString().'";
var enableEditWebsite="'.($enableEditWebsite?$enableEditWebsite:0).'";
 var optionsGridIndex = {
        cell_height: 80,
        vertical_margin: 0

    };
';
$doc->addScriptDeclaration($js);


/*echo "<pre>";
print_r($listPositionsSetting);
die;*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <jdoc:include type="head" />
    <?php
    $doc->addStyleSheet("$this->baseurl/media/bootstrap-3.3.0/dist/css/bootstrap.css");
    $doc->addStyleSheet("$this->baseurl/templates/$this->template/css/template.css");
    $doc->addStyleSheet("$this->baseurl/templates/$this->template/css/custom.css");
    ?>
    <!--[if lt IE 9]>
    <script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 7]>
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template_IEold.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 8]>
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template_IE8.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 9]>
    <link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template_IE9.css" rel="stylesheet" type="text/css" />
    <![endif]-->
</head>
<body id="<?php echo ($itemid ? 'itemid-' . $itemid : ''); ?>">


<?php if($enableEditWebsite){ ?>
    <jdoc:include type="modules" name="header_admin" style="none" />
    <div  class="right-main-body" data-role="page" >
        <div class="admin_menu_left panel  ui-panel-content-fixed-toolbar-open" data-dismissible="false"     data-role="panel" id="panel-01" data-swipe-close="false"   data-position="left" data-display="push" >
            <jdoc:include type="modules" name="admin_left" style="none" />
        </div>
        <div class="wapper-body" data-role="content">


            <span class="open left"><a href="#panel-01" class="jqm-navmenu-link ui-btn ui-btn-icon-notext ui-corner-all ui-icon-bars ui-nodisc-icon ui-alt-icon ui-btn-left">hello panel</a></span>





            <div class="iframelive">
                <div class="screen-layout">
                    <?php echo displayLayout($this,$enableEditWebsite) ?>

                </div>
            </div>
        </div>
    </div>
    <div id="dialog_add_widget" style="display: none;" title="Basic dialog">
        <div id="dialog-body" class="dialog-body">
            <?php echo JText::_('you can not add more widget!')?>
        </div>
    </div>

    <div id="dialog_show_view" style="display: none;" title="Basic dialog">
        <div id="dialog_show_view_body" class="dialog_show_view_body">
            <?php echo JText::_('this dialog show view')?>
        </div>
    </div>


    <jdoc:include type="modules" name="admin" style="none" />
<?php }elseif($preview){

    echo '<div class="screen-layout">'.displayLayout($this,$enableEditWebsite).'</div>';
}else{
    echo displayLayout($this,$enableEditWebsite);
} ?>
<jdoc:include type="modules" name="debug" style="none" />
<div class="module-item-template">
    <?php
    include JPATH_ROOT . '/components/com_utility/views/module/tmpl/default.php';
    ?>
</div>

<div id="dialog" title="Basic dialog">
    <div id="dialog-body" class="dialog-body"></div>
</div>

</body>
</html>
<?php
function displayLayout($this_layout,$enableEdit)
{
    $session=JFactory::getSession();
    if($enableEdit)
    {
        $currentScreenSize=UtilityHelper::getCurrentScreenSizeEditing();
        $doc=JFactory::getDocument();
        $js='
        var currentScreenSizeEditing="'.$currentScreenSize.'";
        ';
        $doc->addScriptDeclaration($js);
    }
    else
        $currentScreenSize=UtilityHelper::getScreenSize();
    $listPositionsSetting=UtilityHelper::getListPositionsSetting($currentScreenSize);

    ob_start();
    ?>
    <?php if(!$enableEdit){ ?>
    <div class="edit_website"><i class="btn glyphicon glyphicon-cog"></i></div>
<?php } ?>

    <div class="grid-stack " style="<?php echo renderStyle($this_layout->styles['body']); ?>"  >
        <div style=" position: absolute;z-index:99999" class="grid-stack-item postion-header-setting">
            position setting
            <div class="pull-right"><i class="glyphicon glyphicon-plus-sign"></i><i class="position-remove glyphicon glyphicon-trash"></i><i class="glyphicon glyphicon-cog"></i></div>
        </div>
        <?php

        foreach($listPositionsSetting as $positionItem)
        {
            if($positionItem->position=='component-position')
                continue;
            $position=$positionItem->position;
            $id = $positionItem->id;
            $gs_x = $positionItem->gs_x;
            $gs_y = $positionItem->gs_y;
            $width = $positionItem->width;
            $height = $positionItem->height;
            include JPATH_ROOT . '/components/com_utility/views/module/tmpl/default.php';


        }
        $position='component-position';
        $id=(int)$listPositionsSetting[$position]->id;
        $gs_x=(int)$listPositionsSetting[$position]->gs_x;
        $gs_y=(int)$listPositionsSetting[$position]->gs_y;
        $width=(int)$listPositionsSetting[$position]->width;
        $height=(int)$listPositionsSetting[$position]->height;
        ?>

        <div class="grid-stack-item"
             data-gs-x="<?php echo $gs_x ?>" data-gs-y="<?php echo $gs_y ?>" data-position="<?php echo $position ?>" data-position-id="<?php echo $id?$id:'0'  ?>"
             data-gs-width="<?php echo $width ?>" data-gs-height="<?php echo $height ?>">
            <div class="grid-stack-item-content"><jdoc:include type="component" /></div>
        </div>

    </div>


    <?php
    $content=ob_get_contents();
    ob_end_clean();
    return $content;
}

function renderStyle($listStyle)
{
    $txt='';
    foreach($listStyle as $key=>$item)
    {
        $txt.="$key:$item;";
    }
    return $txt;
}
?>
