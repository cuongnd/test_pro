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
JToolBarHelper::deleteList('', 'trash', 'Trash');

$colspan = $this->selectable ? 7 : 10;

$editSubject = $this->escape(JText::_('COM_BOOKPRO_TOUR_EDIT'));
$notFound = '- ' . JText::_('not found') . ' -';
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);

$pagination = &$this->pagination;
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-tours-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-tours-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);

//$doc->addScript(JUri::root().'/media/kendotest/kendo.all.js');
$doc->addScript(JUri::root().'/media/kendotest/kendo.core.js');
$doc->addScript(JUri::root().'/media/kendotest/kendo.data.js');
$doc->addScript(JUri::root().'/media/kendotest/kendo.web.js');
$doc->addScript(JUri::root().'/media/kendotest/kendo.grid.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/assets/js/view-tours-default.js');
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-tours-default.css');

$doc->addStyleSheet(JUri::root().'/media/telerik.kendoui.2015.1.318.core/src/styles/web/kendo.common.core.css');
$doc->addStyleSheet(JUri::root().'/media/telerik.kendoui.2015.1.318.core/src/styles/web/kendo.default.css');
$doc->addStyleSheet(JUri::root().'/media/telerik.kendoui.2015.1.318.core/src/styles/web/kendo.common.core.css');






$doc->addStyleSheet(JUri::root().'/media/kendotest/kendo.common.min.css');
$doc->addStyleSheet(JUri::root().'/media/kendotest/kendo.default.min.css');
$doc->addStyleSheet(JUri::root().'/media/kendotest/kendo.dataviz.min.css');
$doc->addStyleSheet(JUri::root().'/media/kendotest/kendo.dataviz.default.min.css');

$doc->addStyleSheet(JUri::root().'/media/kendotest/kendo.default.mobile.min.css');
$doc->addStyleSheet(JUri::root().'/media/kendotest/kendo.material.min.css');

//$doc->addScript(JUri::root().'/media/system/js/jquery.sortingTable/js/jquery.sortingtable.js');


/*$lessInput=JPATH_ROOT.'/media/system/js/jquery.sortingTable/less/jquery.sortingtable.less';
$cssOutput=JPATH_ROOT.'/media/system/js/jquery.sortingTable/css/jquery.sortingtable.css';
BookProHelper::compileLess($lessInput,$cssOutput);
$doc->addStyleSheet(JUri::root().'/media/system/js/jquery.sortingTable/css/jquery.sortingtable.css');*/

$doc->addStyleSheet(JUri::root().'administrator/components/com_bookpro/assets/css/jquery-ui.css');
$doc->addScript(JUri::root().'administrator/templates/sprflat/assets/js/libs/jquery-ui-1.10.4.min.js');


?>



<div class="row">
    <div class="col-md-9">

    </div>
    <div class="col-md-3">
        <label>Tour Listing</label>
    </div>
