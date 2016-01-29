<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'media/jquery/jquery-2.1.1.min.js');
$doc->addScript(JUri::root().'media/jquery-validation-1.13.1/dist/jquery.validate.min.js');
$doc->addScript(JUri::root().'media/bootstrap-3.3.0/dist/js/bootstrap.min.js');
$doc->addStyleSheet(JUri::root().'components/com_website/assets/css/view-website-default.css');
$doc->addScript(JUri::root().'components/com_website/assets/js/view-website-default.js');

$uri=JFactory::getURI();
$host=$uri->getHost();
$host=str_replace('www.','',$host);
$host='.'.$host;
$js='
var url_root="'.JUri::root().'";
var this_host="'.$host.'";
';
$doc->addScriptDeclaration($js);




?>

<div class="error"><i class="icon-error"></i><span class="content-error"></span></div>
<div class="create-website">
    <form id="create-website" name="create_website" class="form-horizontal" role="form">
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label"><?php echo JText::_('Email') ?></label>
            <div class="col-sm-10">
                <input type="email" class="form-control required" name="email" autocomplete="off" id="email" placeholder="email@example.com">
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
                    <input type="text" name="your_domain" id="your_domain" autocomplete="off" class="form-control required" minlength="3" maxlength="40" placeholder="example.com">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="<?php echo JText::_('This is your domain, you need point your domain to Ip:66.96.147.102') ?>"></span>
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
                    <input type="text" name="sub_domain" id="sub_domain" autocomplete="off" class="form-control required" minlength="3" maxlength="40" placeholder="company">
                    <div class="input-group-addon">
                        <div>.shoponline123.net</div>
                    </div><!-- /btn-group -->
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" data-placement="top" title="Tooltip on left"></span>
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
                <input type="button"  class="create-website  btn btn-primary" data-loading-text="Checking..." data-reset-text="Create" value="Submit" />


            </div>
        </div>

        <input type="hidden" name="option" value="com_website">
        <input type="hidden" name="formName" value="formBase">
        <input type="hidden" name="task" value="website.next">
    </form>
</div>
