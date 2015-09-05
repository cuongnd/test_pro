<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/purl-master/purl-master/purl.js');
$doc->addScript(JUri::root() . '/templates/sprflat/js/jquery.blockproperties.js');
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 3/3/2015
 * Time: 10:30 PM
 */
$website=JFactory::getWebsite();
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_website/models');
$modelWebsite=JModelLegacy::getInstance('Website','WebsiteModel');


JForm::addFormPath(JPATH_ROOT . '/components/com_modules/models/forms');
JForm::addFieldPath(JPATH_ROOT . '/components/com_modules/fields');
/*$modelWebsite->setState('website.id',$website->website_id);
$form=$modelWebsite->getForm();
$fieldSet = $form->getFieldset();

foreach ($fieldSet as $field)
{
    $html[] = $field->renderField(array(),true);
}*/
?>
<div id="blockproperties" class="block-properties panel panel-primary panel-teal  panelMove toggle " data-properties-type="website">
    <div class="panel-heading">
        <h4 class="panel-title">Properties</h4>
    </div>
    <div class="panel-body">
        <div class="properties website">
            <?php //echo implode('<br/>',$html) ?>
        </div>
    </div>
    <div class="panel-footer teal-bg">
        <button class="btn btn-danger save-block-properties pull-right"  type="button"><i class="fa-save"></i>Save</button>&nbsp;&nbsp;
        <button class="btn btn-danger cancel-block-properties pull-right"  type="button"><i class="br-refresh"></i>Reset</button>
    </div>
</div>