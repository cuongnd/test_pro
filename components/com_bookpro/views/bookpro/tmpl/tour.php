<?php
/**
 * @package    Bookpro
 * @author        Nguyen Dinh Cuong
 * @link        http://ibookingonline.com
 * @copyright    Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version    $Id: default.php 66 2012-07-31 23:46:01Z quannv $
 * */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');


JToolBarHelper::title('Dashboard');
$orderDir = $this->lists['order_Dir'];
$order = $this->lists['order'];
$itemsCount = count($this->items);
$pagination = &$this->pagination;
$doc = JFactory::getDocument();
$lessInput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/less/view-bookpro-tour.less';
$cssOutput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/css/view-bookpro-tour.css';
BookProHelper::compileLess($lessInput,$cssOutput);

$doc->addScript(JUri::root() . '/administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/dhtmlxscheduler.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/ext/dhtmlxscheduler_recurring.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/ext/dhtmlxscheduler_minical.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/ext/dhtmlxscheduler_year_view.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/ext/dhtmlxscheduler_agenda_view.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/ext/dhtmlxscheduler_grid_view.js');

$doc->addScript(JUri::root() . 'administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/ext/dhtmlxscheduler_tooltip.js');

$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/dhtmlxscheduler.css');


$doc->addScript(JUri::root().'/media/Highcharts-4.1.1/js/highcharts.js');
$doc->addScript(JUri::root().'/media/Highcharts-4.1.1/js/modules/data.js');
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-bookpro-tour.css');
$doc->addScript(JUri::root().'/administrator/components/com_bookpro/assets/js/view-bookpro-tour.js');
$uri=JFactory::getURI();
$host=$uri->toString(array('scheme','host', 'port'));
$js='
		var url_root="'.JUri::root().'";
		var this_host="'.$host.'";
		jQuery.noConflict();
		';
$doc->addScriptDeclaration($js);
?>
<?php
echo $this->loadTemplateByLayout('mainmenu','default');
?>

