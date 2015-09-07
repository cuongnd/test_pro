<script src="modules/mod_mymenu/tmpl/js.js"></script>
<?php
$data=ModMymenuHelper::getModuleById($module->id);
$parammodule=json_decode($data->params);


?>
<?php //echo $module->id ?>

<script>
    jQuery(document).ready(function($){
        var options = {
            ulname:{
                ulname:'#<?php echo $module->id ?>',
            },
            params:{
                color:"<?php echo $parammodule->color; ?>",
                showtotal:"<?php echo $parammodule->showtotal; ?>",
                showArrows:"<?php echo $parammodule->showArrows; ?>",
            },
        }

        $('#<?php echo $module->id ?>').styleulli(options);

    });
</script>

<?php //echo $module->screensize; ?>
<div class="wrapper-menu-module">

    <ul class="moduleMenu nav" id="<?php echo $module->id ?>">
        <li class="moduleMenu-top-search">
            <form>
                <input placeholder="Search ..." name="search">
                <button type="submit"><i class="ec-search s20"></i></button>
            </form>
        </li>

        <ul class="nav sub" style="display: none;">
            <li class="form-group">
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <label class=" control-label">Smartphone size</label>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <select class="btn-primary btn-block smart-phone chzn-done" name="smart_phone" style="display: none;">
                        <option value="480X320">480X320</option>
                        <option value="800X480">800X480</option>
                        <option value="854X480">854X480</option>
                        <option value="960X640">960X640</option>
                        <option value="1136X640">1136X640</option>
                        <option value="1280X768" selected="">1280X768</option>
                        <option value="1920X1080">1920X1080</option>
                    </select><div class="chzn-container chzn-container-single chzn-container-single-nosearch" style="width: 100%;" title=""><a tabindex="-1" class="chzn-single"><span>1280X768</span><div><b></b></div></a><div class="chzn-drop"><div class="chzn-search"><input type="text" autocomplete="off" readonly=""></div><ul class="chzn-results"></ul></div></div>
                </div>


            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button class="btn btn-primary btn-block add_widget" href="javascript:void(0)">Add new block</button>
                </div>
            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button id="save-position" class="btn btn-primary btn-block save-position" href="javascript:void(0)">Save</button>
                </div>
            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button id="change_margin_widget" class="btn btn-primary btn-block change_margin_widget" href="javascript:void(0)">Change Margin Widget </button>
                </div>
            </li>
            <li>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button id="change_background" class="btn btn-primary btn-block change_background" href="javascript:void(0)">Change Background</button>
                </div>
            </li>
            <li>
                &nbsp;
            </li>

            <li class="form-group">
                <label for="disable_widget" class="col-lg-6 col-md-6 col-sm-6 control-label">Select border color widget</label>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="row form-group">
                        <div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-disable_border_module bootstrap-switch-animate" style="width: 108px;"><div class="bootstrap-switch-container" style="width: 159px; margin-left: 0px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 53px;">ON</span><span class="bootstrap-switch-label" style="width: 53px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 53px;">OFF</span><input type="checkbox" checked="" id="disable_border_module" name="disable_border_module" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
                    </div>
                    <div class="row">
                        <input type="color" id="colorpickerFieldSelect" name="colorpickerFieldSelect" style="width: 25%" class="input-sm">

                    </div>
                </div>

            </li>

            <li class="form-group">
                <label for="disable_widget" class="col-lg-6 col-md-6 col-sm-6 control-label">Disable Widget</label>
                <div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-disable_widget bootstrap-switch-animate" style="width: 108px;"><div class="bootstrap-switch-container" style="width: 159px; margin-left: 0px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 53px;">ON</span><span class="bootstrap-switch-label" style="width: 53px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 53px;">OFF</span><input type="checkbox" checked="" id="disable_widget" name="disable_widget" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
            </li>
            <li class="form-group">
                <label for="editing" class="col-lg-6 col-md-6 col-sm-6">Enable Editing:</label>
                <div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-editing bootstrap-switch-animate" style="width: 108px;"><div class="bootstrap-switch-container" style="width: 159px; margin-left: 0px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 53px;">ON</span><span class="bootstrap-switch-label" style="width: 53px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 53px;">OFF</span><input type="checkbox" checked="" id="editing" name="editing" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
            </li>
            <li class="form-group">
                <label for="hide_setting" class="col-lg-6 col-md-6 col-sm-6">Hiden Position Setting:</label>
                <div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-hide_setting bootstrap-switch-animate" style="width: 108px;"><div class="bootstrap-switch-container" style="width: 159px; margin-left: 0px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 53px;">ON</span><span class="bootstrap-switch-label" style="width: 53px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 53px;">OFF</span><input type="checkbox" checked="" id="hide_setting" name="hide_setting" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
            </li>
            <li class="form-group">
                <label for="hide_module_item_setting" class="col-lg-6 col-md-6 col-sm-6">Hiden Module Setting:</label>
                <div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-hide_module_item_setting bootstrap-switch-animate" style="width: 108px;"><div class="bootstrap-switch-container" style="width: 159px; margin-left: 0px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 53px;">ON</span><span class="bootstrap-switch-label" style="width: 53px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 53px;">OFF</span><input type="checkbox" checked="" id="hide_module_item_setting" name="hide_module_item_setting" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
            </li>
            <li class="form-group">
                <label for="full_height" class="col-lg-6 col-md-6 col-sm-6">Full height:</label>
                <div class="icheckbox_flat-blue" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-off bootstrap-switch-id-full_height bootstrap-switch-animate" style="width: 108px;"><div class="bootstrap-switch-container" style="width: 159px; margin-left: -53px;"><span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 53px;">ON</span><span class="bootstrap-switch-label" style="width: 53px;">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 53px;">OFF</span><input type="checkbox" name="full_height" id="full_height" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div>
            </li>



        </ul>

        </li>
        <li class="hasSub"><a href="#" class="notExpand">Pages <i class="st-files"></i></a>
            <ul class="nav sub" style="display: none;">

                <li class="hasSub"><a href="#" class="notExpand"><i class="st-files"></i> Main menu...</a>
                    <ul class="nav sub">
                        <li><a href="timeline.html"><i class="im-file3"></i> Home</a></li>
                        <li><a href="timeline.html"><i class="im-file3"></i> hello</a></li>
                        <li><a href="timeline.html"><i class="im-file3"></i> Contact</a></li>
                    </ul>

                </li>
                <li class="hasSub"><a href="#" class="active-state notExpand"><i class="st-files"></i> system pages</a>
                    <ul class="nav sub" style="display: none;">
                        <li><a href="timeline.html"><i class="ec-clock"></i> Timeline page</a></li>
                        <li><a href="invoice.html"><i class="st-file"></i> Invoice</a></li>
                        <li><a href="profile.html" class="active"><i class="ec-user"></i> Profile page</a></li>
                    </ul>
                </li>
                <li class="hasSub"><a href="#" class="notExpand"><i class="st-files"></i> Error pages</a>
                    <ul class="nav sub">
                        <li><a href="400.html"><i class="st-file-broken"></i> Error 400</a></li>
                        <li><a href="401.html"><i class="st-file-broken"></i> Error 401</a></li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="hasSub"><a href="#" class="notExpand">add module <i class="im-paragraph-justify"></i></a>
            <ul class="nav sub list-module" style="display: none;">
                <li class="item-element module_item ui-draggable ui-draggable-handle" data-module-id="10390"><a href="javascript:void(0)"><i class="ec-pencil2"></i>AcyMailing Module</a></li>
                <li class="item-element module_item ui-draggable ui-draggable-handle" data-module-id="10393"><a href="javascript:void(0)"><i class="ec-pencil2"></i>Alexa stats and google pagerank</a></li>
                <li class="item-element module_item ui-draggable ui-draggable-handle" data-module-id="10394"><a href="javascript:void(0)"><i class="ec-pencil2"></i>Archived Articles</a></li>
                <li class="item-element module_item ui-draggable ui-draggable-handle" data-module-id="10395"><a href="javascript:void(0)"><i class="ec-pencil2"></i>Articles Categories</a></li>

            </ul>
        </li>
        <li class="hasSub"><a href="#" class="notExpand">List plugin <i class="im-paragraph-justify"></i></a>
            <ul class="nav sub list-plugin">
                <li class="hasSub"><a href="#" class="notExpand"> <i class="im-paragraph-justify"></i></a>
                    <ul class="nav sub">
                        <li class="plugin_item" title="specification" data-plugin-id="12785"><a href="javascript:void(0)"><div class="pull-left"><i class="en-shuffle"></i>specifi...</div><i class="st-settings pull-right"></i><div class="pull-right" style="width: 69px"><div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-mini bootstrap-switch-id-enable_plugin"><div class="bootstrap-switch-container"><span class="bootstrap-switch-handle-on bootstrap-switch-primary">ON</span><span class="bootstrap-switch-label">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default">OFF</span><input type="checkbox" checked="" data-size="mini" title="specification" class="plugin_item" id="enable_plugin" name="enable_plugin" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div></div></a>

                        </li>
                    </ul>
                </li>
                <li class="hasSub"><a href="#" class="notExpand">acymailing <i class="im-paragraph-justify"></i></a>
                    <ul class="nav sub">
                        <li class="plugin_item" title="AcyMailing Tag : Subscriber information" data-plugin-id="12806"><a href="javascript:void(0)"><div class="pull-left"><i class="en-shuffle"></i>AcyMail...</div><i class="st-settings pull-right"></i><div class="pull-right" style="width: 69px"><div class="icheckbox_flat-blue" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-off bootstrap-switch-mini bootstrap-switch-id-enable_plugin"><div class="bootstrap-switch-container"><span class="bootstrap-switch-handle-on bootstrap-switch-primary">ON</span><span class="bootstrap-switch-label">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default">OFF</span><input type="checkbox" data-size="mini" title="AcyMailing Tag : Subscriber information" class="plugin_item" id="enable_plugin" name="enable_plugin" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div></div></a>

                        </li>
                        <li class="plugin_item" title="AcyMailing Tag : Joomla User Information" data-plugin-id="12808"><a href="javascript:void(0)"><div class="pull-left"><i class="en-shuffle"></i>AcyMail...</div><i class="st-settings pull-right"></i><div class="pull-right" style="width: 69px"><div class="icheckbox_flat-blue" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-off bootstrap-switch-mini bootstrap-switch-id-enable_plugin"><div class="bootstrap-switch-container"><span class="bootstrap-switch-handle-on bootstrap-switch-primary">ON</span><span class="bootstrap-switch-label">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default">OFF</span><input type="checkbox" data-size="mini" title="AcyMailing Tag : Joomla User Information" class="plugin_item" id="enable_plugin" name="enable_plugin" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div></div></a>

                        </li>
                    </ul>
                </li>
                <li class="hasSub"><a href="#" class="notExpand">authentication <i class="im-paragraph-justify"></i></a>
                    <ul class="nav sub">
                        <li class="plugin_item" title="plg_authentication_joomla" data-plugin-id="12751"><a href="javascript:void(0)"><div class="pull-left"><i class="en-shuffle"></i>Authent...</div><i class="st-settings pull-right"></i><div class="pull-right" style="width: 69px"><div class="icheckbox_flat-blue checked" style="position: relative;"><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-mini bootstrap-switch-id-enable_plugin"><div class="bootstrap-switch-container"><span class="bootstrap-switch-handle-on bootstrap-switch-primary">ON</span><span class="bootstrap-switch-label">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default">OFF</span><input type="checkbox" checked="" data-size="mini" title="plg_authentication_joomla" class="plugin_item" id="enable_plugin" name="enable_plugin" style="position: absolute; opacity: 0;"></div></div><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins></div></div></a>

                        </li>
                    </ul>
                </li>
            </ul>
        </li>

    </ul>


