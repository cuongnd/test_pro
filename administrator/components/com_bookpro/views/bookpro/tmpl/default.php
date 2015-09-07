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

$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-bookpro-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-bookpro-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);

$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/Highcharts-4.1.1/js/highcharts.js');
$doc->addScript(JUri::root().'/media/Highcharts-4.1.1/js/modules/data.js');
$doc->addStyleSheet(JUri::root().'/administrator/components/com_bookpro/assets/css/view-bookpro.css');
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-bookpro-default.css');

$doc->addScript(JUri::root().'/administrator/components/com_bookpro/assets/js/view-bookpro-default.js');





?>
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

<div class=outlet>
    <!-- Start .outlet -->
    <!-- Page start here ( usual with .row ) -->
    <div class=row>
        <!-- Start .row -->
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel vertical slide">
                <div class=carousel-inner>
                    <div class="item active">
                        <div class="tile red">
                            <div class=tile-icon><i class="br-cart s64"></i></div>
                            <div class=tile-content>
                                <div class=number>107</div>
                                <h3>Tours</h3>
                            </div>
                        </div>
                    </div>
                    <div class=item>
                        <div class="tile orange">
                            <!-- tile start here -->
                            <div class=tile-icon><i class="en-earth s64"></i></div>
                            <div class=tile-content>
                                <div class=number>5</div>
                                <h3>Tours</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Carousel -->
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class=carousel-inner>
                    <div class="item active">
                        <div class="tile blue">
                            <div class=tile-icon><i class="im-home s64"></i></div>
                            <div class=tile-content>
                                <div class=number>24</div>
                                <h3>Hotels</h3>
                            </div>
                        </div>
                    </div>
                    <div class=item>
                        <div class="tile brown">
                            <!-- tile start here -->
                            <div class=tile-icon><i class="ec-mail s64"></i></div>
                            <div class=tile-content>
                                <div class=number>17</div>
                                <h3>New emails</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Carousel -->
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel vertical slide">
                <div class=carousel-inner>
                    <div class="item active">
                        <div class="tile green">
                            <div class=tile-icon><i class="ec-users s64"></i></div>
                            <div class=tile-content>
                                <div class=number>325</div>
                                <h3>New users</h3>
                            </div>
                        </div>
                    </div>
                    <div class=item>
                        <div class="tile purple">
                            <!-- tile start here -->
                            <div class=tile-icon><i class="ec-search s64"></i></div>
                            <div class=tile-content>
                                <div class=number>2540</div>
                                <h3>Searches</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Carousel -->
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class=carousel-inner>
                    <div class="item active">
                        <div class="tile teal">
                            <!-- tile start here -->
                            <div class=tile-icon><i class="ec-images s64"></i></div>
                            <div class=tile-content>
                                <div class=number>45</div>
                                <h3>New images</h3>
                            </div>
                        </div>
                    </div>
                    <div class=item>
                        <div class="tile magenta">
                            <!-- tile start here -->
                            <div class=tile-icon><i class="ec-share s64"></i></div>
                            <div class=tile-content>
                                <div class=number>3548</div>
                                <h3>Posts shared</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Carousel -->
        </div>
    </div>
    <!-- End .row -->
    <div class=row>
        <!-- Start .row -->
        <div class="col-lg-6 col-md-6">
            <!-- Start col-lg-6 -->
            <div class="panel panel-teal toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class=panel-heading>
                    <h4 class=panel-title><i class=im-bars></i> Page views</h4>
                </div>
                <div class=panel-body>
                    <div id=stats-pageviews style="width: 100%; height:250px"></div>
                </div>
                <div class="panel-footer teal-bg">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countToday class=number>75</div>
                                <h3>Today</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countYesterday class=number>69</div>
                                <h3>Yesterday</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countWeek class=number>380</div>
                                <h3>This Week</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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





        <div class="col-lg-6 col-md-6">
            <!-- Start col-lg-6 -->
            <div class="panel panel-teal toggle panelClose panelRefresh">
                <!-- Start .panel -->
                <div class=panel-heading>
                    <h4 class=panel-title><i class=im-bars></i> Page views</h4>
                </div>
                <div class=panel-body>
                    <div id=week-earnings style="width: 100%; height:250px"></div>
                </div>
                <div class="panel-footer teal-bg">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countToday class=number>75</div>
                                <h3>Today</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countYesterday class=number>69</div>
                                <h3>Yesterday</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile teal m0">
                            <div class="tile-content text-center pl0 pr0">
                                <div id=countWeek class=number>380</div>
                                <h3>This Week</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
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
        <!-- End col-lg-6 -->
    </div>
    <!-- End .row -->
    <!-- Page End here -->


    <div class=row>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                    <div class="item active">
                        <div class="tile enquiry">
                            <div class=tile-icon><i class="im-mail"></i></div>
                            <div class=tile-content>
                                <div class=number>15</div>
                                <h3>new</h3>
                            </div>
                        </div>
                        <div class="title-bottom-item"><label>ENQUIRY</label></div>
                    </div>

            </div>

            <!-- End Carousel -->
        </div>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class="item active">
                    <div class="tile booking">
                        <div class=tile-icon><i class="im-remove"></i></div>
                        <div class=tile-content>
                            <div class=number>20</div>
                            <h3>new</h3>
                        </div>
                    </div>
                </div>
                <div class="title-bottom-item"><label>BOOKING</label></div>
            </div>

            <!-- End Carousel -->
        </div>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class="item active">
                    <div class="tile helpdesk">
                        <div class=tile-icon><i class="en-question"></i></div>
                        <div class=tile-content>
                            <div class=number>30</div>
                            <h3>new</h3>
                        </div>
                    </div>
                    <div class="title-bottom-item"><label>HELPDESK</label></div>
                </div>
            </div>

            <!-- End Carousel -->
        </div>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class="item active">
                    <div class="tile gallery">
                        <div class=tile-icon><i class="im-image"></i></div>
                        <div class=tile-content>
                            <div class=number>50</div>
                            <h3>new</h3>
                        </div>
                    </div>
                </div>
                <div class="title-bottom-item"><label>GALLERY</label></div>
            </div>

            <!-- End Carousel -->
        </div>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class="item active">
                    <div class="tile article">
                        <div class=tile-icon><i class="im-address-book"></i></div>
                        <div class=tile-content>
                            <div class=number>10</div>
                            <h3>new</h3>
                        </div>
                    </div>
                </div>
                <div class="title-bottom-item"><label>ARTICLE</label></div>
            </div>

            <!-- End Carousel -->
        </div>

        <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
            <div class="carousel-tile carousel slide">
                <div class="item active">
                    <div class="tile review">
                        <div class=tile-icon><i class="im-paragraph-justify2"></i></div>
                        <div class=tile-content>
                            <div class=number>15</div>
                            <h3>new</h3>
                        </div>
                    </div>
                </div>
                <div class="title-bottom-item"><label>REVIEW</label></div>
            </div>

            <!-- End Carousel -->
        </div>

    </div>
</div>
<!-- End .outlet -->

<style>
    .subhead-collapse
    {
        display: none;
    }
    .header
    {
        display: none;
    }
</style>