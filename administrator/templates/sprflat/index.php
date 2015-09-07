<?php
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
require_once JPATH_ROOT . '/administrator/templates/sprflat/helper/template.php';
//$doc->addScript(JUri::root().'/templates/sprflat/assets/js/libs/jquery-2.1.1.min.js');
JHtml::_('jquery.ui', array('core', 'sortable'));
$doc->addStyleSheet(JUri::root() . '/media/jui/jquery-ui-1.11.1/themes/base/all.css');
$doc->addScript(JUri::root() . '/global_css_images_js/js/getevent.js');
$doc->addScript(JUri::root() . '/media/jui/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . '/media/jui/jquery-ui-1.11.1/ui/resizable.js');
$doc->addScript(JUri::root() . '/media/jui/jquery-ui-1.11.1/ui/dialog.js');
$doc->addScript(JUri::root() . '/media/jui/jquery-ui-1.11.1/ui/droppable.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/js/libs/excanvas.min.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/js/libs/html5.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/js/libs/respond.min.js');

JHtml::_('bootstrap.framework');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/js/jRespond.min.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/plugins/core/quicksearch/jquery.quicksearch.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/plugins/misc/countTo/jquery.countTo.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/plugins/forms/icheck/jquery.icheck.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/plugins/forms/icheck/jquery.icheck.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.min.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/plugins/core/slimscroll/jquery.slimscroll.horizontal.min.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/assets/js/jquery.sprFlat.js');
$doc->addScript(JUri::root() . '/administrator/templates/sprflat/js/template.js');


$lessInput = JPATH_ROOT . '/administrator/templates/sprflat/assets/less/main.less';
$cssOutput = JPATH_ROOT . '/administrator/templates/sprflat/assets/css/main.css';
templateSprflatHelper::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . '/administrator/templates/sprflat/assets/css/main.css');


$lessInput = JPATH_ROOT . '/administrator/templates/sprflat/assets/less/custom.less';
$cssOutput = JPATH_ROOT . '/administrator/templates/sprflat/assets/css/custom.css';
templateSprflatHelper::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . '/administrator/templates/sprflat/assets/css/custom.css');



$app=JFactory::getApplication();
$input=$app->input;

$option = $input->get('option', '');
$view = $input->get('view', '');
$layout = $input->get('layout', '');
$task = $input->get('task', '');
$itemid = $input->get('Itemid', '');
$sitename = $app->get('sitename');

require_once JPATH_ROOT . '/libraries/less.php-master/lessc.inc.php';
$options = array('cache_dir' => JPATH_ROOT . '/media/jui/bootstrap-3.3.0/cache');

try{
    $parser = new Less_Parser($options);
    $parser->parseFile(JPATH_ROOT . '/media/jui/bootstrap-3.3.0/less/bootstrap.less');
//$parser->ModifyVars( array('font-size-base'=>'16px') );
    /*$parser->ModifyVars(array(
        'grid-gutter-width' => '30px',
        'container-large-desktop' => '1024px',

    ));*/
//    $css = $parser->getCss();
//    JFile::write(JPATH_ROOT.'/media/jui/bootstrap-3.3.0/dist/css/bootstrap3.css',$css);
}catch(Exception $e){
    $error_message = $e->getMessage();
}
echo $error_message;

$doc->addStyleSheet(JUri::root() . '/media/jui/bootstrap-3.3.0/dist/css/bootstrap3.css');

//bootrap2
$options = array('cache_dir' => JPATH_ROOT . '/media/jui/bootstrap-3.3.0/cache');

try{
    $parser = new Less_Parser($options);
    $parser->parseFile(JPATH_ROOT . '/media/jui/bootstrap-2.3.2/less/bootstrap.less');
//$parser->ModifyVars( array('font-size-base'=>'16px') );
    /*$parser->ModifyVars(array(
        'grid-gutter-width' => '30px',
        'container-large-desktop' => '1024px',

    ));*/
//    $css = $parser->getCss();
//    JFile::write(JPATH_ROOT.'/media/jui/bootstrap-2.3.2/css/bootstrap2.css',$css);
}catch(Exception $e){
    $error_message = $e->getMessage();
}
echo $error_message;

//$doc->addStyleSheet(JUri::root() . '/media/jui/bootstrap-2.3.2/css/bootstrap2.css');


$uri = JFactory::getURI();
$host = $uri->toString(array('scheme', 'host', 'port'));
$js = '
		jQuery.noConflict();
		var url_root="' . JUri::root() . '";
		var this_host="' . $host . '";
		var currentLink="' . $uri->toString() . '";
		';
$doc->addScriptDeclaration($js);

?>


<!DOCTYPE html>
<html lang=en>

<meta http-equiv="content-type" content="text/html;charset=utf-8"/>

