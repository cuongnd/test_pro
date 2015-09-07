<?php
defined('_JEXEC') or die('Restricted access');

///JHtml::_('behavior.modal','a.jbmodal');
//JHTML::_('behavior.tooltip');
JHtml::_('jquery.framework');
JHTML:: _('behavior.framework');
JHTML:: _('behavior.modal');
JHtml::_('behavior.tooltip', '.hasTipPreview');

$itemsCount = count($this->items);

$pagination = &$this->pagination;
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-countries-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-countries-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-countries-default.css');


//$doc->addScript(JUri::root() . '/media/system/js/jquery.sortingTable/js/jquery.sortingtable.js');


$lessInput = JPATH_ROOT . '/media/system/js/jquery.sortingTable/less/jquery.sortingtable.less';
$cssOutput = JPATH_ROOT . '/media/system/js/jquery.sortingTable/css/jquery.sortingtable.css';
BookProHelper::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . '/media/system/js/jquery.sortingTable/css/jquery.sortingtable.css');


$doc->addScript(JUri::root() . 'media/system/js/jquery.bookproEditTable/js/jquery.bookproedittable.js');

$lessInput = JPATH_ROOT . '/media/system/js/jquery.bookproEditTable/less/jquery.bookproedittable.less';
$cssOutput = JPATH_ROOT . '/media/system/js/jquery.bookproEditTable/css/jquery.bookproedittable.css';
BookProHelper::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . '/media/system/js/jquery.bookproEditTable/css/jquery.bookproedittable.css');


$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/assets/js/view-tourlogistics-default.js');

$doc->addStyleSheet(JUri::root() . 'administrator/components/com_bookpro/assets/css/jquery-ui.css');

$script = array();
$script[] = '	function jInsertFieldValue(value, id) {';
$script[] = '		var $ = jQuery.noConflict();';
$script[] = '		var old_value = $("#" + id).val();';
$script[] = '		if (old_value != value) {';
$script[] = '			var $elem = $("#" + id);';
$script[] = '			$elem.val(value);';
$script[] = '			$elem.trigger("change");';
$script[] = '			if (typeof($elem.get(0).onchange) === "function") {';
$script[] = '				$elem.get(0).onchange();';
$script[] = '			}';
$script[] = '			jMediaRefreshPreview(id);';
$script[] = '		}';
$script[] = '	}';

$script[] = '	function jMediaRefreshPreview(id) {';
$script[] = '		var $ = jQuery.noConflict();';
$script[] = '		var value = $("#" + id).val();';
$script[] = '		var $img = $("#" + id + "_preview");';
$script[] = '		if ($img.length) {';
$script[] = '			if (value) {';
$script[] = '				$img.attr("src", "' . JUri::root() . '" + value);';
$script[] = '				$("#" + id + "_preview_empty").hide();';
$script[] = '				$("#" + id + "_preview_img").show()';
$script[] = '			} else { ';
$script[] = '				$img.attr("src", "");';
$script[] = '				$("#" + id + "_preview_empty").show();';
$script[] = '				$("#" + id + "_preview_img").hide();';
$script[] = '			} ';
$script[] = '		} ';
$script[] = '	}';

$script[] = '	function jMediaRefreshPreviewTip(tip)';
$script[] = '	{';
$script[] = '		var $ = jQuery.noConflict();';
$script[] = '		var $tip = $(tip);';
$script[] = '		var $img = $tip.find("img.media-preview");';
$script[] = '		$tip.find("div.tip").css("max-width", "none");';
$script[] = '		var id = $img.attr("id");';
$script[] = '		id = id.substring(0, id.length - "_preview".length);';
$script[] = '		jMediaRefreshPreview(id);';
$script[] = '		$tip.show();';
$script[] = '	}';

// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
$this_host=JUri::root();
$js='
var this_host="'.$this_host.'";
';
$doc->addScriptDeclaration($js);
?>
<div class="row" style="margin-top: 20px">
    <div class="col-md-9">

    </div>
    <div class="col-md-3">
        <label><?php echo JText::_('COUNTRY GEO') ?></label>
    </div>
