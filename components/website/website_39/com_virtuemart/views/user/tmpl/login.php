<?php
$app=JFactory::getApplication();
$menu=$app->getMenu();
$menu_active_item=$menu->getActive();
$configviewlayout=$menu_active_item->configviewlayout;
JHTML::_('behavior.modal');
$menu_item_return=$configviewlayout->get('menu_item_return',0);

$user=JFactory::getUser();
if($user->id!=0)
{
    $app->redirect(JUri::root().'index.php?Itemid='.$menu_item_return);
}
require_once JPATH_ROOT.'/components/com_users/helpers/facebook.php';
$loginUrl = facebook_helper::get_login_url();
require_once JPATH_ROOT.'/components/com_users/helpers/google.php';
$authUrl = google_helper::get_login_url();


?>

<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/position.js');

$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/sortable.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/resizable.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/dialog.js');


$doc->addScript(JPATH_VM_URL.'/assets/js/view_user_login.js');
$doc->addLessStyleSheetTest(JPATH_VM_URL.'/assets/less/view_user_login.less');
$doc->addStyleSheet(JUri::root().'/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css');
$doc->addStyleSheet(JUri::root().'/media/jui_front_end/jquery-ui-1.11.1/themes/base/dialog.css');
$scriptId='view_user_login';
ob_start();
?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-user-login').view_user_login({


        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


?>
<div class="view-user-login">
    <div id=login class="animated bounceIn">
        <!-- Start .login-wrapper -->
        <div class=login-wrapper>
            <ul id=myTab class="nav nav-tabs nav-justified bn">
                <li><a href=#log-in data-toggle=tab>Login</a></li>
                <li><a href=#register data-toggle=tab>Register</a></li>
            </ul>
            <div id=myTabContent class="tab-content bn">
                <div class="tab-pane fade active in" id=log-in>
                    <div class="social-buttons text-center mt10"><a href="<?php echo $loginUrl ?>" target="_self"
                                                                    class="btn btn-primary btn-alt btn-round btn-lg mr10"><i
                                class="fa-facebook s24"></i></a> <a href=#
                                                                    class="btn btn-primary btn-alt btn-round btn-lg mr10"><i
                                class="fa-twitter s24"></i></a> <a href="<?php echo $authUrl ?>"
                                                                   class="btn btn-danger btn-alt btn-round btn-lg mr10"><i
                                class="fa-google-plus s24"></i></a> <a href=#
                                                                       class="btn btn-info btn-alt btn-round btn-lg"><i
                                class="fa-linkedin s24"></i></a></div>
                    <div class=seperator><strong>or</strong>
                        <hr>
                    </div>
                    <form class="form-horizontal mt10" action=index.html id=login-form role=form>
                        <div class=form-group>
                            <div class=col-lg-12>
                                <input name=email id=email class="form-control left-icon" value=admin@sprflat.com
                                       placeholder="Your email ...">
                                <i class="ec-user s16 left-input-icon"></i></div>
                        </div>
                        <div class=form-group>
                            <div class=col-lg-12>
                                <input type=password name=password id=password class="form-control left-icon"
                                       value=somepass
                                       placeholder="Your password">
                                <i class="ec-locked s16 left-input-icon"></i> <span class=help-block><a href=#>
                                        <small>Forgout password ?</small>
                                    </a></span></div>
                        </div>
                        <div class=form-group>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8">
                                <!-- col-lg-12 start here -->
                                <label class=checkbox>
                                    <input type=checkbox name=remember id=remember value=option>
                                    Remember me ?</label>
                            </div>
                            <!-- col-lg-12 end here -->
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-4">
                                <!-- col-lg-12 start here -->
                                <button class="btn btn-success pull-right" data-jtask="user.login" type=button>Login
                                </button>
                            </div>
                            <!-- col-lg-12 end here -->
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id=register>
                    <form class="form-horizontal mt20" action=# id=register-form role=form>
                        <div class=form-group>
                            <div class=col-lg-12>
                                <!-- col-lg-12 start here -->
                                <input id=email1 name=email type=email class="form-control left-icon"
                                       placeholder="Type your email">
                                <i class="ec-mail s16 left-input-icon"></i></div>
                            <!-- col-lg-12 end here -->
                        </div>
                        <div class=form-group>
                            <div class=col-lg-12>
                                <!-- col-lg-12 start here -->
                                <input type=password class="form-control left-icon" id=password1 name=password
                                       placeholder="Enter your password">
                                <i class="ec-locked s16 left-input-icon"></i></div>
                            <!-- col-lg-12 end here -->
                            <div class="col-lg-12 mt15">
                                <!-- col-lg-12 start here -->
                                <input type=password class="form-control left-icon" id=confirm_password
                                       name=confirm_passowrd placeholder="Repeat password">
                                <i class="ec-locked s16 left-input-icon"></i></div>
                            <!-- col-lg-12 end here -->
                        </div>
                        <div class=form-group>
                            <div class=col-lg-12>
                                <!-- col-lg-12 start here -->
                                <button class="btn btn-success btn-block" type="button" data-jtask="user.register">
                                    Register
                                </button>
                            </div>
                            <!-- col-lg-12 end here -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End #.login-wrapper -->
    </div>
</div>