<head>
    <meta charset=utf-8>
    <title>Dashboard | sprFlat - Admin Template</title>
    <!-- Mobile specific metas -->
    <jdoc:include type="head"/>
    <meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1">
    <!-- Force IE9 to render in normal mode -->
    <!--[if IE]>
    <meta http-equiv="x-ua-compatible" content="IE=9"/><![endif]-->
    <meta name=author content=SuggeElson>
    <meta name=description
          content="sprFlat admin template - new premium responsive admin template. This template is designed to help you build the site administration without losing valuable time.Template contains all the important functions which must have one backend system.Build on great twitter boostrap framework">
    <meta name=keywords
          content="admin, admin template, admin theme, responsive, responsive admin, responsive admin template, responsive theme, themeforest, 960 grid system, grid, grid theme, liquid, jquery, administration, administration template, administration theme, mobile, touch , responsive layout, boostrap, twitter boostrap">
    <meta name=application-name content="sprFlat admin template">
    <!-- Fav and touch icons -->
    <link rel=apple-touch-icon-precomposed sizes=144x144
          href=<?php echo Juri::root() ?>/administrator/templates/sprflat/assets/img/ico/apple-touch-icon-144-precomposed.png>
    <link rel=apple-touch-icon-precomposed sizes=114x114
          href=<?php echo Juri::root() ?>/administrator/templates/sprflat/assets/img/ico/apple-touch-icon-114-precomposed.png>
    <link rel=apple-touch-icon-precomposed sizes=72x72
          href=<?php echo Juri::root() ?>/administrator/templates/sprflat/assets/img/ico/apple-touch-icon-72-precomposed.png>
    <link rel=apple-touch-icon-precomposed
          href=<?php echo Juri::root() ?>/administrator/templates/sprflat/assets/img/ico/apple-touch-icon-57-precomposed.png>
    <link rel=icon href=<?php echo Juri::root() ?>/administrator/templates/sprflat/assets/img/ico/favicon.ico
          type=image/png>
    <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
    <meta name=msapplication-TileColor content=#3399cc>
<body class="admin <?php echo $option . ' view-' . $view . ' layout-' . $layout . ' task-' . $task . ' itemid-' . $itemid; ?>">
<div class="container-fluid">
    <div class="col-lg-6"><img
            src="<?php echo JUri::root() ?>/administrator/components/com_bookpro/assets/images/logo.png"></div>
    <div class="col-lg-6">
        <h1 class="admin-daskboard">
            <?php echo JText::_('Adminitrator daskboard') ?>
        </h1>
    </div>
</div>


