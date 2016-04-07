<?php

$doc = JFactory::getDocument();
$website = JFactory::getWebsite();
/*$doc->addScript(JUri::root() . '/media/system/js/sidr-master/src/jquery.sidr.js');

$doc->addStyleSheet(JUri::root() . '/media/system/js/sidr-master/dist/stylesheets/jquery.sidr.light.css');
*/
$doc->addScript(JUri::root() . '/media/system/js/bootstrap-switch-master/dist/js/bootstrap-switch.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css');
$doc->addStyleSheet(JUri::root() . '/libraries/adminfrontend/assets/admin_left.css');
$doc->addScript(JUri::root() . '/libraries/adminfrontend/assets/admin_left.js');
$listScreenSize = UtilityHelper::getListScreenSize();
$currentScreenSize = UtilityHelper::getCurrentScreenSizeEditing();
$user=JFactory::getUser();
$full_height_state=$user->getParam('option.webdesign.full_height_state','false');
$full_height_state=JUtility::toStrictBoolean($full_height_state);

$app = JFactory::getApplication();


$doc->addScript(JUri::root().'/media/system/js/bootstrap-switch-master/docs/js/highlight.js');
$doc->addScript(JUri::root() . "/media/system/js/jquery.popupWindow.js");
JHtml::_('formbehavior.chosen', 'select');

$user=JFactory::getUser();
$show_popup_control=$user->getParam('option.webdesign.show_popup_control',false);
$show_popup_control=JUtility::toStrictBoolean($show_popup_control);
//get list view of component

JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_menus/models');
$menuTypeModel=JModelLegacy::getInstance('Menutypes','MenusModel');

$website=JFactory::getWebsite();

$menuItemIdActive=$app->input->get('Itemid',0,'int');
//-------------
//get Data source

//end get Data Source
$db=JFactory::getDbo();

?>

<ul id="sideNav" class="nav nav-pills nav-stacked">
    <li class=top-search>
        <form>
            <input name=search placeholder="Search ...">
            <button type=submit><i class="ec-search s20"></i></button>
        </form>
    </li>
    <?php
    $is_website_designing=JFactory::is_website_designing();
    if($is_website_designing){
    ?>
    <li><a href="index.php?option=com_bookpro">Dashboard <i class=im-screen></i></a></li>
    <li><a href="javascript:void(0)" class="link_javascript">Option <i class=im-paragraph-justify></i></a>
        <ul class="nav sub">
            <li class="form-group">
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <label class=" control-label">Smartphone size</label>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <select name="smart_phone" class="btn-primary btn-block smart-phone">
                        <?php foreach ($listScreenSize as $item) { ?>
                            <option <?php echo $item == $currentScreenSize ? 'selected' : ''; ?>
                                value="<?php echo $item ?>"><?php echo $item ?></option>
                        <?php } ?>
                    </select>
                </div>


            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block add_widget">Add new block
                    </button>
                </div>
            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block save-position"
                            id="save-position">Save
                    </button>
                </div>
            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block change_margin_widget"
                            id="change_margin_widget">Change Margin Widget
                    </button>
                </div>
            </li>
            <li>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block change_background"
                            id="change_background">Change Background
                    </button>
                </div>
            </li>
            <li>
                &nbsp;
            </li>

            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 control-label" for="disable_widget">Select border color
                    widget</label>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="row form-group">
                        <input name="disable_border_module" id="disable_border_module" type="checkbox" checked>
                    </div>
                    <div class="row">
                        <input type="color" class="input-sm" style="width: 25%" name="colorpickerFieldSelect"
                               id="colorpickerFieldSelect"/>

                    </div>
                </div>

            </li>

            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 control-label" for="show_popup_property">show popup property</label>
                <input class="noStyle" name="show_popup_property" <?php echo $show_popup_control?' checked ':'' ?>  id="show_popup_property" type="checkbox" >
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 control-label" for="disable_widget">Disable Widget</label>
                <input class="noStyle" name="disable_widget" id="disable_widget" type="checkbox" checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="editing">Enable Editing:</label>
                <input class="noStyle" name="editing" id="editing" type="checkbox" checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="hide_setting">Hiden Position Setting:</label>
                <input class="noStyle" name="hide_setting" id="hide_setting" type="checkbox" checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="hide_module_item_setting">Hiden Module Setting:</label>
                <input class="noStyle" name="hide_module_item_setting" id="hide_module_item_setting" type="checkbox"
                       checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="full_height">Full height:</label>
                <input class="noStyle" <?php echo $full_height_state?'checked':'' ?>   id="full_height" name="full_height" type="checkbox">
            </li>


        </ul>

    </li>
    <li><a href="javascript:void(0)" class="link_javascript">Data <i class=st-files></i></a>
        <ul class="nav sub">
            <li class="item-element item-data-source-ui" data-add-on-type="binding-source"><a href="javascript:void(0)"><i
                        class=st-files></i> BindingSource</a>
        </ul>
    </li>
    <li class="data-set load_datasources"><a href="javascript:void(0)" data-type="datasources" class="data_set notExpand link_ajax">Data set <i data-type="binding_source" class=st-files></i></a>

    </li>
    <li class="menu_page"><a class="list-menu notExpand link_ajax" data-type="menu_page" href="javascript:void(0)">Pages <i class=st-files></i><span
                class="manager-menu"><i class="fa-list-alt" data-type="menu_params"  menu="menu"></i></span> </a>

    </li>
    <li class="load_modules"><a href="javascript:void(0)" class="notExpand link_ajax" data-type="modules" >add module <i class=im-paragraph-justify></i><i data-type="config_field_module" module-config="global_module_config" class="fa-list-alt"></i></a>
    </li>
    <li class="load_plugins"><a href="javascript:void(0)" class="notExpand link_ajax" data-type="plugins">List plugin <i class=im-paragraph-justify></i></a>
    </li>

    <li class="load_component"><a data-type="component" class="notExpand link_ajax" href="javascript:void(0)">List Component <i class=im-paragraph-justify></i></a>
    </li>
    <li class="load_element"><a href="javascript:void(0)" class="link_ajax notExpand" data-type="element">Elements <i class=im-paragraph-justify></i><span class="element-property-manager"><i element-config="global_element_config" data-type="param_element" class="fa-list-alt"></i></span></a>
    </li>
    <?php }else{
        $website_name=JFactory::get_website_name();
        $main_component="com_$website_name";
        $main_component_path=JPath::get_component_path($main_component);
        $file_admin_menu_left_path=$main_component_path.DS.'admin_menu_left.php';
        if(file_exists($file_admin_menu_left_path))
        {
            require_once $file_admin_menu_left_path;
        }
    } ?>
</ul>

<?php



    include JPATH_ROOT . '/templates/sprflat/html/layouts/joomla/system/tooleditstyle.php';
    ?>
    <?php
    include JPATH_ROOT . '/templates/sprflat/html/layouts/joomla/system/blockproperty.php';
    //include JPATH_ROOT . '/libraries/adminfrontend/rightsidebar.php';
    ?>
    <style type="text/css">
        .input-title {
            border: 1px #ccc dotted;
        }
    </style>


    <style type="text/css">
        .div-loading {
            display: none;
            background: url("<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif") center center no-repeat;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%
        }
    </style>