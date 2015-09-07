<?php
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('bootstrap.framework');
AImporter::helper('image','currency','tour');
AImporter::css('country');
AImporter::model('country');
$app = JFactory::getApplication();
$input = $app->input;
$country_id = $input->get('country_id',0,'int');
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.paginate.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.sort.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/__jquery.tablesorter/jquery.tablesorter.js');


$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/noosliderlite/script_nooSliderLite.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/view-bustrips.js');

$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/noosliderlite/css/style.css');

$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/css/footable.core.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/__jquery.tablesorter/themes/blue/style.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/css/view-bustrips.css');
$country = TourHelper::getCountryObject($country_id);

?>


<div class="row-fluid">
    <div class="span8 col_left">
        <?php
        $this->setLayout('img_car_rental');
        echo $this->loadTemplate();
        ?>
        <div class="row-fluid">
            <div class="span8">
                <div class="row-fluid">
                    <?php
                    $this->setLayout('city');
                    echo $this->loadTemplate('about');
                    ?>
                </div>
                <div class="row-fluid">
                    <?php
                    $this->setLayout('city');
                    echo $this->loadTemplate('featured_car_rental');
                    ?>
                </div>
            </div>
            <div class="span4">
                <?php
                $this->setLayout('city');
                echo $this->loadTemplate('vacation_tips');
                ?>
            </div>
        </div>
        <div class="row-fluid banner_center" style="padding-top:10px;">
            <img class="image-full" src="images/banner-center.jpg">
        </div>
        <div class="row-fluid">
            <div class="span6">
                <div class="row-fluid">
                    <?php
                    $this->setLayout('city');
                    echo $this->loadTemplate('car_rental_routes');
                    ?>
                </div>
                <div class="row-fluid">
                    <?php
                    $this->setLayout('city');
                    echo $this->loadTemplate('find_car_rental_across_country');
                    ?>
                </div>
            </div>
            <div class="span6">
                <?php
                $this->setLayout('city');
                echo $this->loadTemplate('about2');
                ?>
            </div>
        </div>

    </div>
    <div class="span4 col_right">
        <?php
        $this->setLayout('country');
        echo $this->loadTemplate('featured_car_rental');
        ?>



        <div class="row-fluid right-content">
            <div class="span12">


                <div class="sign_up_col_right">
                    <div class="search-title">Join now for deal & discount alert, save up to 75%</div>
                    <form class="form-search">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td>
                                    <i class="icon_search"></i>
                                </td>
                                <td valign="bottom">
                                    <div class="input-append">
                                        <input type="text"
                                               class="input-medium button_col_right search-query">
                                        <button type="submit" class="btn btn-small">Sign up</button>
                                    </div>
                                </td>
                            </tr>
                        </table>


                    </form>
                </div>
            </div>
        </div>
        <div class="row-fluid right-content">
            <div class="span12">
                <img class="image-full" src="images/lucky.jpg">
            </div>
        </div>
        <?php
        $this->setLayout('country');
        echo $this->loadTemplate('events_in_region');
        ?>


        <div class="row-fluid right-content">
            <img class="image-full" src="components/com_bookpro/assets/images/wherther.jpg">
        </div>
        <div class="row-fluid right-content">
            <img class="image-full" src="/components/com_bookpro/assets/images/banner_country_car1.jpg">
        </div>



    </div>
</div>