<!-- Start #header -->
<div id=header>
    <div class=container-fluid>
        <div class=navbar>
            <div class=navbar-header><a class=navbar-brand href="index.php?option=com_bookpro"><i
                        class="im-windows8 text-logo-element animated bounceIn"></i><span
                        class=text-logo>Asian</span><span class=text-slogan></span></a></div>
            <nav class=top-nav role=navigation>
                <ul class="nav navbar-nav pull-left">
                    <li id=toggle-sidebar-li><a href=# id=toggle-sidebar><i class=en-arrow-left2></i></a></li>
                    <li><a href=# class=full-screen><i class=fa-fullscreen></i></a></li>
                    <li class=dropdown><a href=# data-toggle=dropdown><i class=ec-cog></i><span class=notification>10</span></a>
                        <ul class=dropdown-menu role=menu>
                            <li><a href=#><i class=st-bag></i> Orders <span
                                        class="notification purple">12</span></a></li>
                        </ul>
                    </li>
                    <li class=dropdown><a href=# data-toggle=dropdown><i class=ec-mail></i><span class=notification>4</span></a>
                        <ul class="dropdown-menu email" role=menu>
                            <li class=mail-head>
                                <div class=clearfix>
                                    <div class=pull-left><a href=email-inbox.html><i class=ec-archive></i></a></div>
                                    <span>Inbox</span>

                                    <div class=pull-right><a href=email-inbox.html><i class=st-pencil></i></a></div>
                                </div>
                            </li>
                            <li class=search-email>
                                <form>
                                    <input name=search placeholder="Search for emails">
                                    <button type=submit><i class=ec-search></i></button>
                                </form>
                            </li>
                            <li class="mail-list clearfix"><a href=#><img
                                        src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/128.jpg
                                        class="mail-avatar pull-left" alt=avatar>

                                    <p class=name><span class=status><i class=en-dot></i></span> Jason Rivera <span
                                            class=notification>2</span> <span class=time>12:30 am</span></p>

                                    <p class=msg>I contact you regarding my account please can you set up my pass
                                        ...</p>
                                </a></li>
                            <li class="mail-list clearfix"><a href=#><img
                                        src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/129.jpg
                                        class="mail-avatar pull-left" alt=avatar>

                                    <p class=name><span class="status off"><i class=en-dot></i></span> Steeve Mclark
                                        <span class=notification>6</span> <span class=time>10:26 am</span></p>

                                    <p class=msg>Good job dude awesome work here, please add theese features ...</p>
                                </a></li>
                            <li class="mail-list clearfix"><a href=#><img
                                        src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/130.jpg
                                        class="mail-avatar pull-left" alt=avatar>

                                    <p class=name><span class="status off"><i class=en-dot></i></span> Fellix Jones
                                        <span class=notification>1</span> <span class=time>7:15 am</span></p>

                                    <p class=msg>I have some issues when try to reach my product page can you
                                        ...</p>
                                </a></li>
                            <li class="mail-list clearfix"><a href=#><img
                                        src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/131.jpg
                                        class="mail-avatar pull-left" alt=avatar>

                                    <p class=name><span class=status><i class=en-dot></i></span> Tina Dowsen <span
                                            class=notification>5</span> <span class=time>03:46 am</span></p>

                                    <p class=msg>Hello Sugge, i want to apply for your referal program , please
                                        ...</p>
                                </a></li>
                            <li class=mail-more><a href=email-inbox.html>View all <i class=en-arrow-right7></i></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li><a href=# id=toggle-header-area><i class=ec-download></i></a></li>
                    <li><a href=# id="preview"><i class="im-eye turn_off_preview"></i></a></li>
                    <li class=dropdown><a href=# data-toggle=dropdown><i class=br-alarm></i> <span
                                class=notification>5</span></a>
                        <ul class="dropdown-menu notification-menu right" role=menu>
                            <li class=clearfix><i class=ec-chat></i> <a href=# class=notification-user>Ric Jones</a>
                                <span class=notification-action>replied to your</span> <a href=#
                                                                                          class=notification-link>comment</a>
                            </li>
                            <li class=clearfix><i class=st-pencil></i> <a href=#
                                                                          class=notification-user>SuggeElson</a>
                                <span class=notification-action>just write a</span> <a href=#
                                                                                       class=notification-link>post</a>
                            </li>
                            <li class=clearfix><i class=ec-trashcan></i> <a href=# class=notification-user>SuperAdmin</a>
                                <span class=notification-action>just remove</span> <a href=#
                                                                                      class=notification-link>12
                                    files</a></li>
                            <li class=clearfix><i class=st-paperclip></i> <a href=# class=notification-user>C.
                                    Wiilde</a> <span class=notification-action>attach</span> <a href=#
                                                                                                class=notification-link>3
                                    files</a></li>
                            <li class=clearfix><i class=st-support></i> <a href=# class=notification-user>John
                                    Simpson</a> <span class=notification-action>add support</span> <a href=#
                                                                                                      class=notification-link>ticket</a>
                            </li>
                        </ul>
                    </li>
                    <li class=dropdown><a href=# data-toggle=dropdown><img class=user-avatar
                                                                           src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg
                                                                           alt="<?php echo $user->name ?>"> <?php echo $user->name ?>
                        </a>
                        <ul class="dropdown-menu right" role=menu>
                            <li><a href=index.php?option=com_users&view=user&layout=profile><i class=st-user></i>
                                    Profile</a></li>
                            <li><a href=#><i class=st-settings></i> Settings</a></li>
                            <li>
                                <a href="<?php echo jRoute::_('index.php?option=com_users&task=user.logout&' . jSession::getFormToken() . '=1&return=' . base64_encode('index.php?option=com_users&view=login')) ?>"><i
                                        class=im-exit></i> Logout</a></li>
                        </ul>
                    </li>
                    <li id=toggle-right-sidebar-li><a href=# id=toggle-right-sidebar><i class=ec-users></i> <span
                                class=notification>3</span></a></li>
                    <li id=toggle-right-sidebar-tool-li><a href=# id=toggle-right-sidebar-tool><i
                                class=en-arrow-right3></i></a></li>
                </ul>
            </nav>
        </div>
        <!-- Start #header-area -->
        <div id=header-area class=fadeInDown>
            <?php
            include JPATH_ROOT . '/libraries/adminbackend/admintop.php';
            ?>

        </div>
        <!-- End #header-area -->
    </div>
    <!-- Start .header-inner -->
</div>
<div id="main-content" class="container-fluid">
<?php


$view=$app->input->get('view','bookpro','string');
$layout=$app->input->getString('layout','default','string');
$show_col_lg=($view=='bookpro'&&$layout=='default')?true:false;
?>

    <!-- End #header -->
    <!-- Start #sidebar -->
    <?php if($show_col_lg){ ?>
    <div id=sidebar class="col-lg-3">
        <!-- Start .sidebar-inner -->
        <div class=sidebar-inner>
            <!-- Start #sideNav -->
            <?php
            include JPATH_ROOT . '/libraries/adminbackend/adminleft.php';
            ?>
            <!-- End #sideNav -->

        </div>
        <!-- End .sidebar-inner -->
    </div>
    <?php } ?>
    <!-- End #sidebar -->

    <!-- Start #content -->
    <div id=content class="<?php echo $show_col_lg?'col-lg-9':'' ?>">
        <!-- Start .content-wrapper -->
        <div class=content-wrapper>
            <div class="row">
                <?php if(!$show_col_lg) include JPATH_ROOT.'/administrator/components/com_bookpro/views/bookpro/tmpl/mainmenu_default.php' ?>
            </div>
            <jdoc:include type="component"/>

            <!-- End .row -->


        </div>
        <!-- End .content-wrapper -->
        <div class=clearfix></div>
    </div>
</div>
<!-- End #content -->