</div>
<div class="view-countries-default" xmlns="http://www.w3.org/1999/html">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
<!--        <div class="main-subhead">
        </div>-->
        <div class="col-md-12" style="width: 100%;border: 1px #ccc solid;background: #fff">
            <input type="button" class="countries-new-row" value="New" style="float: right;margin: 5px">
        </div>

        <div class="pagination-limitbox">
            <?php /*echo $pagination->getListFooter(); */ ?>
            <?php echo $this->pagination->getLimitBox(); ?>
            <span><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_LIMIT_BOX') ?></span>
        </div>

        <div class="table-infomation">
            <table class="adminlist table-striped table sortingtable  bookpro-edit-table">
                <thead>
                <tr>
                    <?php if (!$this->selectable) {
                        ?>
                        <th width="1%">
                            <input type="checkbox" class="inputCheckbox" name="toggle" value=""
                                   onclick="Joomla.checkAll(this);"/>
                        </th>
                    <?php } ?>
                    <th width="2%" class="view-border-right"><?php echo JText::_("ID"); ?></th>
                    <th width="1%"></th>
                    <th width="20%" class="view-border-right">
                        <?php echo JText::_('Country'); ?>
                    </th>
                    <th width="7%" class="view-border-right"><?php echo JText::_('Code'); ?> </th>
                    <th width="7%" class="view-border-right"><?php echo JText::_('Phone Code') ?></th>
                    <th width="7%" class="view-border-right"><?php echo JText::_('State Number'); ?></th>
                    <th width="7%"><?php echo JText::_('Action'); ?> <input type="button" class="countries-new-row" value="Add"></th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($this->items as $item): ?>
                    <tr>
                        <td class="checkboxCell class_deleterow" style="border-top: none">
                            <?php echo JHTML::_('grid.checkedout', $item, $i); ?>
                            <input type="hidden" name="id" value="<?php echo $item->id ?>">
                        </td>
                        <td class="view-border-right empty_when_edit"><?php echo $item->id; ?></td>
                        <td class="icon-country-edit" style="border: none">
<!--                            <script type="text/javascript">
                                jQuery(document).ready(function($){
                                    $('.logistic-edit-row').click(function(){
                                        tr= $.closest(tr);
                                        tr.find('.icon-country-edit').each(function(){
                                            var data=tr.find('.get-value-img').val();
                                            jQuery('#path_url').append(data);
                                        })
                                    })

                                })
                            </script>-->
                            <!--<img src="<?php /*echo JUri::root() */?>" style="margin-top:2px;width: 20px;height: 17px">-->
                            <img data-path="<?php echo JPATH_ROOT.'/images/icon_country' ?>" src="<?php echo JUri::root().$item->path; ?>" alt="" class="img-icon" style="max-width: none; width: 20px;height: 15px">

                        </td>
                        <td class="view-border-right logistic-enable-row" data-column-name="country_name">
                            <a href="#"><?php echo $item->country_name; ?></a>
                        </td>
                        <td class="view-border-right logistic-enable-row"
                            data-column-name="country_code"><?php echo $item->country_code; ?></td>
                        <td class="view-border-right logistic-enable-row"
                            data-column-name="phone_code"> <?php echo $item->phone_code; ?></td>
                        <td class="view-border-right logistic-enable-row"
                            data-column-name="state_number"><?php echo $item->state_number; ?></td>
                        <td class="show-hide-icon" style="border-top: none">
                            <img class="logistic-edit-row"
                                 src="<?php echo JUri::root() ?>/administrator/components/com_bookpro/assets/images/icon-action.png">
                            <i class="glyphicon glyphicon-remove logistic-delete-row" style="color: red"></i>
                            <i class="glyphicon glyphicon-floppy-saved logistic-update-row"></i>
                            <input type="hidden" value="Save" class="logistic-save-row">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
        <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
        <input type="hidden" name="reset" value="0"/>
        <input type="hidden" name="cid[]" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="controller" value="<?php echo CONTROLLER_TOUR; ?>"/>
        <input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
        <input type="hidden" name="<?php echo SESSION_TESTER; ?>" value="1"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
