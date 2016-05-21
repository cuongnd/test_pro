<?php
$doc = JFactory::getDocument();
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
JHtml::_('jquery.ui');
JHtml::_('behavior.formvalidation');
$doc->addLessStyleSheetTest(JUri::root() . 'components/com_website/assets/less/view-website-default.less');
$doc->addScript(JUri::root() . '/media/system/js/jquery-validation-1.13.1/dist/jquery.validate.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/bootstrap-3.3.0/dist/js/bootstrap.js');
$doc->addScript(JUri::root() . '/media/system/js/jquery.utility.js');
$doc->addScript(JUri::root() . '/components/com_website/assets/js/view_website_default.js');
require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
$list_websie_enable_create_sub_domain=websiteHelperFrontEnd::get_list_website_enable_create_sub_domain();
$input=JFactory::getApplication()->input;
$action=$input->getString('action','');
$option=array(
    'id'=>'',
    'domain'=>JText::_('please select domain')
);
array_unshift($list_websie_enable_create_sub_domain,$option);

$list_category=websiteHelperFrontEnd::get_category();
$option=array(
    'id'=>'virtuemart_category_id',
    'tree_category'=>JText::_('please select category')
);
array_unshift($list_category,$option);
$uri = JFactory::getURI();

$user = JFactory::getUser();



$scriptId = "script_website_view_website_default";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-website-default').view_website_default({
            action:"<?php echo $action ?>"
        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);



?>
<div  class="view-website-default">


    <div class="error"><i class="icon-error"></i><span class="content-error"></span></div>
    <div class="create-website">
        <form id="create-website-form" name="create_website" class="form-horizontal" role="form">
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label"><?php echo JText::_('Email') ?></label>
                <div class="col-sm-10">
                    <input type="email" class="form-control required" value="<?php echo $user->email ?>" name="email"
                           autocomplete="off" id="email" placeholder="email@example.com">
                </div>
            </div>
            <div class="form-group">
                <label for="virtuemart_category_id" class="col-sm-2 control-label"><?php echo JText::_('Category') ?></label>
                <div class="col-sm-10">
                    <?php //echo JHtml::_('select.genericlist',$list_category,'virtuemart_category_id',array('class'=>'required'),'virtuemart_category_id','tree_category'); ?>
                </div>
            </div>


            <div class="form-group">
                <label for="domain" class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="checkbox">
                        <label>
                            <input name="own_domain" type="checkbox"> <?php echo JText::_('I own domain name') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group field_your_domain" style="display: none">
                <label for="your_domain" class="col-sm-2 control-label"><?php echo JText::_('Your Domain') ?></label>
                <div class="col-sm-10">
                    <div class="input-group your_domain">
                        <div class="input-group-addon">
                            <div>http://wwww.</div>
                        </div><!-- /btn-group -->
                        <input type="text" name="your_domain" id="your_domain" autocomplete="off"
                               class="form-control required" minlength="3" maxlength="40" placeholder="example.com">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top"
                                  title="<?php echo JText::_('This is your domain, you need point your domain to Ip:66.96.147.102') ?>"></span>
                        </div><!-- /btn-group -->
                    </div><!-- /input-group -->
                </div>
            </div>
            <div class="form-group field_suggestionyourdomain">
                <label for="email" class="col-sm-2 control-label"></label>
                <div class="col-sm-10 suggestionyourdomain">

                </div>
            </div>


            <div class="form-group">
                <label for="sub_domain" class="col-sm-2 control-label"><?php echo JText::_('Sub Domain') ?></label>
                <div class="col-sm-10">
                    <div class="input-group sub_domain">
                        <div class="input-group-addon">
                            <div>http://wwww.</div>
                        </div><!-- /btn-group -->
                        <input type="text" name="sub_domain" id="sub_domain" autocomplete="off"
                               class="form-control required" minlength="3" maxlength="40" placeholder="company">
                        <div class="input-group-addon">


                            <?php echo JHtml::_('select.genericlist',$list_websie_enable_create_sub_domain,'domain_id',array('class'=>'required'),'id','domain'); ?>
                        </div><!-- /btn-group -->
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top"
                                  title="Tooltip on left"></span>
                        </div><!-- /btn-group -->
                    </div><!-- /input-group -->
                </div>
            </div>
            <div class="form-group field_suggestionsubdomain">
                <label for="email" class="col-sm-2 control-label"></label>
                <div class="col-sm-10 suggestionsubdomain">

                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="button" class="create-website  btn btn-primary" data-loading-text="Checking..."
                           data-reset-text="Create" value="Submit"/>


                </div>
            </div>

            <input type="hidden" name="option" value="com_website">
            <input type="hidden" name="formName" value="formBase">
            <input type="hidden" name="task" value="website.next">
            <input type="hidden" name="action" value="<?php echo $action ?>">
        </form>
    </div>
</div>