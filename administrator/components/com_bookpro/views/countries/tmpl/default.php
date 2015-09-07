<?php
defined('_JEXEC') or die('Restricted access');
///JHtml::_('behavior.modal','a.jbmodal');
//JHTML::_('behavior.tooltip');
JHtml::_('jquery.framework');
JHTML:: _('behavior.framework');
JHTML:: _('behavior.modal');

//$itemsCount = count($this->items);
/*echo "<pre>";
print_r($this->items);die();*/

$pagination = &$this->pagination;
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-countries-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-countries-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-countries-default.css');
$doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.common.min.css');
$doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.default.min.css');
$doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.default.min.css');
$doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.min.css');
$doc->addStyleSheet(JUri::root() . '/media/kendotest/kendo.dataviz.material.min.css');

//$doc->addScript(JUri::root() . 'media/kendotest/kendo.all.js');
//$doc->addScript(JUri::root() . 'media/kendotest/kendo.grid.js');
$doc->addScript(JUri::root() . 'media/kendotest/kendo.web.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/assets/js/view-tourcountry-default.js');

//$doc->addStyleSheet(JUri::root() . 'administrator/components/com_bookpro/assets/css/jquery-ui.css');

$item_data=$this->items;


$js='
var item_data="'.$item_data.'";
';
$doc->addScriptDeclaration($js);



/*echo "<pre>";
print_r(item_data);die();*/
?>
<div class="row" style="margin-top: 20px">
    <div class="col-md-9">

    </div>
    <div class="col-md-3">
        <label><?php echo JText::_('COUNTRY GEO') ?></label>
    </div>
</div>


<div id="example">
    <div id="grid"></div>
</div>