</div>

<style>

    .wrapper-menu-module{
        background: <?php echo $parammodule->color; ?>;
        position: absolute;
        border: 1px solid black;
    }
    .moduleMenu{
        position: relative;
        list-style: none;
    }
    .moduleMenu li.hasSub {
        float: left;
        width: 100%;
        margin-top: 0px;
        border-right: 1px solid #e7e7e2;

    }
    .moduleMenu li a {
        padding: 15px;
        position: relative;
        color: #768399;
        height: 50px;
        border-radius: 0;

        -webkit-transition: background;
        transition: background;
        -webkit-transition-duration: 0.4s;
        transition-duration: 0.4s;
    }
    .moduleMenu li a i {
        font-size: 20px;
        margin-right: 15px;
        float: left;
        color: #acb1b8;
        -webkit-transition: all;
        transition: all;
        -webkit-transition-duration: 0.4s;
        transition-duration: 0.4s;
        width: auto;
        margin-bottom: 0;
        text-shadow: 1px 1px 1px #ffffff;
    }
    .moduleMenu li a i.moduleMenu-arrow {
        float: right;
        margin-right: 0;
        margin-left: 5px;
    }
    .moduleMenu li a i.s16 {
        right: 17px;
    }
    .moduleMenu li a .mynotification {
        position: relative;
        display: inline-block;
        top: auto;
        right: auto;
        float: right;
        margin-left: 10px;
    }
    .moduleMenu li a .indicator {
        position: absolute;
        top: -1px;
        left: 0;
        height: 51px;
        width: 4px;
        background: #75b9e6;
        opacity: 0;
    }
    .moduleMenu li a:hover {
        background: #eaeeef;
    }
    .moduleMenu li a:hover .mynotification.onhover {
        opacity: 1;
    }
    .moduleMenu li a.active {
        background: #ffffff;
        font-weight: bold;
        border-right: 1px solid transparent;
        margin-right: -1px;
        border-top: 1px solid .e0e0da;
        border-bottom: 1px solid .e0e0da;
    }
    .moduleMenu li a.expand {
        background: #EAEEEF;
        border-bottom: 1px solid #e0e0da;
    }
    .moduleMenu li a.expand .mynotification.onhover {
        opacity: 1;
    }
    .moduleMenu li a.expand i {
        color: #75b9e6;
    }
    .moduleMenu li .sub {
        display: none;
        margin-right: -1px;
    }
    .moduleMenu li .sub a {
        background: .fcfcfd;
        border-top: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }
    .moduleMenu li .sub a.active {
        background: #ffffff;
        font-weight: bold;
        border-right: 1px solid transparent;
        margin-right: -1px;
        border-top: 1px solid .e0e0da;
        border-bottom: 1px solid .e0e0da;
    }
    .moduleMenu li .sub a:hover {
        background: #eaeeef;
    }
    .moduleMenu li .sub li.hasSub > a {
        border-top: 1px solid #e0e0da;
    }
    .moduleMenu li .sub li.hasSub > a.expand {
        background: #eaeeef;
    }
    .moduleMenu li .sub.show li:last-child > a {
        border-bottom: 1px solid #e0e0da;
    }
    .moduleMenu .hasSub a.expand {
        border-top-color: transparent;
    }

    .moduleMenu-arrow{
        float: right;
        margin-right: 0;
        margin-left: 5px;
    }
    .moduleMenu .moduleMenu-top-search {
        position: relative;
    }
    .moduleMenu .moduleMenu-top-search input {
        border: none;
        border-bottom: 1px solid transparent;
        background: none;
        height: 50px;
        padding-left: 50px;
        width: 100%;
    }
    .moduleMenu .moduleMenu-top-search input:focus {
        border-bottom: 1px solid #e0e0da;
    }
    .moduleMenu .moduleMenu-top-search button {
        position: absolute;
        left: 8px;
        top: 13px;
        border: none;
        background: none;
    }
    .moduleMenu .moduleMenu-top-search button i {
        color: #acb1b8;
    }

    .mynotification.onhover {
        opacity: 0;
    }


    .mynotification {
        background-color: #f68484;
        border-radius: 4px;
        color: #ffffff;
        font: bold 11px/20px Arial;
        min-width: 20px;
        padding-left: 4px;
        padding-right: 4px;
        position: absolute;
        right: 5px;
        text-align: center;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
        top: 6px;
    }
    /*.moduleMenu ul li.hasSub a:hover {*/
        /*background: #eaeeef;*/
    /*}*/
    /*.moduleMenu ul li.hasSub a:hover .notification.onhover {*/
        /*opacity: 1;*/
    /*}*/
</style>

