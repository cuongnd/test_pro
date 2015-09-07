<?php

$app=JFactory::getApplication();
$menu_item_id=$app->input->get('menu_item_id',0,'int');
$table_menu_item = JTable::getInstance('Menu');
$table_menu_item->load($menu_item_id);
$php_content=$table_menu_item->php_content;
if ( base64_encode(base64_decode($php_content, true)) === $php_content){
    $php_content=base64_decode($table_menu_item->php_content);
} else {
    $php_content='';
}



jimport('joomla.filesystem.file');
$menu_class='load_menu_item_'.$menu_item_id;
if(!trim($php_content))
{
    $php_content=JFile::read(JPATH_ROOT.'/components/com_menus/views/item/tmpl/menu_php_template.php');
    $php_content=str_replace('load_menu_item',$menu_class,$php_content);
}

$doc=JFactory::getDocument();
$lessInput = JPATH_ROOT . '/components/com_menus/views/item/tmpl/assets/less/view_item_phpcontent.less';
$cssOutput = JPATH_ROOT . '/components/com_menus/views/item/tmpl/assets/css/view_item_phpcontent.css';
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
JUtility::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . "/components/com_menus/views/item/tmpl/assets/css/view_item_phpcontent.css");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_menus/views/item/tmpl/assets/js/view_item_phpcontent.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");


$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.css");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.js");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/show-hint.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xquery-hint.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/templates-hint.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/theme/eclipse.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/doc/docs.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hover/text-hover.css");
//$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/theme/xq-light.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/lint/lint.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/mode/php/phpcolors.css");

$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/show-context-info.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/lint/status-lint.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/fold/folding-eclipse.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hover/xquery-hover.css");



$doc->addStyleSheet(JUri::root() . "/media/system/js/fseditor-master/fseditor.css");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/sql/sql.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/hint/show-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/sql-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/css-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/html-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/xml-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/javascript-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/anyword-hint.js");

$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/edit/matchbrackets.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/edit/closebrackets.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/lint/lint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/runmode/runmode.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/format/formatting.js");



$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/foldcode.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/foldgutter.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/brace-fold.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/xml-fold.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/comment-fold.js");


$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/show-context-info.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/lint/remoting-lint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/lint/status-lint.js");


$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/htmlmixed/htmlmixed.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/xml/xml.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/javascript/javascript.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/css/css.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/clike/clike.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/mode/php/php.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/selection/active-line.js");
//$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hover/text-hover.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hover/token-hover.js");

$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/xquery-commons.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/templates-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/execute/remoting-execute.js");



$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xquery-templates.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xquery-hint.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/system-functions.xml.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xhive-functions.xml.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/mode/xquery/xquery.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hover/xquery-hover.js");
$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.js");



require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website=JFactory::getWebsite();
require_once JPATH_ROOT.'/libraries/joomla/form/fields/icon.php';
$db=JFactory::getDbo();
$query=$db->getQuery(true);
$query->from('#__menu As menu');
$query->select('menu.parent_id, menu.id,menu.level,menu.icon,menu.title,menu.link,menu.alias');
$query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
$query->select('menuType.id as menu_type_id,menuType.title as menu_type_title');
$query->where('menuType.website_id=' . (int)$website->website_id);
$query->where('menuType.client_id=0');
$query->order('menuType.id,menu.ordering');

$db->setQuery($query);
$list_menu=$db->loadObjectList();
$list_menu_item=array();
foreach($list_menu as $item)
{
    $list_menu_item[$item->menu_type_id][]=$item;
}


$scriptId = "com_menus_view_menus_jaxloader" . '_' . JUserHelper::genRandomPassword();
ob_start();
?>
<script type="text/javascript" id="<?php echo $scriptId ?>">

    <?php
        ob_get_clean();
        ob_start();
    ?>
    jQuery(document).ready(function($){


        ajax_php_content_loader.init_php_content_ajax_loader();
    });
    <?php
     $script=ob_get_clean();
     ob_start();
      ?>
</script>
<?php
ob_get_clean();
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);






    ob_start();
    ?>
    <div class="row">
        <div class="col-md-12">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <a href="#" class="navbar-brand">Home</a>
                </div>
                <div>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="#">Tutorials</a></li>
                        <li><a href="#">Java/J2EE</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">Code<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="javascript:void(0)" onclick="ajax_php_content_loader.format_code(this)">Re format code</a></li>
                                <li><a href="#">JavaScript</a></li>
                                <li><a href="#">jQuery</a></li>
                                <li><a href="#">Ajax</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <textarea cols="100" rows="300" id="php_content"><?php echo $php_content ?></textarea>
        </div>
    </div>



    <?php

    $contents=ob_get_clean();
    $tmpl=$app->input->get('tmpl','','string');
    if($tmpl=='field')
    {
        echo $contents;
        return;
    }
    $response_array[] = array(
        'key' => '.panel-body.property.menu',
        'contents' => $contents
    );

    echo  json_encode($response_array);
    ?>