</div>
<div class="view-tours-default" xmlns="http://www.w3.org/1999/html">

    <form action="index.php" method="post" name="adminForm" id="adminForm">



        <fieldset id="filter-bar">

            <div class="filter-search">
                <div class="pull-left">
                    <p><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_NAME') ?></p>
                    <select id="select-tour-name">
                        <option value="0" disabled selected>Tour 0</option>
                        <option value="1">Tour 1</option>
                        <option value="2">Tour 2</option>
                        <option value="3">Tour 3</option>
                    </select>
                </div>

                <div class="pull-left">
                    <p><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_CODE') ?></p>
                    <input type="text" name="title"
                           value="<?php echo $this->lists['title'] ?>"
                           placeholder="<?php echo JText::_('COM_BOOKPRO_TOUR_PLACEHOLDER_TITLE') ?>"
                           style="width: 100px">

                </div>

                <div class="pull-left">
                    <p><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_TOUR_TYLE') ?></p>
                    <select id="select-tour-type">
                        <option value="0" disabled selected>tour tyle 0</option>
                        <option value="1">tour tyle 1</option>
                        <option value="2">tour tyle 2</option>
                        <option value="3">tour tyle 3</option>
                    </select>
                </div>
                <div class="pull-left">
                    <p><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_TOUR_STYLE') ?></p>
                    <select id="select-tour-style">
                        <option value="0" disabled selected>tour style 0</option>
                        <option value="1">tour style 1</option>
                        <option value="2">tour style 2</option>
                        <option value="3">tour style 3</option>
                    </select>
                </div>
                <div class="pull-left">
                    <p><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_ACTIVE') ?></p>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value="0">active 0</option>
                        <option value="1">active 1</option>
                        <option value="2">active 2</option>
                        <option value="3">active 3</option>
                    </select>
                </div>

                <div class="pull-left">
                    <button onclick="this.form.submit();" class="btn search">
                        <?php echo JText::_('COM_BOOKPRO_SEARCH'); ?>
                    </button>

                </div>
            </div>


        </fieldset>


        <div class="main-subhead">

        </div>


        <div class="pagination-limitbox">
                <?php echo $pagination->getListFooter(); ?>
                <?php echo $this->pagination->getLimitBox(); ?>
                <span><?php echo JText::_('COM_BOOKPRO_TOUR_LABEL_LIMIT_BOX') ?></span>
            </div>

            <div class="table-infomation">
                <table class="adminlist table-striped table sortingtable">
                    <thead>
                    <tr>

                        <?php if (!$this->selectable) {
                            ?>
                            <th width="1%"><input type="checkbox" class="inputCheckbox"
                                                  name="toggle" value="" onclick="Joomla.checkAll(this);"/>
                            </th>
                        <?php } ?>

                        <th width="5%"><?php echo JText::_("ID"); ?></th>

                        <th width="20%">
                            <?php echo JText::_('TOUR NAME'); ?>
                        </th>
                        <th width="10%"><?php echo JText::_('TOUR CODE'); ?>
                        </th>
                        <th width="10%"><?php echo JText::_('TOUR TYPE') ?>
                        </th>
                        <th width="10%"><?php echo JText::_('TOUR STYLE') ?>
                        </th>
                        <th width="12%"><?php echo JText::_('START-END CITY') ?>
                        </th>
                        <th width="5%"><?php echo JText::_('PRICE'); ?>
                        </th>

                        <th width="5%"><?php echo JText::_('HOTEL'); ?>
                        </th>

                        <th width="8%"><?php echo JText::_('ADD ONS'); ?>
                        </th>
                        <th width="5%"><?php echo JText::_('PAYMENT'); ?>
                        </th>
                        <th width="5%"><?php echo JText::_('ALLOCATION'); ?>
                        </th>
                        <th width="5%"><?php echo JText::_('PROMOTION'); ?>
                        </th>
                        <th width="5%"><?php echo JText::_('DICOUNT'); ?>
                        </th>
                        <th width="5%"><?php echo JText::_('ASSIGN'); ?>
                        </th>
                        <th width="5%"><?php echo JText::_('ACTION'); ?>
                        </th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($this->items as $item): ?>
                        <tr>

                            <td class="checkboxCell"><?php echo JHTML::_('grid.checkedout', $subject, $i); ?>
                            </td>
                            <td><?php echo $item->id; ?></td>

                            <td>
                                <a href="<?php echo JRoute::_('index.php?option=com_bookpro&view=tour&cid=' . $item->id) ?> ">
                                    <?php echo $item->title; ?></a></td>
                            <td><?php echo $item->code; ?></td>
                            <td><?php echo $item->stype; ?></td>

                            <td>Classic Cultures</td>
                            <td>Siem Reap Vientiane</td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                            <td>Hien</td>
                            <td><i class="en-eye"></i><i class="im-pencil"></i></td>
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>


            <input type="hidden" name="option" value="<?php echo OPTION; ?>"/> <input
                type="hidden" name="task"
                value="<?php echo JRequest::getCmd('task'); ?>"/> <input
                type="hidden" name="reset" value="0"/> <input type="hidden"
                                                              name="cid[]" value=""/> <input type="hidden"
                                                                                             name="boxchecked"
                                                                                             value="0"/> <input
                type="hidden" name="controller"
                value="<?php echo CONTROLLER_TOUR; ?>"/> <input type="hidden"
                                                                name="filter_order" value="<?php echo $order; ?>"/>
            <input
                type="hidden" name="filter_order_Dir"
                value="<?php echo $orderDir; ?>"/> <input type="hidden"
                                                          name="<?php echo SESSION_TESTER; ?>" value="1"/>
            <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
