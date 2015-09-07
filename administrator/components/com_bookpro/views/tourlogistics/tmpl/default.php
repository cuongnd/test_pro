<?php

defined('_JEXEC') or die('Restricted access');

//JHtml::_('behavior.modal','a.jbmodal');
//JHTML::_('behavior.tooltip');
$bar = &JToolBar::getInstance('toolbar');

JToolBarHelper::title(JText::_('COM_BOOKPRO_TOUR_MANAGER'), 'object');
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::divider();
JToolBarHelper::publish();
JToolBarHelper::unpublishList();
AImporter::css('hotel');
JToolBarHelper::deleteList('', 'delete', 'Delete');

$colspan = $this->selectable ? 7 : 10;

$editSubject = $this->escape(JText::_('COM_BOOKPRO_TOUR_EDIT'));
$notFound = '- ' . JText::_('not found') . ' -';
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);

$pagination = &$this->pagination;
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-tours-logistics-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-tours-logistics-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-tours-logistics-default.css');


$doc->addScript(JUri::root() . '/media/system/js/jquery.sortingTable/js/jquery.sortingtable.js');


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



?>
<div class="row" style="margin-top: 20px">
    <div class="col-md-9">

    </div>
    <div class="col-md-3">
        <label><?php echo JText::_('COUNTRY GEO') ?></label>
    </div>
</div>
<div class="view-tours-logistics-default" xmlns="http://www.w3.org/1999/html">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div class="main-subhead">

        </div>
        <div class="pagination-limitbox">
            <?php echo $pagination->getListFooter(); ?>
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
                    <th width="5%" class="view-border-right"><?php echo JText::_("ID"); ?></th>
                    <th width="25%" class="view-border-right">
                        <?php echo JText::_('Country'); ?>
                    </th>
                    <th width="10%" class="view-border-right"><?php echo JText::_('Code'); ?> </th>
                    <th width="10%" class="view-border-right"><?php echo JText::_('Phone Code') ?></th>
                    <th width="10%" class="view-border-right"><?php echo JText::_('State Number'); ?></th>
                    <th width="10%"><?php echo JText::_('Action'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->items as $item): ?>
                    <tr >
                        <td class="checkboxCell"
                            style="border-top: none"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?></td>
                        <td class="view-border-right"><?php echo $item->id; ?></td>
                        <td class="view-border-right logistic-enable-row"  data-column-name="country_name"><a
                                href="#"><?php echo $item->country_name; ?></a>
                        </td>
                        <td class="view-border-right logistic-enable-row" data-column-name="country_code"><?php echo $item->country_code; ?></td>
                        <td class="view-border-right logistic-enable-row" data-column-name="phone_code"> <?php echo $item->phone_code; ?></td>
                        <td class="view-border-right logistic-enable-row" data-column-name="state_number"></td>
                        <td style="border-top: none">
                            <img class="logistic-edit-row"
                                src="<?php echo JUri::root() ?>/administrator/components/com_bookpro/assets/images/icon-action.png">

                            <i class="glyphicon glyphicon-remove logistic-delete-row" style="color: red"></i>
                            <i class="glyphicon glyphicon-floppy-saved logistic-save-row" ></i>
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