<div class="view-bookpro-tour">
    <div class="row-fluid toolbar2">
        <div class="span6 margin-dashboard">
            <div class="pull-left">
                <h1 style="font-size: 22px; color: #999999;text-transform: uppercase" ><i class="icon-screen screen-dashboard" style="width: 30px;height: 30px"></i>Dashboard</h1>
            </div>
        </div>
        <div class="span6">
            <div class="pull-right i-icon-header">
                <span class="icon-loop"></span>
                <span class="icon-menu-3"></span>
                <span class="icon-edit"></span>
                <span class="icon-question-sign"></span>
            </div>
        </div>
    </div>
    <br/>
    <div class="row-fluid">
        <div class="span2 big-icon ">
            <div class="row-fluid">
                <div class="count-order pull-left">
                    <div class="pull-left">
                        <h2>10</h2>
                        <label>bookings</label>
                    </div>
                    <a href="index.php?option=com_bookpro"></a>
                </div>
                <a href="index.php?option=com_bookpro&view=bookpro&layout=tour"><i
                        class="icon-cart pull-right more"></i></a>
            </div>
        </div>
        <div class="span2 big-icon ">
            <div class="row-fluid">
                <div class="count-order pull-left">
                    <div class="pull-left">
                        <h2>3</h2>
                        <label>settings changed</label>
                    </div>
                </div>
                <i class="icon-wrench pull-right more"></i>
            </div>
        </div>
        <div class="span2 big-icon ">
            <div class="row-fluid">
                <div class="count-order pull-left">
                    <div class="pull-left">
                        <h2>0</h2>
                        <label>new tours</label>
                    </div>
                </div>
                <i class="icon-downarrow pull-right more"></i>
            </div>
        </div>
        <div class="span2 big-icon ">
            <div class="row-fluid">
                <div class="count-order pull-left">
                    <div class="pull-left">
                        <h2>6</h2>
                        <label>new reviews</label>
                    </div>
                </div>
                <i class="icon-comments-2 pull-right more"></i>
            </div>
        </div>
        <div class="span2 big-icon ">
            <div class="row-fluid">
                <div class="count-order pull-left">
                    <div class="pull-left">
                        <h2>8</h2>
                        <label>payments</label>
                    </div>
                </div>
                <i class="icon-database pull-right more"></i>
            </div>
        </div>
        <div class="span2 big-icon ">
            <div class="row-fluid">
                <div class="count-order pull-left">
                    <div class="pull-left">
                        <h2>110</h2>
                        <label>Tour</label>
                    </div>
                </div>
                <i class="icon-flag-2 pull-right more"></i>
            </div>
        </div>
    </div>
    <div class="row-fluid bg-chart">
        <div class="bg-chart-margin">
            <div class="span5 border-chart">
                <div class="panel panel-teal toggle panelClose panelRefresh">
                    <!-- Start .panel -->
                    <div class=panel-heading>
                        <h4 class=panel-title><i class=im-bars></i> web visitors</h4>
                    </div>
                    <div class=panel-body>
                        <div id="stats-pageviews" style="width: 100%; height:250px; border: #cccccc solid 1px">
                        </div>
                    </div>
                    <div class="panel-footer teal-bg">
                        <div class="span3">
                            <div class="tile teal m0">
                                <div class="tile-content text-center pl0 pr0">
                                    <div id=countToday class=number>75</div>
                                    <h3>Today</h3>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="tile teal m0">
                                <div class="tile-content text-center pl0 pr0">
                                    <div id=countYesterday class=number>69</div>
                                    <h3>Yesterday</h3>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="tile teal m0">
                                <div class="tile-content text-center pl0 pr0">
                                    <div id=countWeek class=number>380</div>
                                    <h3>This Week</h3>
                                </div>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="tile teal m0">
                                <div class="tile-content text-center pl0 pr0">
                                    <div id=countTotal class=number>1254</div>
                                    <h3>Total</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End .panel -->
            </div>

            <div class="span7 border-chart">
            <div class="panel panel-teal toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class=panel-heading>
                    <h4 class=panel-title><i class=im-bars></i> booking & enquiry chart</h4>
                </div>
                <div class=panel-body>
                    <div id="stats-booking" style="width: 100%; height:250px; border: #cccccc solid 1px;padding-right: 1px">

                    </div>
                </div>
                <div class="panel-footer teal-bg">
                    <div class="span3">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countToday class=number>75</div>
                                <h3>Today</h3>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countYesterday class=number>69</div>
                                <h3>Yesterday</h3>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countWeek class=number>380</div>
                                <h3>This Week</h3>
                            </div>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countTotal class=number>1254</div>
                                <h3>Total</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="row-fluid bg-chart-2">
        <div class="bg-chart-margin">
            <div class="span5">
                    <div class="row-fluid">
                        <div id="top-revenue-by-tour" class="panel-body bg-chart-margin-border" style="border: #cccccc solid 1px"></div>
                    </div>
                    <div class="row-fluid">
                        <div id="top-revenue-by-month" class="panel-body bg-chart-margin-border" style="border: #cccccc solid 1px"></div>
                        <pre id="tsv" style="display:none;border: #cccccc solid 1px">Browser Version	Total Market Share
                            Microsoft Internet Explorer 8.0	26.61%
                            Microsoft Internet Explorer 9.0	16.96%
                            Chrome 18.0	8.01%
                            Chrome 19.0	7.73%
                            Firefox 12	6.72%
                            Microsoft Internet Explorer 6.0	6.40%
                            Firefox 11	4.72%
                            Microsoft Internet Explorer 7.0	3.55%
                            Safari 5.1	3.53%
                            Firefox 13	2.16%
                            Firefox 3.6	1.87%
                            Opera 11.x	1.30%
                            Chrome 17.0	1.13%
                            Firefox 10	0.90%
                            Safari 5.0	0.85%
                            Firefox 9.0	0.65%
                            Firefox 8.0	0.55%
                            Firefox 4.0	0.50%
                            Chrome 16.0	0.45%
                            Firefox 3.0	0.36%
                            Firefox 3.5	0.36%
                            Firefox 6.0	0.32%
                            Firefox 5.0	0.31%
                            Firefox 7.0	0.29%
                            Proprietary or Undetectable	0.29%
                            Chrome 18.0 - Maxthon Edition	0.26%
                            Chrome 14.0	0.25%
                            Chrome 20.0	0.24%
                            Chrome 15.0	0.18%
                            Chrome 12.0	0.16%
                            Opera 12.x	0.15%
                            Safari 4.0	0.14%
                            Chrome 13.0	0.13%
                            Safari 4.1	0.12%
                            Chrome 11.0	0.10%
                            Firefox 14	0.10%
                            Firefox 2.0	0.09%
                            Chrome 10.0	0.09%
                            Opera 10.x	0.09%
                            Microsoft Internet Explorer 8.0 - Tencent Traveler Edition	0.09%</pre>

                    </div>
                    <div class="row-fluid"></div>
            </div>
            <div class="span7">
            <div class="border-calendar">
            <div id="scheduler_here" class="dhx_cal_container panel-body" style='width:auto; height:600px;'>
                <div class="dhx_cal_navline">
                    <div class="dhx_cal_prev_button">&nbsp;</div>
                    <div class="dhx_cal_next_button">&nbsp;</div>
                    <div class="dhx_cal_today_button"></div>
                    <div class="dhx_cal_date"></div>
                </div>
                <div class="dhx_cal_header">
                </div>
                <div class="dhx_cal_data">
                </div>
            </div>
                </div>
        </div>
        </div>
    </div>
    <div class="row-fluid bg-Latest-booking">
        <div class="span6">
            <fieldset>
                <legend>
                    <?php echo JText::_('Latest booking'); ?>
                    <div class="pull-right">
                        <a href="<?php echo Juri::base() ?>index.php?option=com_bookpro&view=orders"> View all </a>
                    </div>
                </legend>

                <form action="index.php" method="post" name="adminForm" id="adminForm">


                    <table class="table table-striped ">
                        <thead>
                        <tr>


                            <th><?php echo JText::_("COM_BOOKPRO_CUSTOMER"); ?>    </th>
                            <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                            <th><?php echo JText::_("COM_BOOKPRO_ORDER_TOTAL"); ?></th>
                            <th><?php echo JText::_("COM_BOOKPRO_ORDER_PAY_STATUS"); ?></th>
                            <th><?php echo JText::_("Actions"); ?></th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php if ($itemsCount == 0) { ?>
                            <tr>
                                <td colspan="13" class="emptyListInfo"><?php echo JText::_('No booking today.'); ?></td>
                            </tr>
                        <?php } ?>
                        <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
                            <?php $subject = &$this->items[$i]; ?>

                            <tr class="row<?php echo $i % 2; ?>">
                                <td>
                                    <a href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->user_id)); ?>"><?php echo $subject->ufirstname; ?></a>
                                    <br>
                                    <?php echo JHtml::_('date', $subject->created, 'd-m H:i') ?>
                                </td>

                                <td>
                                    <a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $subject->order_number; ?></a>
                                </td>
                                <td><?php echo CurrencyHelper::formatprice($subject->total) ?></td>
                                <td><?php echo PayStatus::format($subject->pay_status); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Action
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Add payment</a></li>
                                            <li><a href="#">View detail</a></li>
                                            <li><a href="#">Edit</a></li>
                                        </ul>
                                    </div>

                                </td>

                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
                    <input type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>"/>
                    <input type="hidden" name="reset" value="0"/>
                    <input type="hidden" name="cid[]" value=""/>
                    <input type="hidden" name="boxchecked" value="0"/>
                    <input type="hidden" name="filter_order" value="<?php echo $order; ?>"/>
                    <input type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>"/>
                    <input type="hidden" name="controller" value="<?php echo CONTROLLER_ORDER; ?>"/>
                    <?php echo JHTML::_('form.token'); ?>
                </form>
            </fieldset>
        </div>
        <div class="span6">
            <?php if (count($this->myorderasign)) { ?>
                <?php echo $this->loadTemplate('myorderasign') ?>
            <?php } ?>

        </div>
    </div>
    <div class="row-fuild bg-list-messages">
        <div class="span12">
            <?php echo $this->loadTemplate('messages') ?>
        </div>
    </div>
</div>
<style>
    .subhead-collapse {
        display: none;
    }

    .header {
        display: none;
    }
</style>
